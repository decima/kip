import {get, writable} from "svelte/store";
import {toasts} from "./toasts.js";

 function initFile() {


    const {subscribe, set, update} = writable({content: null})

    async function load(path) {
        const result = await fetch("/_/api/file/" + path);
        const content = await result.json()
        if(!content.content) content.content = ""
        try{content.content = JSON.parse(content.content)}catch (e){}

        set(content)
        return content

    }


    async function save() {
        const e = get({subscribe})
        const result = await fetch("/_/api/file" + e.path, {
            method: "PUT",
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({content: e.content, references: []})
        });
        if(result.status<300){
            toasts.add("Saved", "success")
        }


    }

    async function addRaw(path, content){

        const result = await fetch("/_/api/file/" + path+"?raw", {
            method: "PUT",
            headers: {
                'Content-Type': 'application/octet-stream'
            },
            body: content
        });
        return result.json()
    }

    return {
        subscribe,
        load,
        set,
        save,
        addRaw,
    }
}

export const file =  initFile();
