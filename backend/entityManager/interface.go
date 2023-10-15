package entityManager

import "kip/backend/entityManager/model"

type EntityManager interface {
	ListFiles(path string) (model.EntityList, error)
	GetFile(path string) ([]byte, error)
	DeleteFile(path string) error
	Exists(path string, asFile bool) bool
	RawFileExists(path string) bool
	RawFileGet(path string) ([]byte, error)
	SaveFile(path string, content []byte) error
}
