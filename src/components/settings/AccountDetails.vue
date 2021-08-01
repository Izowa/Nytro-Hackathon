<template>
  <v-card color="red" class="pa-4 mt-2">
    <v-card-title><h1 class="white--text">Account Details</h1></v-card-title>
    <v-card-text>
      <p>Username: {{$store.state.currentUser.usersUid}}</p>
      <p>Email: {{$store.state.currentUser.usersEmail}}</p>
      <v-btn>Change Password</v-btn>
    </v-card-text>
  </v-card>
</template>

<script>
export default {

}
</script>

<style>

</style>