<template>
    <div class="article-options" v-if="$store.getters.getCurrentArticle">
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
</template>

<script>
    import SaveArticleAction from "pages/article/header/SaveArticleAction";
    import DeleteArticleAction from "pages/article/header/DeleteArticleAction";

    export default {
        name : "ArticleActions",
        components: {DeleteArticleAction, SaveArticleAction},
        computed : {
            slidesLink() {
                return Router.url('knowledge_slides', {webpath: this.$getArticleWebpath()})
            }
        }
    }
</script>

<style scoped>

    .article-options {
        display:flex;
        flex: 0 0 auto;
    }

</style>

<style>

    .article-options .ant-btn i {
        margin-left: 0;
    }

</style>
