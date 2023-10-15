<script>
    import TreeItem from "./TreeItem.svelte";

    export let items = [];
    export let expanded = false;
    export let editorMode=false
</script>
<ul>
    {#if expanded}
        {#each items as item }
            <li>
                <TreeItem bind:editorMode bind:item on:menuchange/>
            </li>
        {/each}
    {/if}
</ul>