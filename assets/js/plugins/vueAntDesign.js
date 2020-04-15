//add all the components that we are actually using from the library
import { Button } from 'ant-design-vue';

export default {
    install(Vue, options) {
        Vue.use(Button);
    }
}