<template>
    <div class="article-page">
        <a-layout class="container-layout">
            <multipane @paneResize="resizeNavBar"
                       class="multipane">
                <a-layout-sider :width="$store.getters.getNavBarWidth"
                                v-model="navBarCollapsed"
                                :collapsedWidth="0"
                                breakpoint="lg"
                                collapsible
                                :trigger="null"
                                @breakpoint="onBreakpointChanged"
                                :style="{ overflow: 'auto', height: '100vh', position: 'fixed', left: 0 }">
                    <nav-bar v-if="!$store.getters.getNavBarCollapsed"/>
                </a-layout-sider>

                <a-drawer placement="left"
                          class="nav-bar-drawer"
                          :closable="false"
                          :visible="$store.getters.getNavBarDrawerOpened"
                          @close="onNavBarDrawerClose">
                    <nav-bar/>
                </a-drawer>

                <multipane-resizer class="pane-resizer"
                                   :style="{ marginLeft: $store.getters.getNavBarWidth }"
                                   v-if="!$store.getters.getNavBarCollapsed"/>

                <a-layout :style="{ flexGrow : 1 }"
                          class="main-layout"
                          :class="{ 'has-sider-collapsed' : $store.getters.getNavBarCollapsed }">
                    <a-layout-content class="layout-content"
                                      :style="{ margin: '24px 16px 0', overflow: 'initial' }">

                        <div class="article-wrapper">
                            <div class="article-top-wrapper">
                                <article-header/>
                            </div>

                            <router-view :key="$route.path"/>
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

    import {Multipane, MultipaneResizer} from 'vue-multipane';

    export default {
        name: "ArticlePage",
        components: {NavBar, ArticleHeader, Multipane, MultipaneResizer},
        computed: {
            navBarCollapsed: {
                get() {
                    return this.$store.getters.getNavBarCollapsed
                },
                set(value) {
                    this.$store.commit("setNavBarCollapsed", value);
                }
            },
        },
        methods: {
            resizeNavBar(pane, resizer, size) {
                const minNavBarSize = 240;
                const maxNavBarSize = 450;
                let sizeInNumber = parseInt(size, 10);

                // clamp the size of the navbar between min and max size
                sizeInNumber = Math.min(Math.max(sizeInNumber, minNavBarSize), maxNavBarSize);

                this.$store.commit("setNavBarWidth", `${sizeInNumber}px`);
            },
            onBreakpointChanged(collapsed) {
                this.$store.commit("setNavBarCollapsed", collapsed);

                if (!collapsed && this.$store.getters.getNavBarDrawerOpened) {
                    this.$store.commit("setNavBarDrawerOpened", false)
                }
            },
            onNavBarDrawerClose() {
                this.$store.commit("setNavBarDrawerOpened", false)
            }
        }
    }
</script>

<style scoped lang="less">

    @main-layout-border-radius: 60px;

    .multipane {
        width: 100%;
    }

    .article-page, .container-layout {
        min-height: 100vh;
        background-color: @navbar-background-color;
    }

    .main-layout {
        border-top-left-radius: @main-layout-border-radius;
        z-index: 1;
        transition: border-top-left-radius 1s @ease-in-out;

        &.has-sider-collapsed {
            border-top-left-radius: 0px;
        }
    }

    .layout-content {
        margin: 0 !important;
        width: 100%;
        padding: 32px 40px 40px 40px;
    }

    .pane-resizer {
        margin-top: @main-layout-border-radius;
        z-index: 999;
        height: calc(100% - @main-layout-border-radius) !important;
        min-width: 10px;

        &:hover {
            background-image: linear-gradient(to right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0) 30%,
            rgba(0, 0, 0, 0.1) 50%,
            rgba(255, 255, 255, 0) 50%,
            rgba(255, 255, 255, 0) 100%);
        }
    }

    .article-wrapper {
        height: 100%;
    }

    .article-top-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }


    @media screen and (min-width: 1750px){
        .layout-content {
            max-width: 1400px;
            margin: 0 auto !important;
        }
    }

</style>

<style lang="less">

    .nav-bar-drawer {
        .ant-drawer-wrapper-body {
            background-color: @navbar-background-color;
        }

        .ant-drawer-body {
            padding: 0;
        }
    }

    @media screen and (max-width: @screen-md) {
        .ant-layout {
            background-color: white !important;
            display: block !important;
            flex-direction: unset !important;

            .multipane {
                display: block;
            }
        }

        .article-page, .container-layout, .multipane, .main-layout, .layout-content {
            height: 100%;
        }

        .layout-content {
            padding: 20px 15px 40px 15px !important;
        }
    }

</style>
