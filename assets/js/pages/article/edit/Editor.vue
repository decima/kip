<template>
    <div class="editor-wrapper">
        <tui-editor class="editor"
                    ref="toastuiEditor"
                    :options="options"
                    :initialValue="initialValue"
                    previewStyle="vertical"
                    @change="onEditorChange" />

    </div>
</template>

<script>
    import 'codemirror/lib/codemirror.css';
    import '@toast-ui/editor/dist/toastui-editor.css';

    import {Editor as TuiEditor} from '@toast-ui/vue-editor';

    export default {
        name: "Editor",
        components: {TuiEditor},
        data(){
            return {
                initialValue : this.$store.getters.getCurrentArticle.file.markdownContent
            }
        },
        computed: {
            options() {
                return {
                    hideModeSwitch: true
                }
            }
        },
        methods: {
            onEditorChange() {
                const content = this.$refs.toastuiEditor.invoke('getMarkdown');
                this.$store.commit("setCurrentArticleMarkdownContent", content)
            },
            areThereAnyChangesNotSaved() {
                return this.initialValue !== this.$store.getters.getCurrentArticle.file.markdownContent
            }
        },
        mounted(){
            this.$store.subscribeAction((action, state) => {
                if(action.type === "saveArticle"){
                    this.initialValue = action.payload.content;
                }
            })
        }
    }
</script>

<style scoped>

    .editor-wrapper {
        width: 100%;
    }

    .editor, .editor-wrapper {
        height: 100% !important;
    }

</style>
