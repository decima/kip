<script>
    import {serverUptime} from "../../stores/serverUptime.js";

    export let navigate;
    export let location;


</script>
<div>

    <h1 class="text-5xl font-bold">Health</h1>
    Server alive since
    <span class="badge">{$serverUptime.uptime}</span>
    seconds
    <a class="btn" on:click={()=>{serverUptime.refresh()}}>Fetch data from server</a>
</div>