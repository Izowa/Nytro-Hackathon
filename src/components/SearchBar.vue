<template>
  <v-text-field
    v-model="searchTerm"
    solo
    class="mt-7 rounded-pill"
    label="Search (Coming Soon)"
    append-icon="mdi-magnify"
    @click:append="search"
    color="white"
  ></v-text-field>
</template>

<script>
export default {
  data() {
    return {
      searchTerm: "",
    };
  },
  methods: {
    search() {
      this.$router.push({
        name: "Search",
        params: { searchTerm: this.searchTerm },
      });
    },
  },
};
</script>

<style>
</style>