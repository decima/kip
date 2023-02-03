<script>
    import Navbar from "./components/layout/Navbar.svelte";
    import {Route, Router} from "svelte-navigator";
    import Health from "./components/pages/Health.svelte";
    import Home from "./components/pages/Home.svelte";
</script>
<Router>
    <Navbar/>

    <main class="mx-auto container">
        <Route path="" component={Home}/>
        <Route path="health" component={Health}/>
    </main>
</Router>