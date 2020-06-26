<template>
    <div class="actions">
        <template v-if="isMouseOvering || visible">
            <a-dropdown :trigger="['click']" v-model="visible" placement="bottomCenter">
                <fa icon="plus"/>
                <a-menu slot="overlay">
                    <a-menu-item>
                        <a @click="openModalArticleName">{{ $t('navBar.newArticle') }}</a>
                    </a-menu-item>
                    <a-menu-item v-if="article.dataRef.isFolder && !article.dataRef.hasReadme">
                        <a @click="createNewArticle('README')">{{ $t('navBar.addReadme') }}</a>
                    </a-menu-item>
                </a-menu>
            </a-dropdown>
        </template>

        <a-modal :visible="modalArticleNameOpened"
                 :title="$t('navBar.createANewArticle')"
                 @ok="createNewArticle()"
                 @cancel="modalArticleNameOpened = false"
                 :closable="false"
                 :destroyOnClose="true"
                 :afterClose="() => newArticleName = ''">
            <a-input :addon-after="fileExtension"
                     autoFocus
                     :placeholder="$t('navBar.articleName')"
                     v-model="newArticleName"
                     @pressEnter="createNewArticle()"/>
        </a-modal>
    </div>
</template>

<script>

    import {editLink} from "utils";

    export default {
        name: "ArticleTreeItemActions",
        props: {
            isMouseOvering: {},
            article: {}
        },
        data() {
            return {
                visible: false,
                modalArticleNameOpened: false,
                newArticleName: "",
                fileExtension: ".md"
            }
        },
        methods: {
            openModalArticleName() {
                this.visible = false;
                this.modalArticleNameOpened = true
            },
            async createNewArticle(articleName) {
                const newArticleName = articleName || this.newArticleName;

                if(newArticleName !== ""){
                    const fromArticlePath = this.article.dataRef.path;
                    let parentFolderPath = "";

                    if (this.article.dataRef.isFolder) {
                        parentFolderPath = `/${fromArticlePath}/`;
                    } else {
                        let indexOfParentFolderPath = fromArticlePath.lastIndexOf("/");
                        if (indexOfParentFolderPath === -1) {
                            parentFolderPath = "/";
                        } else {
                            parentFolderPath = `/${fromArticlePath.substring(0, indexOfParentFolderPath)}/`;
                        }
                    }

                    let newArticlePath = parentFolderPath + newArticleName + this.fileExtension;
                    this.$router.push({path: newArticlePath + editLink});

                    this.visible = false;
                    this.modalArticleNameOpened = false;

                    // Saving the empty file
                    await this.$store.dispatch("saveArticle", {
                        webpath : newArticlePath,
                        content : ""
                    });

                    //we reload the articles tree (menu) to have it with the newly created file
                    this.$store.dispatch("loadArticlesTree");
                }
            }
        }
    }
</script>

<style scoped lang="less">

    .actions {
        position: absolute;
        top:0px;
        right: 0px;
        padding-right: 10px;
        padding-left:20px;
    }

</style>

<style lang="less">

    .ant-tree-node-content-wrapper:hover .actions {
        background-image: linear-gradient(to right, #d1ddde00 0%, #d1ddde 30%);
    }

    .ant-tree-node-selected .actions {
        background-image: linear-gradient(to right, #c5d0d100 0%, #c5d0d1 30%);
    }

</style>
