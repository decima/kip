<template>
    <div>
        <vue-simplemde v-model="content" :config="config" />
    </div>
</template>

<script>
    import VueSimplemde from 'vue-simplemde'

    export default {
        name : "Editor",
        components: {VueSimplemde},
        data(){
            return {
                content : ""
            }
        },
        computed : {
            config(){
                return {
                    spellChecker : false
                }
            }
        },
        created(){
            this.content = JSON.parse(JSON.stringify(this.$store.getters.getArticleToEdit.file.markdownContent))
        }
    }
</script>

<style>
    @import '~simplemde/dist/simplemde.min.css';
</style>