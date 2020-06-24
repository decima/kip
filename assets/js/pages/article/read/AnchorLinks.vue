<template>
    <div class="anchor-wrapper">
        <a-anchor-link v-for="articleLink in articleTree"
                       :key="articleLink.id"
                       :href="`#${articleLink.id}`"
                       :title="articleLink.text"
                       @click.native.prevent.stop="scrollToElement">
            <anchor-links v-if="articleLink.children"
                          :article-tree="articleLink.children"/>
        </a-anchor-link>
    </div>
</template>

<script>
    export default {
        name: "AnchorLinks",
        props: {
            articleTree: {}
        },
        methods: {
            scrollToElement() {
                if (this.$store.getters.getTocDrawerOpened) {
                    this.$store.commit("setTocDrawerOpened", false)
                }
            }
        }
    }
</script>

<style scoped>

</style>
