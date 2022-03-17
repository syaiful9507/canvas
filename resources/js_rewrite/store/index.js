import search from '@/store/modules/search';
import settings from '@/store/modules/settings';
import { createStore } from 'vuex';

export const store = createStore({
    modules: {
        search,
        settings,
    },
});
