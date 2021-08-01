<template>
  <nav>
    <v-app-bar flat color="white darken-3">
      <v-row align="center">
        <v-col>
          <h1 class="red--text"> NYAZ </h1>
        </v-col>
        <v-col>
          <SearchBar />
        </v-col>
        <v-col>
          <v-btn v-show="!$store.state.loggedIn" class="red--text" text :to="{name: 'Login'}">Login</v-btn>
          <v-btn v-show="!$store.state.loggedIn" class="red--text" text :to="{name: 'SignUp'}">SignUp</v-btn>
          <!---<v-btn v-show="$store.state.loggedIn" class="red--text" text :to="{name: 'Profile', params: {id: $store.state.currentUser.usersID}}">Profile</v-btn>--->
          <v-btn v-show="$store.state.loggedIn" class="red--text" text :to="{name: 'BuyNyaz'}">Buy Nyaz</v-btn>
          <v-btn v-show="$store.state.loggedIn" class="red--text" text @click="logoutCall">Logout</v-btn>
          <!---<v-btn v-show="$store.state.loggedIn" class="red--text" text :to="{name: 'Account'}"><v-icon>mdi-cog</v-icon></v-btn>--->
        </v-col>
      </v-row>
    </v-app-bar>
    <div class="pa-3">
      <v-row class="red">
        <v-spacer />
        <v-btn text to="/feed/featured-posts">Featured Posts</v-btn>
        <v-btn text to="/feed/top-posts">Top Posts</v-btn>
        <v-btn text to="/feed/">New Posts</v-btn>
        <v-spacer />
      </v-row>
    </div>
    <div class="pa-3" v-show="$store.state.loggedIn">
      <v-row class="darkgrey">
        <v-spacer />
        <v-btn width="500px" class="mt-3" color="white red--text" to="/create-new-post">Create A Post</v-btn>
        <v-spacer />
      </v-row>
    </div>
    
  </nav>
</template>

<script>
import SearchBar from "@/components/SearchBar.vue";
export default {
  components: { SearchBar },
  methods: {
    logoutCall() {
      this.$store.dispatch("logoutUser");
    },
  },
};
</script>

<style>
</style>