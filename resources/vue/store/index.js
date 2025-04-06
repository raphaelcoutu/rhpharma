import { createStore } from 'vuex'
import calendar from './modules/calendar'

const debug = process.env.NODE_ENV !== 'production';

const store = createStore({
    modules: {
        calendar,
    },
    strict: false,
})

export default store;
