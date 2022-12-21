import search from '@/store/modules/search'
import config from '@/store/modules/config'
import user from '@/store/modules/user'
import { createStore } from 'vuex'

export const store = createStore({
    modules: {
        config,
        search,
        user
    }
})
