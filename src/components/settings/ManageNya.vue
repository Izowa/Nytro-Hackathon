<template>
  <v-card color="red" class="pa-4 mt-2">
    <v-card-title><h1 class="white--text">Manage Nya</h1></v-card-title>
    <v-card-text>
      <p>Wallet ID: {{$store.state.currentUser.usersUid}}</p>
      <p>Nya Amount: {{$store.state.currentUser.usersUid}}</p>
      <p>Email: {{$store.state.currentUser.usersEmail}}</p>
      <v-btn :to="{name: 'BuyNyaz'}">Top Up Nyas</v-btn>
    </v-card-text>
  </v-card>
</template>

<script>
export default {

}
</script>

<style>

</style>