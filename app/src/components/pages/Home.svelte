<script>


    import {file} from "../../stores/file.js";
    import {onDestroy, onMount} from "svelte";

    export let location, navigate, filepath;
    onMount(() => {
        file.load(filepath);
        document.addEventListener('newPath', load)
    })
    onDestroy(() => {
        document.removeEventListener('newPath', load)
    })

    const load=async (e) => {
        file.load(e.detail.filePath);
    }



</script>
<div class="prose mx-auto ">
    {@html $file.content}
</div>
