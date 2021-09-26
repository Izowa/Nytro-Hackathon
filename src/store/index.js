import Vue from 'vue'
import Vuex from 'vuex'

import modules from './modules';

Vue.use(Vuex)

// ! - The central store file, links modules together and stores central data
const store = new Vuex.Store({
    state: {
        baseStorageURL: "https://www.nyaz.io/webStorage/nya/",
        alertMsg: '',
        alertType: '',
        alertOn: false,
    },
    mutations: {
        SET_ALERT(state, alert) {
            state.alertMsg = alert["msg"];
            state.alertType = alert["type"];
            state.alertOn = true;
        },
        OFF_ALERT(state) {
            state.alertOn = false;
        }
    },
    actions: {
        alerts({
            commit
        }, alert) {
            commit('SET_ALERT', alert);
        },
        offAlert({
            commit
        }) {
            commit('OFF_ALERT');
        },
    },
    modules
})

for (const moduleName of Object.keys(modules)) {
    if (modules[moduleName].actions && modules[moduleName].actions.innit) {
        store.dispatch(`${moduleName}/innit`);
    }
}

export default store;