package config

import (
	log "github.com/sirupsen/logrus"
	"github.com/spf13/viper"
	"strings"
)

const CLIENT_HOST = "client.host"
const CLIENT_PORT = "client.port"

const ENV_PREFIX = "BOILERPLATE"

const HOST = "server.host"
const PORT = "server.port"
const ENV = "env"

func HostAndPort() string {
	return viper.GetString(HOST) + ":" + viper.GetString(PORT)
}

func init() {
	viper.SetDefault(HOST, "0.0.0.0")
	viper.SetDefault(PORT, "9000")
	viper.SetDefault(ENV, "dev") // dev/prod
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
		err := viper.ReadInConfig() // Find and read the config file
		if err != nil {             // Handle errors reading the config file
			log.Warn("config file does not exist. You can either add a config.yaml file, or set the " + ENV_PREFIX + "_ENV envVar to prod to not see this message anymore")
		} else {
			//seeeeeeeelf update config :wink: :wink: if you add new keys.
			viper.WriteConfig()
			log.Debug("Updating configuration")
		}
	}

}
