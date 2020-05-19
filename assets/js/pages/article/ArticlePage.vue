<template>
    <div class="article-page">
        <a-layout class="container-layout">

            <multipane @paneResize="resizeNavBar">
                <a-layout-sider :width="$store.getters.getNavBarWidth"
                                :style="{ overflow: 'auto', height: '100vh', position: 'fixed', left: 0 }">
                    <nav-bar />
                </a-layout-sider>

                <multipane-resizer class="pane-resizer"
                                   :style="{ marginLeft: $store.getters.getNavBarWidth, left : 0 }" />

                <a-layout :style="{ flexGrow : 1 }" class="main-layout">
                    <a-layout-content class="layout-content"
                                      :style="{ margin: '24px 16px 0', overflow: 'initial' }">

                        <div class="article-wrapper">
                            <article-header />
                            <router-view :key="$route.path" />
                        </div>

                    </a-layout-content>
                </a-layout>
            </multipane>

        </a-layout>
    </div>
</template>

<script>
    import ArticleHeader from "pages/article/header/ArticleHeader";
    import NavBar from "pages/article/navbar/NavBar";

    import { Multipane, MultipaneResizer } from 'vue-multipane';

    export default {
        name : "ArticlePage",
        components: {NavBar, ArticleHeader, Multipane, MultipaneResizer},
        methods : {
            resizeNavBar(pane, resizer, size){
                this.$store.commit("setNavBarWidth", size);
            }
        }
    }
</script>

<style scoped lang="less">

    @main-layout-border-radius: 60px;

    .main-layout {
        border-top-left-radius: @main-layout-border-radius;
        border-bottom-left-radius: @main-layout-border-radius;
    }

    .pane-resizer, .container-layout {
        background-color: @navbar-background-color;
    }

</style>
