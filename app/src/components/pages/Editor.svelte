<script>
    import {onMount} from "svelte";
    import {file} from "../../stores/file.js";
    import Tiptap from "../../lib/Tiptap.svelte";

    let loading = true;
    let container;
    export let filepath;
    onMount(async () => {
        await file.load(filepath);
        loading = false;
    })

    document.addEventListener('newPath', editorLoad)
    async function editorLoad(e) {
        loading = true;
        filepath = e.detail.filePath;
        await file.load(filepath);
        loading = false;
    }


    async function uploader(fileObject){
        const blob = await fileObject.arrayBuffer();
        const path = filepath + '/' + fileObject.name;
        return file.addRaw(path, blob);
    }


</script>
{#if loading}
    loading...
{:else}
    <div class="h-screen box-border ">
    <Tiptap bind:content={$file.content} bind:path={filepath} uploader={uploader}/>
    </div>
{/if}
