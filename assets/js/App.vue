<template>
    <div>
        <router-view/>
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