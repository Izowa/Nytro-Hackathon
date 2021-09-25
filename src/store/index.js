import Vue from 'vue'
import Vuex from 'vuex'

import modules from './modules';

Vue.use(Vuex)

// ! - The central store file, links modules together and stores central data
const store = new Vuex.Store({
    state: {
        baseStorageURL: "https://www.nyaz.io/webStorage/nya/"
    },
    modules
})

for (const moduleName of Object.keys(modules)) {
    if (modules[moduleName].actions && modules[moduleName].actions.innit) {
        store.dispatch(`${moduleName}/innit`);
    }
}

export default store;