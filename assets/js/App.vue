<template>
    <div>
        <router-view v-if="$store.getters.getArticlesTree"/>
    </div>
</template>

<script>

    export default {
        name: 'App',
        async created(){
            this.$store.dispatch("loadArticlesTree")
        }
    }

</script>