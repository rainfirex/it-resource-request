export default {
    state: {
        navMain: [
            {path: '/request-info-resource', title: 'Заявка на подключение к И.Р.', auth: 'both', ico: 'fa-desktop'},
        ],
        navAuth: [
            {path: '/logout', title: 'Выход', auth: true},
            {path: '/auth', title: 'Авторизация', auth: false, ico: 'fa-user'},
        ],
        navTicket: [
        ],
        navHandler: [
            {path: '/resource-access', title: 'Архив заявок.', auth: true, is_handler: true, ico:'fa-sign-language'}
        ],
    },

    getters: {
        getMainNavs(state) {
            return state.navMain;
        },

        getAuthNavs(state) {
            return state.navAuth;
        },

        getTicketNavs(state) {
            return state.navTicket;
        },

        getHandlerNavs(state) {
            return state.navHandler;
        }
    },

    mutations: {
        setMainNav(state, payload) {
            state.nav.push(payload);
        }
    },

    actions: {
        setMainNav({ commit }, payload) {
            commit('setMainNav', payload)
        }
    }
}
