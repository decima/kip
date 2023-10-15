import {defineConfig, loadEnv} from 'vite';
import {svelte} from '@sveltejs/vite-plugin-svelte'
import {parse} from "yaml";
import {readFileSync} from 'fs'
import pluginRewriteAll from 'vite-plugin-rewrite-all';

let projectConfiguration;
try {

//for development purpose only
    projectConfiguration = parse(readFileSync('../config.yaml', 'utf8'))
} catch (e) {
    try {
        projectConfiguration = parse(readFileSync('../config.yml', 'utf8'))
    } catch (e) {
        projectConfiguration = {server: {host: "0.0.0.0", port: 0}}
    }
}

export default ({mode}) => {
    process.env = {...process.env, ...loadEnv(mode, process.cwd())};

    return defineConfig({
        plugins: [svelte(), pluginRewriteAll()],
        server: {
            proxy: {
                '/_/api': 'http://' + projectConfiguration.server.host + ":" + projectConfiguration.server.port, //for development purpose only
            }
        }
    });
}