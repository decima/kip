package entityManager

import "strings"

type tempFile struct {
	name  string
	isDir bool
}

func RemoveContentSuffix(name string) string {
	name = strings.TrimSuffix(name, ".md")
	return name
}

func addContentSuffix(name string) string {
	name = strings.TrimSuffix(name, "/")

	if !hasContentSuffix(name) {
		name = name + ".md"
	}
	return name
}

func hasContentSuffix(name string) bool {
	return strings.HasSuffix(name, ".md")
}
