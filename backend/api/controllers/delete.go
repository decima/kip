package controllers

import (
	"github.com/gin-gonic/gin"
	"kip/backend/entityManager"
)

func HandleDelete(em entityManager.EntityManager) gin.HandlerFunc {

	return func(c *gin.Context) {
		path := c.Param("path")
		em.DeleteFile(path)
		c.JSON(204, nil)
	}
}
