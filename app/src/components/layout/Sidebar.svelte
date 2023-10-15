<script>
    import Tree from "./treeview/Tree.svelte";
    import {fileTree} from "../../stores/fileTree.js";
    import {link, useLocation} from "svelte-navigator";
    import {createEventDispatcher, onMount} from "svelte";
    import {file} from "../../stores/file.js";

    const dispatcher = createEventDispatcher()

    let editorMode = false;
    const location = useLocation();
    let otherPath = ""
    onMount(() => {
        refreshPath()
    })

    function refreshPath() {


        editorMode = false;
        otherPath = "/_/edit" + $location.pathname
        if ($location.pathname.startsWith("/_/edit/")) {
            otherPath = $location.pathname.replace("/_/edit", "")
            editorMode = true;
        }
        return otherPath.replace("/_/edit", "")


    }

    function menuChange(e) {
        const filePath = refreshPath()
        document.dispatchEvent(new CustomEvent("newPath", {detail: {filePath}}))

    }

    const onKeydown = (e) => {
        if (e.key === "Escape" && editorMode) {
            file.save()
            window.location.href = otherPath
        }
        if (e.key === "e" && !editorMode) {
            window.location.href = otherPath

        }
        if(e.key === "s" && (e.ctrlKey||e.metaKey) && editorMode){
            file.save()
        }
    }
</script>

<svelte:window on:keydown={onKeydown}/>

<div class="overflow-auto h-full shadow  p-4">
    <a href="{otherPath}" on:click={()=>setTimeout(refreshPath,0)} use:link>
        <i class="fas fa-sharp fa-edit"></i>

    </a>
    <Tree bind:items={$fileTree} bind:editorMode expanded={true} on:menuchange={menuChange}/>
</div>
