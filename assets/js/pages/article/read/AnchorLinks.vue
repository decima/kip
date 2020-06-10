<template>
    <div class="anchor-wrapper">
        <a-anchor-link v-for="articleLink in articleTree"
                       :key="articleLink.id"
                       :href="`#${articleLink.id}`"
                       :title="articleLink.text"
                       @click.native.stop.prevent="scrollTo(`#${articleLink.id}`)">
            <anchor-links v-if="articleLink.children" :article-tree="articleLink.children"/>
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
            scrollTo(href) {
                const element = document.querySelector(href);
                if(element){
                    const top = element.offsetTop;

                    window.scrollTo(0, top);
                }
            }
        }
    }
</script>

<style scoped>

</style>
