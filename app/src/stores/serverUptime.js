import {writable} from "svelte/store";

function initServerUptime() {
    const {subscribe, set, update} = writable({uptime: 0});

    return {
        subscribe,
        refresh() {
            fetch("/api/health")
                .then((response) => response.json())
                .then(set)
        }

    }
}

export const serverUptime = initServerUptime();

