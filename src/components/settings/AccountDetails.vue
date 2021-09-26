<template>
  <v-card color="primary" class="pa-4">
    <v-card-title>
      <h1 class="white--text">Account Details</h1>
    </v-card-title>
    <v-card-text>
      <h3 class="grey--text">Editing coming soon</h3>
      <p>Username: {{currentUser.usersUid}}</p>
      <p>Email: {{currentUser.usersEmail}}</p>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
  computed: {
    ...mapState('auth', ["currentUser"])
  },
}
</script>

<style>

</style>