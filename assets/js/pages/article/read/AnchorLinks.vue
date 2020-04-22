<template>
    <div class="anchor-wrapper">
        <a-anchor-link v-for="articleLink in articleTree"
                       :key="articleLink.id"
                       :href="`#${articleLink.id}`" :title="articleLink.text">
            <anchor-links v-if="articleLink.children" :article-tree="articleLink.children"/>
        </a-anchor-link>
    </div>
</template>

<script>
    export default {
        name: "AnchorLinks",
        props: {
            articleTree: {}
        }
    }
</script>

<style scoped>

</style>