import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

// ! - This file stores information related to server data manipulation
export default {
    namespaced: true,
    actions: {
        async createPost({
            commit
        }, formObj) {
            let url = "https://nyaz.io/nya/createNewPost.inc.php";
            let formData = new FormData();
            //console.table(formObj)
            for (let element in formObj['data']) {
                formData.append(element, formObj['data'][element]);
            }
            for (let element in formObj['images']) {
                formData.append(element, formObj['images'][element]);
            }
            let response = await axios.post(
                url,
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).catch(e => {
                console.log(e);
            });
            return response.data;
        },
        async deletePost({
            commit,
            state,
            rootState
        }, formObj) {
            let formData = new FormData();
            console.log(formObj);
            formData.append("postsID", formObj["postsID"])
            formData.append("usersID", rootState.auth.currentUser.usersID);
            formData.append("usersPwd", rootState.auth.currentUser.usersPwd);
            let response = await axios.post('https://nyaz.io/nya/deletePost.inc.php', formData).catch(e => {
                console.log(e);
            });
            return response.data;
        },
        async dataPost({
            commit
        }, formObj) {
            let url = "https://nyaz.io/nya/" + formObj['url'] + ".inc.php";
            let formData = new FormData();
            //console.table(formObj)
            for (let element in formObj['data']) {
                formData.append(element, formObj['data'][element]);
            }
            let response = await axios.post(
                url,
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).catch(e => {
                console.log(e);
            });
            return response.data;
        },
        async dataGet({
            commit
        }, url) {
            let fullUrl = "https://nyaz.io/nya/" + url + ".inc.php";

            let response = await axios.get(fullUrl, {
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