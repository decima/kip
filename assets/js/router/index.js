import VueRouter from 'vue-router'
import Vue from 'vue'

Vue.use(VueRouter);

import routes from "./routes";

export default new VueRouter({
    mode: 'hash',
    routes: routes
});
