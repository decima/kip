import Vue from 'vue';
import routerManager from "./router";
import store from './store';
import i18n from './translations';
import { Button } from 'ant-design-vue';
import App from './App.vue';


Vue.use(Button);
import 'style/index.less';

App.store = store;
App.router = routerManager;
App.i18n = i18n;

new Vue({
  el: '#app',
  template: '<App/>',
  components: { App }
});


