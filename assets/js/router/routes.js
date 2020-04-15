import { RouteName } from "./RouteName";

import ArticlePage from "pages/article/ArticlePage"

/**
 * All the routes name must be configured in the ./RouteName.js file.
 * You can also use RouteName.js in components like this : `this.$routes.ARTICLE_PAGE`
 */

export default [
    {
        path: '/',
        component: ArticlePage,
        name: RouteName.ARTICLE_PAGE,
    },
    {
        path: '*/edit',
        component: ArticlePage,
        name:"edit",
    },
];
