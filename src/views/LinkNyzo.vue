<template>
  <v-container>
    <v-card>
      <v-form>
        <v-card-title><h2>Link Nyzo Wallet</h2></v-card-title>
        <v-card-text>
          <v-text-field v-model="keys.publicKey" label="Public Wallet Key" />
          <v-text-field v-model="keys.privateKey" label="Private Wallet Key" />
          <p>Policy: ...</p>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="setKeysCall">Link Wallet</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
export default {
  metaInfo: {
    title: 'Link Nyzo',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      keys: {
        publicKey: "",
        privatekey: "",
      },
    };
  },
  methods: {
    setKeysCall() {
      this.$store.dispatch("setKeys", this.keys);
    },
  },
};
</script>

<style>
</style>