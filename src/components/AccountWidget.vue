<template>
  <v-card color="primary" class="pa-6 topAcountBox" :width="$vuetify.breakpoint.mobile ? '500px' : 'auto'">
      <v-card-text v-show="loggedIn">
          <h3 class="mb-2">Nyaz Balance</h3>
          <div class="d-inline-flex">
             <img src="@/assets/paw.svg" width="50" height="50" />
             <p class="white rounded-pill mb-5 ml-5 pa-3 black--text">Coming Soon</p>
          </div>
          <h3 class="mb-2">Nya Level</h3>
          <div class="d-inline-flex">
             <img src="@/assets/paw.svg" width="50" height="50" />
             <p class="white rounded-pill mb-5 ml-5 pa-3 black--text">Coming Soon</p>
          </div>
          <h3 class="mb-2">Total Nya Gained</h3>
          <div class="d-inline-flex">
             <img src="@/assets/paw.svg" width="50" height="50" />
             <p class="white rounded-pill mb-7 ml-5 pa-3 black--text">Coming Soon</p>
          </div>
      </v-card-text>
      <v-card-text v-show="!loggedIn">
         <div class="d-inline-flex">
            <h2 class="font-weight-bold mr-1 mb-3 white--text">Login to access</h2>
         </div>
         <ul class="mb-5">
            <li>Upload Posts</li>
            <li>Upvote</li>
            <li>Comment</li>
         </ul>
         <v-btn v-show="!loggedIn" to="/login">Sign me in!</v-btn>
      </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
   computed: {
      ...mapState('auth', ['loggedIn'])
   }
}
</script>

<style>
.topAcountBox{
   border-radius: 1rem !important;
}
</style>