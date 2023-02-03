<script>
    import {themeChange} from "theme-change";
    import {onMount} from "svelte";
    import {Link} from "svelte-navigator";

    let currentTheme = "light";
    let checked = false;
    onMount(() => {
        themeChange(false)
        currentTheme = document.querySelector("html").dataset.theme;
        if (currentTheme === "dark") {
            checked = true;
        }
    })


    let themes = ["light", "dark", "cupcake", "bumblebee", "emerald", "corporate", "synthwave", "retro", "cyberpunk", "valentine", "halloween", "garden", "forest", "aqua", "lofi", "pastel", "fantasy", "wireframe", "black", "luxury", "dracula", "cmyk", "autumn", "business", "acid", "lemonade", "night", "coffee", "winter"];

</script>
<div class="navbar bg-base-100">
    <div class="flex-1">
        <Link to="">
            <a class="btn btn-ghost normal-case text-xl" href="/">BOILERPLATE</a>
        </Link>
    </div>
    <div class="flex-none">
        <ul class="menu menu-horizontal p-0">
            <li>
                <Link to="health">health</Link>
            </li>
        </ul>
        <div class="flex gap-1">
            {#if checked}
                <span>🌚</span>
            {:else}
                <span>🌞️</span>
            {/if}
            <input type="checkbox" class="toggle" data-toggle-theme="dark,light" data-act-class="active"
                   bind:checked/>
        </div>
        <!-- ALTERNATIVE THEME SWITCHER (for more fun) -->
        <!--
        <div>
            <select data-choose-theme class="select">
                <option value="">Default</option>
                {#each themes as theme}
                    <option value={theme}>{theme}</option>

                {/each}
            </select>
        </div>
        -->
    </div>
</div>