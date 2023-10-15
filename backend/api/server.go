package api

import (
	"github.com/gin-gonic/gin"
	"kip/backend/api/controllers"
	"kip/backend/entityManager"
	"kip/config"
)

func Serve(hostAndPort string, em entityManager.EntityManager) {

	if config.IsProd() {
		gin.SetMode(gin.ReleaseMode)
	}
	engine := gin.Default()
	engine.SetTrustedProxies(nil)
	engine.NoRoute(controllers.HandleNoRoute(em))
	engine.POST("/*path", controllers.HandlePost(em))
	engine.DELETE("/*path", controllers.HandleDelete(em))
	engine.Run(hostAndPort)
}
