import Vue from 'vue';
import routerManager from "./router";
import store from './store';
import i18n from './translations';
import App from './App.vue';
import vueAntDesign from './plugins/vueAntDesign';
import utils from './plugins/utils';
import VueHotkey from 'v-hotkey';
//importing style
import 'style/index.less';

//using plugins
Vue.use(vueAntDesign);
Vue.use(utils);
Vue.use(VueHotkey);

App.store = store;
App.router = routerManager;
App.i18n = i18n;

new Vue({
    el: '#app',
    template: '<App/>',
    components: {App},
    data: {
        SETTINGS: SETTINGS
    }
});


