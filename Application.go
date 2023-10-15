package main

import (
	log "github.com/sirupsen/logrus"
	"kip/backend/api"
	"kip/backend/entityManager"
	"kip/config"
	"path/filepath"
)

var version string = "dev"

func main() {
	log.Info("current Version " + version)
	log.Info("Frontend on " + config.DevFrontendHost())
	basePath, _ := filepath.Abs(config.StoragePath())
	var em entityManager.EntityManager = entityManager.FileManager{BasePath: basePath}
	api.Serve(config.HostAndPort(), em)
}
