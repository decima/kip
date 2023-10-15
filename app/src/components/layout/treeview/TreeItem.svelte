<script>
    import Tree from "./Tree.svelte";
    import { link, Route, Router } from "svelte-navigator";
    import {createEventDispatcher} from "svelte";
    import {file} from "../../../stores/file.js";

    export let item;
    export let editorMode=false;
    let expanded = false; //TODO set to false or save in localstorage
    const dispatcher = createEventDispatcher()
</script>


<div class="inline-block">
    {#if item.children}

        <span on:click={()=>expanded=!expanded}>
            {#if expanded}
                <i class="fas fa-sharp fa-folder-open"></i>
            {:else }
                <i class="fas fa-sharp fa-folder"></i>
            {/if}
        </span>
        {:else }
        <i class="fa-sharp fas fa-file"></i>
    {/if}
    <a href="{(editorMode?'/_/edit':'')+item.path}" use:link on:click={()=>{dispatcher('menuchange');expanded=true}}>
        {item.name}
    </a>
</div>

<div class="border-l border-l-2 ml-1.5 pl-2">
    {#if item.children}
        <Tree items={item.children}  bind:editorMode bind:expanded on:menuchange/>
    {/if}
</div>