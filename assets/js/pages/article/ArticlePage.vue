<template>
    <div>
        <a-button type="primary">button</a-button>
    </div>
</template>

<script>
    export default {
        name : "ArticlePage",
        mounted(){
            console.log(this.$route)
        }
    }
</script>

<style scoped>

</style>