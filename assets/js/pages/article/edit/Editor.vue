<template>
    <div class="editor-wrapper">
        <vue-simplemde :value="$store.getters.getCurrentArticle.file.markdownContent"
                       @input="handleInput"
                       :configs="configs"
                       ref="markdownEditor"/>
    </div>
</template>

<script>
    import VueSimplemde from 'vue-simplemde'

    export default {
        name: "Editor",
        components: {VueSimplemde},
        computed: {
            configs() {
                return {
                    spellChecker: false,
                    toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "table"]
                }
            }
        },
        methods : {
            handleInput(value){
                this.$store.commit("setCurrentArticleMarkdownContent", value)
            }
        }
    }
</script>

<style scoped>

    .editor-wrapper {
        width : 100%;
    }

</style>

<style>
    @import '~simplemde/dist/simplemde.min.css';
</style>