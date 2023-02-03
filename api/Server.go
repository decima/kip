package api

import (
	"BOILERPLATE/utils"
	"github.com/gin-contrib/static"
	"github.com/gin-gonic/gin"
	log "github.com/sirupsen/logrus"
	"net/http"
)

func Serve(hostAndPort string) {

	gin.SetMode(gin.ReleaseMode)
	engine := gin.New()

	//serve static files from dist
	engine.Use(static.Serve("/", static.LocalFile("./dist", false)))

	//Handle router for svelte (any unknown page brings back to /)
	engine.NoRoute(func(c *gin.Context) {
		log.WithField("path", c.Request.URL.Path).Debug("calling unknown path")
		c.File("./dist/index.html")
	})

	// create an apiGroup (prefix for every route '/api')
	// If you change this, think about changing it as well in vite.config.js and everywhere the api is called if it is hardcoded.
	apiGroup := engine.Group("/api")

	registerRoutes(apiGroup)

	log.WithFields(log.Fields{"address": hostAndPort}).Info("Starting server")
	engine.Run(hostAndPort)
}

func registerRoutes(apiGroup *gin.RouterGroup) {
	// You can separate your routes in sub folders, feel free to organize as you want
	apiGroup.GET("/", func(c *gin.Context) {
		c.Writer.WriteHeader(http.StatusOK)
		c.Writer.WriteString("it works")
	})

	apiGroup.GET("/health", func(c *gin.Context) {
		c.JSON(http.StatusOK, gin.H{
			"uptime": utils.Uptime().Seconds(),
			"status": "ok",
		})
	})

}
