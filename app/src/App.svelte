<script>
    import {Route, Router} from "svelte-navigator";
    import Health from "./components/pages/Health.svelte";
    import Home from "./components/pages/Home.svelte";
    import Editor from "./components/pages/Editor.svelte";
    import Sidebar from "./components/layout/Sidebar.svelte";
    import Toasts from "./components/layout/Toasts.svelte";
</script>
<Router primary={false}>

    <div class="flex bg-base-100 min-h-screen">
        <aside class="w-1/6 fixed bg-primary text-primary-content h-screen">
            <Sidebar/>
        </aside>
        <div class="w-1/6 h-full"></div>
        <main class="w-5/6">
            <Route path="_/edit/*filepath" component="{Editor}"/>
            <Route path="*filepath" component={Home}/>
            <Route path="health" component={Health} meta="{{name:'health'}}"/>
        </main>
</Router>

<Toasts/>