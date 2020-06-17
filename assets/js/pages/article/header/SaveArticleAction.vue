<template>
    <a-button type="link"
              @click="saveArticle"
              :loading="loading"
              v-hotkey="keymap">{{ $t("read.save") }}</a-button>
</template>

<script>

    import saveArticleMixin from "mixins/saveArticleMixin";

    export default {
        name : "SaveArticleAction",
        mixins : [saveArticleMixin],
        data(){
            return {
                loading : false
            }
        },
        computed : {
            keymap(){
                return {
                    'meta+s' : (e) => this.saveArticleFromKeyboardShortcut(e),
                    'ctrl+s' : (e) => this.saveArticleFromKeyboardShortcut(e)
                }
            }
        },
        methods : {
            saveArticleFromKeyboardShortcut(e){
                e.preventDefault();
                this.saveArticle()
            }
        }
    }
</script>

<style scoped>

</style>
