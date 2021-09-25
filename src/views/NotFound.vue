<template>
  <v-container>
    <v-card color="secondary" flat>
      <h1 class="primary--text">404 Not Found</h1>
      <h3>Oh no! It seems the page you were looking for was not here!</h3>
      <v-img src="@/assets/notfound.png"></v-img>
    </v-card>
  </v-container>
</template>

<script>
export default {
  metaInfo: {
      title: 'Not Found',
      'http-equiv': "Content-Security-Policy",
      content: "upgrade-insecure-requests"
    },
}
</script>

<style>

</style>