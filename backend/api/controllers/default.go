package controllers

import (
	"github.com/gin-gonic/gin"
	log "github.com/sirupsen/logrus"
	"kip/backend/api/models"
	"kip/backend/entityManager"
	"net/http"
	"os"
	"strings"
)

const distPath = "./dist"

func HandleNoRoute(em entityManager.EntityManager) gin.HandlerFunc {

	return func(c *gin.Context) {
		accept := c.Request.Header["Accept"]
		path := c.Request.URL.Path
		path = strings.TrimSuffix(path, "/")
		readDistFilePath := distPath + path

		if file, err := os.Stat(readDistFilePath); err == nil && !file.IsDir() {
			log.Println("serving file " + readDistFilePath)
			c.File(readDistFilePath)
			return
		}

		if em.RawFileExists(path) {
			log.Println("serving raw file " + path)
			content, _ := em.RawFileGet(path)

			mime := http.DetectContentType(content)
			c.Data(200, mime, content)
			return
		}

		if isJsonAccept(accept) {
			splitted := strings.Split(path, "/")
			name := splitted[len(splitted)-1]
			if name == "" {
				name = "home"
				path = "/"
			}

			pathForContent := path
			if path == "" || path == "/" {
				pathForContent = "/README"
			}
			var hasChildren bool = false
			var hasContent bool = false
			response := models.FileResponse{
				Name:        name,
				Path:        path,
				HasContent:  &hasContent,
				HasChildren: &hasChildren,
			}
			if em.Exists(path, false) {
				content, _ := em.ListFiles(path)
				hasChildren = true
				response.Children = content
			}
			if em.Exists(pathForContent, true) {
				content, _ := em.GetFile(pathForContent)
				hasContent = true

				response.DownloadPath = pathForContent + ".md"
				response.Content = string(content)
			}
			if path == "/README" {
				response.Path = "/"
			}
			statusCode := http.StatusOK
			if !hasContent && !hasChildren {
				statusCode = http.StatusNotFound
			}
			c.JSON(statusCode, response)
			return
		}
		c.File(distPath + "/index.html")
		return
	}
}

func isJsonAccept(accept []string) bool {
	for _, a := range accept {
		if a == "application/json" {
			return true
		}
	}
	return false
}
