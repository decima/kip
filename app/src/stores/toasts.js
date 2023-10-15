import {writable} from "svelte/store";

 function initToasts() {


    const {subscribe, set, update} = writable([])

    function add(message, type) {
        update(e => {
            e.push({message, type})
            setTimeout(() => {
                update(e => {
                    e.shift()
                    return e
                })
            }, 2000)
            return e
        })
    }

    return {
        add,
        subscribe,
        set,
        update,
    }
}

export const toasts =  initToasts();