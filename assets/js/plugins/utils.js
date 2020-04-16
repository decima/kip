import {RouteName} from "../router/RouteName";
import { editLink } from "utils";

export default {
    install(Vue, options) {
        Vue.prototype.$routes = RouteName;

        Vue.prototype.$editLink = function(){
            return this.$route.path + editLink
        }

        Vue.prototype.$readLink = function(){
            return this.$route.path.substring(0,this.$route.path.lastIndexOf(editLink))
        }
    }
}