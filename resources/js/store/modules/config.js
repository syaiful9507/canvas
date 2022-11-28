import request from '@/utils/request'
import {
  setDarkTheme,
  setLightTheme,
  prefersDarkColorScheme,
} from '@/utils/theme'

const initialState = {
  assetsUpToDate: window.Canvas.assetsUpToDate,
  i18n: window.Canvas.translations,
  languageCodes: window.Canvas.languageCodes,
  maxUpload: window.Canvas.maxUpload,
  path: window.Canvas.path,
  timezone: window.Canvas.timezone,
  unsplash: window.Canvas.unsplash,
  user: window.Canvas.user,
  version: window.Canvas.version,
  roles: window.Canvas.roles,
}

const state = { ...initialState }

const actions = {
  updateDigest(context, payload) {
    request
      .post(`/api/users/${state.user.id}`, {
        name: state.user.name,
        email: state.user.email,
        digest: payload,
      })
      .then(({ data }) => {
        context.commit('UPDATE_DIGEST', data.user)
      })
  },

  updateLocale(context, payload) {
    request
      .post(`/api/users/${state.user.id}`, {
        name: state.user.name,
        email: state.user.email,
        locale: payload,
      })
      .then(({ data }) => {
        context.commit('UPDATE_LOCALE', data)
      })
  },

  updateAppearance(context, payload) {
    request
      .post(`/api/users/${state.user.id}`, {
        name: state.user.name,
        email: state.user.email,
        dark_mode: payload,
      })
      .then(({ data }) => {
        context.commit('UPDATE_APPEARANCE', data.user)

        if (
          state.user.dark_mode === true ||
          (state.user.dark_mode === null && prefersDarkColorScheme())
        ) {
          setDarkTheme()
        } else {
          setLightTheme()
        }
      })
  },

  setUser(context, user) {
    context.commit('SET_USER', user)
  },
}

const mutations = {
  UPDATE_DIGEST(state, user) {
    state.user.digest = user.digest
  },

  UPDATE_LOCALE(state, data) {
    state.i18n = data.i18n
    state.user.locale = data.user.locale
  },

  UPDATE_APPEARANCE(state, user) {
    state.user.dark_mode = user.dark_mode
  },

  SET_USER(state, user) {
    state.user = user
  },
}

const getters = {
  trans(state) {
    return JSON.parse(state.i18n)
  },

  locale(state) {
    return state.user.locale || state.user.default_locale
  },

  getLocaleDisplayName: () => (locale) => {
    let code = require('../../data/lang.json')[locale]

    return code.nativeName
  },

  getAppearanceName(state) {
    switch (state.user.dark_mode) {
      case true:
        return JSON.parse(state.i18n).dark
      case false:
        return JSON.parse(state.i18n).light
      default:
        return JSON.parse(state.i18n).system
    }
  },

  isLightAppearance(state) {
    return state.user.dark_mode === false
  },

  isDarkAppearance(state) {
    return state.user.dark_mode === true
  },

  isSystemAppearance(state) {
    return state.user.dark_mode === null
  },

  isContributor(state) {
    return state.user.role === 1
  },

  isEditor(state) {
    return state.user.role === 2
  },

  isAdmin(state) {
    return state.user.role === 3
  },
}

export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
}
