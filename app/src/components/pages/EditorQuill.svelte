<script>
    import {onDestroy, onMount} from "svelte";
    import {file} from "../../stores/file.js";
    import Quill from 'quill/dist/quill.js';
    import 'quill/dist/quill.snow.css';

    let container;
    let quill;
    let toolbar;
    let live="";
    export let location, navigate, filepath;
    const editorLoad = async (e) => {
        await file.load(e.detail.filePath);
        quill.root.innerHTML = $file.content;
    }
    onMount(async () => {
        await file.load(filepath);
        quill = new Quill(container, {
            theme: "snow",
            modules: {
                toolbar: {
                    container: toolbar
                }
            }
        });
        document.addEventListener('newPath', editorLoad)
        quill.root.innerHTML = $file.content;
        quill.on('text-change', function (delta, oldDelta, source) {
            console.log("content has changed")
            live = quill.getContents()
            $file.content = quill.root.innerHTML;
        });
    })

    onDestroy(() => {
        document.removeEventListener('newPath', editorLoad)
    })

    const save = () => {
        file.save();

    }


</script>
<div>
    <div class="toolbar" bind:this={toolbar}>
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-strike"></button>
        <button class="ql-blockquote"></button>
        <button class="ql-code-block"></button>
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-indent" value="-1"></button>
        <button class="ql-indent" value="+1"></button>
        <button class="ql-direction" value="rtl"></button>
        <select class="ql-color"></select>
        <select class="ql-background"></select>
        <select class="ql-size"></select>
        <button class="ql-clean"></button>
        <select class="ql-header">
            <option selected></option>
            <option value="1"></option>
            <!-- Note a missing, thus falsy value, is used to reset to default -->
            <option value="2"></option>
            <option value="3"></option>
            <option value="4"></option>
        </select>

        <select class="ql-font">
            <option selected></option>
            <option value="serif"></option>
            <option value="monospace"></option>
        </select>


        <select class="ql-align">
            <option selected></option>
            <option value="center"></option>
            <option value="right"></option>
            <option value="justify"></option>
        </select>
        <button class="ql-link"></button>
        <button class="ql-image"></button>

        <button class="ql-video"></button>
        <button class="ql-formula"></button>
        <button class="ql-code"></button>

        <button on:click={save}><i class="fa-solid fa-floppy-disk"></i></button>
    </div>
    <div bind:this={container} id="container">

        <div id="editor" contenteditable="true"></div>
    </div>
    <textarea class="w-screen h-screen font-mono"  readonly>{JSON.stringify(live, null, 2)}</textarea>
</div>
<style>

    #container {
        @apply w-full h-screen;
        @apply pt-10;
    }

    .toolbar {
        @apply bg-gray-200 w-full z-10;
        position: fixed;
    }
</style>

