import {writable} from "svelte/store";

function initFileTree() {
    const {subscribe, set} = writable([]);

    async function refresh() {
        const result = await fetch("/_/api/tree/");
        const content = await result.json()
        set(content)
        return content

    }

     refresh()

    return {
        subscribe,
        refresh,
        set,

    }
}

export const fileTree = initFileTree();

