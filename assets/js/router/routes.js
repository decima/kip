import {RouteName} from "./RouteName";

import ArticlePage from "pages/article/ArticlePage"
import ReadArticle from "pages/article/read/ReadArticle"
import EditArticle from "pages/article/edit/EditArticle"

import { editLink } from "utils";

/**
 * All the routes name must be configured in the ./RouteName.js file.
 * You can also use RouteName.js in components like this : `this.$routes.ARTICLE_PAGE`
 */

export default [
    {
        path: '*',
        component: ArticlePage,
        children: [
            {
                path: `*${ editLink }`,
                component: EditArticle,
                name: RouteName.EDIT_ARTICLE,
            },
            {
                path: '',
                component: ReadArticle,
                name: RouteName.READ_ARTICLE,
            },
        ]
    }
];
