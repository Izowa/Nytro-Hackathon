<template>
  <v-container>
    <h1>404 Not Found</h1>
    <p>Oh no! It seems the page you were looking for was not here!</p>
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