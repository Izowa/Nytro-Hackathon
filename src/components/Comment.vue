<template>
  <v-card color="secondary" class="my-2 pa-2">
    <v-card-subtitle> @{{comment.usersUid}} </v-card-subtitle>
    <v-card-text>
      {{comment.commentValue}}
    </v-card-text>
  </v-card>
</template>

<script>
export default {
    props:['comment'],
};
</script>

<style>
</style>