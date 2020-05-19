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

                            <transition name="subroute" mode="out-in">
                                <router-view :key="$route.path" />
                            </transition>
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

    .multipane {
        width : 100%;
    }

    .article-page, .container-layout {
        min-height: 100vh;
    }

    .main-layout {
        border-top-left-radius: @main-layout-border-radius;
        border-bottom-left-radius: @main-layout-border-radius;
        box-shadow: -10px 0px 80px rgba(0, 0, 0, 0.03);
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
        z-index: 2;
        height : calc(100% - @main-layout-border-radius) !important;
    }

    .subroute-enter-active, .subroute-leave-active {
        transition: transform .3s @ease-out-quint, opacity .3s;
        transform-origin: top;
    }
    .subroute-enter, .subroute-leave-to {
        transform: scale(0.97);
        opacity: 0;
    }
</style>
