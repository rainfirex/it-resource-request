import VueRouter from 'vue-router';
import Auth from "../components/views/Auth";
import NotFound from '../components/views/NotFound';
import RequestInfoResource from "../components/views/RequestInfoResource";
import ShowInfoResource from "../components/views/ShowInfoResource";

export default new VueRouter({
    routes : [
        {
            path: '/', component: RequestInfoResource, name: 'main'
        },
        {
            path: '/resource-access', component: ShowInfoResource, name: 'info-resourse', meta:{
                requestAuth: true
            }
        },
        {
            path: '/request-info-resource', component: RequestInfoResource, name: 'request.info.resource'
        },
        {
            path:'/auth', component: Auth, name: 'auth', meta: {
                guest: true
            }
        },
        {
            path: '*', component: NotFound, name: 'not-found', meta: {
                requestAuth: false
            }
        },

    ], mode : 'history'
});
