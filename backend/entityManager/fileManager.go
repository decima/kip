package entityManager

import (
	"errors"
	"fmt"
	"kip/backend/entityManager/model"
	"os"
	"path/filepath"
	"sort"
	"strings"
)

type FileManager struct {
	BasePath string
}

var ErrorForbidden = errors.New("Forbidden")
var _ EntityManager = &FileManager{}

func (f FileManager) ListFiles(path string) (model.EntityList, error) {
	if err := f.errorOnParentRoot(path); err != nil {
		return nil, err
	}

	out := model.EntityList{}

	path = RemoveContentSuffix(path)
	isRootDir := path == "" || path == "/"

	if !strings.HasSuffix(path, "/") {
		path = path + "/"
	}

	files, err := os.ReadDir(f.BasePath + path)
	if err != nil {
		return nil, err
	}

	fileList := map[string]tempFile{}
	for _, file := range files {
		name := file.Name()
		name = RemoveContentSuffix(name)
		filePath := path + name
		isDir := file.IsDir()
		if name == "README" && isRootDir {
			continue
		}
		if tmp, ok := fileList[filePath]; ok {
			isDir = isDir || tmp.isDir
		}
		fileList[filePath] = tempFile{name, isDir}

	}

	for filePath, file := range fileList {

		var children *model.EntityList
		if file.isDir {
			c, err := f.ListFiles(filePath)
			if err != nil {
				return nil, err
			}
			children = &c
		}

		out = append(out, model.Entity{
			Name:     file.name,
			Path:     filePath,
			Children: children,
		})
	}
	sort.Slice(out, func(i, j int) bool {
		return out[i].Name < out[j].Name
	})
	return out, nil
}

func (f FileManager) GetFile(path string) ([]byte, error) {
	if err := f.errorOnParentRoot(path); err != nil {
		return nil, err
	}
	path = addContentSuffix(path)
	//read file
	out, err := os.ReadFile(f.BasePath + path)
	if err != nil {
		if os.IsNotExist(err) {
			return []byte{}, nil
		}
		return nil, err
	}
	return out, nil
}

func (f FileManager) DeleteFile(path string) error {
	if err := f.errorOnParentRoot(path); err != nil {
		return err
	}
	folder := f.BasePath + path
	file := addContentSuffix(folder)

	if err := os.Remove(file); err != nil {
		return err
	}

	return os.RemoveAll(folder)

}

func (f FileManager) Exists(path string, asFile bool) bool {

	if err := f.errorOnParentRoot(path); err != nil {
		return false
	}

	if asFile {

		path = addContentSuffix(path)
	}
	stats, err := os.Stat(f.BasePath + path)
	return err == nil && stats.IsDir() != asFile
}

func (f FileManager) RawFileExists(path string) bool {
	if err := f.errorOnParentRoot(path); err != nil {
		return false
	}

	stats, err := os.Stat(f.BasePath + path)
	return err == nil && !stats.IsDir()
}

func (f FileManager) RawFileGet(path string) ([]byte, error) {

	if err := f.errorOnParentRoot(path); err != nil {
		return nil, err
	}

	return os.ReadFile(f.BasePath + path)
}

func (f FileManager) SaveFile(path string, content []byte) error {
	file := f.BasePath + path

	if err := f.errorOnParentRoot(path); err != nil {
		return err
	}
	dir := filepath.Dir(file)
	os.MkdirAll(dir, 0755)

	if f.Exists(path, false) {
		file = addContentSuffix(file)
	} else if f.Exists(path, true) {
		file = addContentSuffix(file)
	}
	return os.WriteFile(file, content, 0644)
}

func (f FileManager) errorOnParentRoot(path string) error {
	file := f.BasePath + path
	absolute, _ := filepath.Abs(file)
	fmt.Println(f.BasePath)
	fmt.Println(absolute)
	if !strings.HasPrefix(absolute, f.BasePath) {
		return ErrorForbidden
	}
	return nil
}
