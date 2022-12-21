import request from '@/utils/request'

const initialState = {
    id: null,
    name: null,
    email: null,
    username: null,
    summary: null,
    avatar: null,
    dark_mode: null,
    digest: null,
    locale: null,
    role: null,
    default_avatar: null,
    default_locale: null,
    created_at: null,
    updated_at: null,
    deleted_at: null
}

const state = { ...initialState }

const actions = {
    updateDigest(context, payload) {
        request
            .post(`/api/users/${state.id}`, {
                name: state.name,
                email: state.email,
                digest: payload
            })
            .then(({ data }) => {
                context.commit('UPDATE_DIGEST', data.user)
            })
    },

    updateLocale(context, payload) {
        request
            .post(`/api/users/${state.id}`, {
                name: state.user.name,
                email: state.user.email,
                locale: payload
            })
            .then(({ data }) => {
                context.commit('UPDATE_LOCALE', data)
            })
    },

    updateDarkMode(context, payload) {
        request
            .post(`/api/users/${state.id}`, {
                name: state.name,
                email: state.email,
                dark_mode: payload
            })
            .then(({ data }) => {
                context.commit('UPDATE_DARK_MODE', data.user)
            })
    }
}

const mutations = {
    UPDATE_DIGEST(state, user) {
        state.digest = user.digest
    },

    UPDATE_LOCALE(state, data) {
        state.i18n = data.i18n
        state.locale = data.user.locale
    },

    UPDATE_DARK_MODE(state, user) {
        state.dark_mode = user.dark_mode
    }
}

const getters = {
    isContributor(state) {
        return state.role === 1
    },

    isEditor(state) {
        return state.role === 2
    },

    isAdmin(state) {
        return state.role === 3
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}
