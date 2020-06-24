<template>
    <div class="app-wrapper">
        <router-view v-if="$store.getters.getArticlesTree"/>
    </div>
</template>

<script>

    export default {
        name: 'App',
        async created() {
            this.$store.dispatch("loadArticlesTree")
        }
    }

</script>

<style scoped>

    .app-wrapper {
        height: 100%;
    }

</style>
