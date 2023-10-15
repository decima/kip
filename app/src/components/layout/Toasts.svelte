<script>
    import {toasts} from "../../stores/toasts.js";
</script>
<div class="toast toast-end">
    {#each $toasts as toast}
        <div class="alert alert-{toast.type}">
            <span>{toast.message}</span>
        </div>
    {/each}

</div>