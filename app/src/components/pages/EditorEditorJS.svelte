<script>
    import {onMount} from "svelte";
    import {file} from "../../stores/file.js";
    import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import LinkTool from '@editorjs/link';


    let container;
    export let filepath;
    onMount(async () => {
        await file.load(filepath);
        const editor = new EditorJS({
            holder: container,
            tools: {
                header: Header,
                link: LinkTool,
                list: List,

            },
        });


    })


</script>
<div>
    <div bind:this={container} id="container" class="prose mx-auto max-w-none">
    </div>
</div>
<style>

    #container {
        @apply w-full h-screen;
        @apply pt-10;
        @apply bg-teal-100;
    }

</style>

