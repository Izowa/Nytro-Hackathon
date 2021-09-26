<template>
  <nav>
    <v-app-bar flat color="white darken-3">
      <v-row align="center">
        <v-col>
          <h1 class="primary--text">NYAZ</h1>
        </v-col>
        <v-col>
          <SearchBar />
        </v-col>
        <v-col>
          <v-menu>
            <template v-slot:activator="{ on }">
              <v-btn
                v-show="loggedIn"
                style="float: right"
                width="150px"
                v-on="on"
                text
                color="primary"
                >Account</v-btn
              >
            </template>
            <v-list v-show="loggedIn">
              <v-list-item
                v-for="link in links"
                :key="link.text"
                link
                :to="link.route"
              >
                {{ link.text }}
              </v-list-item>
              <v-list-item @click="$store.dispatch('auth/logoutUser')">
                Logout
              </v-list-item>
              <v-list-item>
                <v-list-item-content
                  ><v-list-item-title class="font-weight-bold">
                    {{
                      $vuetify.theme.dark ? "Dark Mode" : "Light Mode"
                    }}</v-list-item-title
                  >
                </v-list-item-content>
                <v-list-item-action
                  ><v-switch v-model="$vuetify.theme.dark" />
                </v-list-item-action>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-btn
            width="150px"
            v-show="!loggedIn"
            style="float: right"
            class="primary--text"
            text
            :to="{ name: 'Login' }"
            >Login</v-btn
          >
          <v-btn
            width="150px"
            v-show="!loggedIn"
            style="float: right"
            class="primary--text"
            text
            :to="{ name: 'SignUp' }"
            >SignUp</v-btn
          >
          <v-btn
            width="150px"
            v-show="loggedIn"
            style="float: right"
            class="primary--text"
            text
            :to="{ name: 'BuyNyaz' }"
            >Buy Nyaz</v-btn
          >
        </v-col>
      </v-row>
    </v-app-bar>
    <div class="pa-3">
      <v-row class="primary">
        <v-spacer />
        <v-btn width="25vw" text to="/feed/featured-posts"
          >Featured Posts</v-btn
        >
        <v-btn width="25vw" text to="/feed/top-posts">Top Posts</v-btn>
        <v-btn width="25vw" text to="/feed/">New Posts</v-btn>
        <v-spacer />
      </v-row>
    </div>
    <div class="pa-3" v-show="loggedIn">
      <v-row class="secondary">
        <v-spacer />
        <v-btn
          width="500px"
          class="mt-3"
          color="white primary--text"
          to="/create-new-post"
          >Create A Post</v-btn
        >
        <v-spacer />
      </v-row>
    </div>
  </nav>
</template>

<script>
import SearchBar from "@/components/SearchBar.vue";
import { mapState } from "vuex";
export default {
  components: { SearchBar },
  data() {
    return {
      links: [],
    };
  },
  computed: {
    ...mapState("auth", ["currentUser", "loggedIn"]),
  },
  mounted() {
    this.links = [
      { text: "My Account", route: { name: "Account" } },
      { text: "My Posts", route: { name: "MyPosts" } },
      {
        text: "Profile",
        route: {
          name: "Profile",
          params: { uID: this.currentUser.usersUid },
        },
      },
    ];
  },
  methods: {
    logoutCall() {
      this.$store.dispatch("auth/logoutUser");
    },
  },
};
</script>

<style>
</style>