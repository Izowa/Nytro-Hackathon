<template>
  <v-card color="primary" class="pa-4">
    <v-card-title><h1 class="white--text">Manage Nya</h1></v-card-title>
    <v-card-text>
      <v-row>
          <v-col>
            <v-text-field solo disabled value="Wallet Public key:" />
            <v-text-field solo disabled value="Nya Amount:" />
            <!-- Nyzo exchange <v-text-field solo disabled value="Current Exchange rate:" /> -->
          </v-col>
          <v-col>
            <v-text-field
              solo
              disabled
              :value="currentUser.usersPublicKey"
            />
            <v-text-field
              solo
              disabled
              value="Coming soon"
            />
          </v-col>
        </v-row>
      <v-btn :to="{name: 'BuyNyaz'}">Top Up Nyas</v-btn>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapState } from 'vuex'
export default {
  computed: {
    ...mapState('auth', ["currentUser"])
  }
}
</script>

<style>

</style>