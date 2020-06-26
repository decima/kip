//add all the components that we are actually using from the library
import {
    Button,
    Tree,
    Spin,
    Tag,
    notification,
    Select,
    Anchor,
    Row,
    Col,
    Layout,
    Modal,
    Tooltip,
    Drawer,
    Popconfirm,
    Menu,
    Dropdown,
    Input
} from 'ant-design-vue';

import Fa from "components/Fa";

Spin.setDefaultIndicator({
    indicator: {
        render() {
            return <a-icon class='anticon-loading' type='loading' spin />
        },
    }
});

export default {
    install(Vue, options) {
        Vue.use(Button);
        Vue.use(Tree);
        Vue.use(Tag);
        Vue.use(Select);
        Vue.use(Anchor);
        Vue.use(Row);
        Vue.use(Col);
        Vue.use(Layout);
        Vue.use(Tooltip);
        Vue.use(Drawer);
        Vue.use(Popconfirm);
        Vue.use(Dropdown);
        Vue.use(Menu);
        Vue.use(Input);
        Vue.use(Modal);

        Vue.component("fa", Fa);

        Vue.component("a-spin", Spin);

        notification.config({
            placement: 'bottomRight',
            bottom: '50px',
        });

        Vue.prototype.$notification = notification;
        Vue.prototype.$confirm = Modal.confirm;
    }
}
