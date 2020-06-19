<template>
    <div v-if="$store.getters.getCurrentArticle" class="article-header">
        <div class="article-path">{{ articleWebPath }}</div>

        <div class="article-options">
            <save-article-action v-if="$route.name === $routes.EDIT_ARTICLE"/>

            <a-button-group>
                <a-button :type="$route.name === $routes.READ_ARTICLE ? 'primary' : 'default'">
                    <a-tooltip>
                        <template slot="title">{{ $t("read.readTooltip")}}</template>
                        <router-link :to="{ path: $readLink() }">
                            <fa icon="eye"/>
                        </router-link>
                    </a-tooltip>
                </a-button>

                <a-button :type="$route.name === $routes.EDIT_ARTICLE ? 'primary' : 'default'">
                    <a-tooltip>
                        <template slot="title">{{ $t("edit.editTooltip")}}</template>
                        <router-link :to="{ path: $editLink() }">
                            <fa icon="pencil"/>
                        </router-link>
                    </a-tooltip>
                </a-button>

                <a-button>
                    <a-tooltip>
                        <template slot="title">{{ $t("presentation.presentationTooltip")}}</template>
                        <a :href="slidesLink" target="_blank">
                            <fa icon="presentation"/>
                        </a>
                    </a-tooltip>
                </a-button>
            </a-button-group>

            <delete-article-action/>
        </div>
    </div>
</template>

<script>
    import DeleteArticleAction from "./DeleteArticleAction";
    import SaveArticleAction from "pages/article/header/SaveArticleAction";

    export default {
        name: "ArticleHeader",
        components: {SaveArticleAction, DeleteArticleAction},
        computed: {
            slidesLink() {
                return Router.url('knowledge_slides', {webpath: this.$getArticleWebpath()})
            },
            articleWebPath(){
                if(this.$getArticleWebpath() === "/"){
                    return this.$t("navBar.home")
                }
                return this.$getArticleWebpath();
            }
        }
    }
</script>

<style scoped>

    .article-path {
        color: #CED4DC;
        font-size: 12px;
    }

    .article-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

</style>

<style>

    .article-options .ant-btn i {
        margin-left: 0;
    }

</style>
