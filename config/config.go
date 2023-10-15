package config

import (
	log "github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"strings"
)

const ENV_PREFIX = "kip"

const HOST = "server.host"
const PORT = "server.port"
const STORAGE_PATH = "storage.path"
const ENV = "env"
const DEV_FRONTEND_HOST = "dev.frontend.host"

func HostAndPort() string {
	return viper.GetString(HOST) + ":" + viper.GetString(PORT)
}

func StoragePath() string {
	return viper.GetString(STORAGE_PATH)
}

func IsDev() bool {
	return viper.GetString(ENV) == "dev"
}

func IsProd() bool {
	return viper.GetString(ENV) == "prod"
}

func DevFrontendHost() string {
	return viper.GetString(DEV_FRONTEND_HOST)
}

func init() {
	viper.SetDefault(HOST, "0.0.0.0")
	viper.SetDefault(PORT, "9000")
	viper.SetDefault(STORAGE_PATH, "./data")
	viper.SetDefault(ENV, "dev") // dev/prod
	viper.SetDefault(DEV_FRONTEND_HOST, "http://localhost:5713")
	load()
}

func load() {
	log.SetLevel(log.DebugLevel)
	viper.SetEnvPrefix(ENV_PREFIX)

	viper.SetEnvKeyReplacer(strings.NewReplacer(".", "_"))
	viper.AutomaticEnv()

	viper.SetConfigName("config")
	viper.SetConfigType("yaml")
	viper.AddConfigPath("./")

	if viper.GetString(ENV) != "dev" {
		log.SetFormatter(&log.JSONFormatter{})
		log.SetLevel(log.InfoLevel)
	} else {
		err := viper.ReadInConfig() // Find and read the config file.js
		if err != nil {             // Handle errors reading the config file.js
			log.Warn("config file.js does not exist. You can either add a config.yaml file.js, or set the " + ENV_PREFIX + "_ENV envVar to prod to not see this message anymore")
		} else {
			//seeeeeeeelf update config :wink: :wink: if you add new keys.
			viper.WriteConfig()
			log.Debug("Updating configuration")
		}
	}

}
