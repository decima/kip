import {RouteName} from "../router/RouteName";
import { editLink } from "utils";

export default {
    install(Vue, options) {
        Vue.prototype.$routes = RouteName;

        Vue.prototype.$editLink = function(){
            return this.$readLink() + editLink
        };

        Vue.prototype.$readLink = function(){
            if(this.$route.name === this.$routes.EDIT_ARTICLE){
                return this.$route.path.substring(0, this.$route.path.lastIndexOf(editLink))
            }

            return this.$route.path;
        };

        Vue.prototype.$getArticleWebpath = function(){
            return this.$store.getters.getCurrentArticle?.file?.webpath;
        }
    }
}