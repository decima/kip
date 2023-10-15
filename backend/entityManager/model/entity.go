package model

type Entity struct {
	Name     string      `json:"name,omitempty"`
	Path     string      `json:"path,omitempty"`
	Children *EntityList `json:"children,omitempty"`
}

type EntityList []Entity
