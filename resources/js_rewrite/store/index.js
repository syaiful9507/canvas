import search from './modules/search';
import settings from './modules/settings';
import { createStore } from 'vuex';

export const store = createStore({
    modules: {
        search,
        settings,
    },
});
