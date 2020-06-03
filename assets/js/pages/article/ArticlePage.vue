<template>
    <div class="article-page">
        <a-layout class="container-layout">

            <multipane @paneResize="resizeNavBar" class="multipane">
                <a-layout-sider :width="$store.getters.getNavBarWidth"
                                :style="{ overflow: 'auto', height: '100vh', position: 'fixed', left: 0 }">
                    <nav-bar />
                </a-layout-sider>

                <multipane-resizer class="pane-resizer"
                                   :style="{ marginLeft: $store.getters.getNavBarWidth }" />

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
                const minNavBarSize = 240;
                const maxNavBarSize = 450;
                let sizeInNumber = parseInt(size, 10);

                // clamp the size of the navbar between min and max size
                sizeInNumber = Math.min(Math.max(sizeInNumber, minNavBarSize), maxNavBarSize);

                this.$store.commit("setNavBarWidth", `${ sizeInNumber }px`);
            }
        }
    }
</script>

<style scoped lang="less">

    @main-layout-border-radius: 60px;

    .multipane {
        width : 100%;
    }

    .article-page, .container-layout {
        min-height: 100vh;
    }

    .main-layout {
        border-top-left-radius: @main-layout-border-radius;
        z-index: 1;
    }

    .layout-content {
        margin: 0 !important;
        padding: 32px 40px 40px  40px;
    }

    .container-layout {
        background-color: @navbar-background-color;
    }

    .pane-resizer {
        margin-top : @main-layout-border-radius;
        z-index: 999;
        height : calc(100% - @main-layout-border-radius) !important;
        min-width:10px;

        &:hover {
            background-image: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 30%, rgba(0,0,0,0.1) 50%, rgba(255,255,255,0) 50%, rgba(255,255,255,0) 100%);;
        }
    }

    .article-wrapper {
        height: 100%;
    }

</style>
