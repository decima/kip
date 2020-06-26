<template>
    <div class="hamburger-button"
         v-if="$store.getters.getNavBarCollapsed"
         @click="openNavBarDrawer">
        <div class="hamburger-line top-line"></div>
        <div class="hamburger-line bottom-line"></div>
    </div>
</template>

<script>
    export default {
        name : "HamburgerButton",
        methods : {
            openNavBarDrawer(){
                this.$store.commit("setNavBarDrawerOpened", true)
            }
        }
    }
</script>

<style scoped lang="less">

    .hamburger-button {
        cursor: pointer;
        margin-right:20px;

        &:hover {
            .top-line {
                transform : translateX(3px)
            }
            .bottom-line {
                transform : translateX(-2px)
            }
        }
    }

    .hamburger-line {
        height: 2px;
        background-color: @text-color;
        transition: transform .3s @ease-out-quint;

        + .hamburger-line {
            margin-top:6px;
        }
    }

    .top-line {
        width: 30px;
    }

    .bottom-line {
        width: 20px;
    }

</style>
