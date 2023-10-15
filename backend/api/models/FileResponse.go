package models

import (
	"kip/backend/entityManager/model"
)

type FileResponse struct {
	Name         string           `json:"name"`
	Path         string           `json:"path"`
	DownloadPath string           `json:"downloadPath,omitempty"`
	HasContent   *bool            `json:"hasContent,omitempty"`
	HasChildren  *bool            `json:"hasChildren,omitempty"`
	Children     model.EntityList `json:"children,omitempty"`
	Content      string           `json:"content,omitempty"`
}
