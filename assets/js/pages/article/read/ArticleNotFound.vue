<template>
    <div>
        <p v-html="$t('read.notFound', { name : $route.path })"></p>
        <router-link :to="{ path : `${$route.path}/edit` }">
            <a-button type="primary">{{ $t("read.createIt") }}<fa icon="plus" /></a-button>
        </router-link>
    </div>
</template>

<script>
    export default {
        name : "ArticleNotFound"
    }
</script>

<style scoped>

</style>