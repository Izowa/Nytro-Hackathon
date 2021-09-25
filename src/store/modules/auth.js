import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

// ! - This file stores information related to the user, login and account changes
export default {
    namespaced: true,
    state: {
        currentUser: {},
        loggedIn: false,
    },
    mutations: {
        SET_CURRENT_USER(state, user) {
            if (user != '{}') {
                //console.log('SET_CURRENT_USER');
                state.currentUser = user;
                state.loggedIn = true;
                localStorage.setItem('currentUser', JSON.stringify(user));
            }
        },
        LOGOUT(state) {
            state.currentUser = {};
            state.loggedIn = false;
            localStorage.setItem('currentUser', JSON.stringify({}));
        }
    },
    actions: {
        innit({
            commit
        }) {
            try {
                let localUser = JSON.parse(localStorage.getItem('currentUser'))
                if (Object.entries(localUser).length != 0) {
                    commit('SET_CURRENT_USER', JSON.parse(localStorage.getItem('currentUser')))
                }
            } catch (error) {
                console.log("New Viewer Maybe");
                console.log(error);
            }

        },
        async pwdCheck({
            commit,
            state
        }, postsID) {
            let formData = new FormData();
            formData.append("usersID", state.currentUser.usersID);
            formData.append("pwd", state.currentUser.usersPwd);
            let response = await axios.post('https://nyaz.io/nya/pwdCheck.inc.php', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).catch(e => {
                console.log(e);
            });
            return response.data;
        },
        async ownerCheck({
            commit,
            state
        }, postsID) {
            let formData = new FormData();
            formData.append("usersID", state.currentUser.usersID);
            formData.append("postsID", postsID);
            let response = await axios.post('https://nyaz.io/nya/ownerCheck.inc.php', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).catch(e => {
                console.log(e);
            });
            return response.data;
        },
        async registerUser({
            commit
        }, formObj) {
            let formData = new FormData();
            for (let element in formObj) {
                formData.append(element, formObj[element]);
            }
            let res = await axios.post('https://nyaz.io/nya/registerUser.inc.php', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).catch(e => {
                console.log(e);
            });
            console.log(res);
            if (res.data['error'] != 'none') {
                return res.data;
            } else {
                let user = res.data;
                delete user['error'];
                commit('SET_CURRENT_USER', user);
                return res.data;
            }
        },
        async loginUser({
            commit
        }, loginInfo) {
            let res = await axios.post('https://nyaz.io/nya/loginUser.inc.php', loginInfo).catch(e => {
                console.log(e);
            });
            console.log(res);
            let user = res.data;
            if (res.data['error'] != 'none') {
                return res.data['error'];
            } else {
                delete user['error'];
                commit('SET_CURRENT_USER', user);
                return res.data['error'];
            }
        },
        logoutUser({
            commit
        }) {
            commit("LOGOUT");
            this.$router.push({
                name: 'Feed'
            });
        },
    },
}