package controllers

import (
	"github.com/gin-gonic/gin"
	"kip/backend/api/models"
	"kip/backend/entityManager"
	"strings"
)

func HandlePost(em entityManager.EntityManager) gin.HandlerFunc {

	return func(c *gin.Context) {
		path := c.Param("path")
		content, _ := c.GetRawData()
		var err error
		if path == "" || path == "/" {
			path = "/README"
		}
		err = em.SaveFile(path, content)
		if err != nil {
			c.JSON(500, gin.H{"oups": "something went wrong"})
			return
		}

		path = entityManager.RemoveContentSuffix(path)
		path = strings.TrimSuffix(path, "/")
		splitted := strings.Split(path, "/")
		name := splitted[len(splitted)-1]
		c.JSON(200, models.FileResponse{
			Name: name,
			Path: path,
		})
	}
}
