import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    currentUser: {},
    loggedIn: false,
    mobileView: false,
  },
  // Methods to actually change the state
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
  // Async calls and data handelling
  actions: {
    // ? ----- NYZO TRANSATIONS -----
    async txSubmit({ commit }, formObj) {
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
    // ? ----- ACCOUNT -----
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
    // ! Unsure if this is a safety risk, was thinking of creating a cookie system auth
    loadUser({
      commit
    }) {
      let localUser = JSON.parse(localStorage.getItem('currentUser'))
      if (Object.entries(localUser).length != 0) {
        commit('SET_CURRENT_USER', JSON.parse(localStorage.getItem('currentUser')))
      }
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
    logoutUser({
      commit
    }) {
      commit("LOGOUT");
    },
    // This is for sending the email to the user to change the password
    async pwdRequest(email) {
      let response = await axios.post('https://nyaz.io/nya/pwdRequest.inc.php', email);
      return response.data['error'];
    },
    // This updates the server to change the password of the user
    async changePassword(info) {
      let response = await axios.post('https://nyaz.io/nya/changePassword.inc.php', info);
      console.log(response);
      return response.data['error'];
    },
    async changerUser({ commit }, data) {
      let response = await axios.post(
        "https://nyaz.io/nya/profileEdit.inc.php",
        formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).catch(e => {
        console.log(e);
      });
      let user = response.data;
      if (response.data['error'] != 'none') {
        return response.data['error'];
      } else {
        delete user['error'];
        commit('SET_CURRENT_USER', user);
        return response.data['error'];
      }
    },
    async createPost({ commit }, formObj) {
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
    async dataCall({commit}, formObj) {
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
  },
  modules: {}
})