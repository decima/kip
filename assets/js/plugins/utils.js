import {RouteName} from "../router/RouteName";

export default {
    install(Vue, options) {
        Vue.prototype.$routes = RouteName;
    }
}