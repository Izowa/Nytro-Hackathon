import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

// ! - This file stores information related to the nyzo chain. Buying Nyaz and receiving wallet data.
export default {
    namespaced: true,
    actions: {
        async txSubmit({
            commit
        }, formObj) {
            let formData = new FormData();
            for (let element in formObj) {
                formData.append(element, formObj[element]);
            }
            let response = await axios.post('https://nyaz.io/nya/TXSubmit.inc.php', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).catch(e => {
                console.log(e);
            });
            return response.data;
        },
    },
}