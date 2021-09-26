<template>
  <v-alert
    :class="'pointerG' + !$vuetify.breakpoint.mobile ? 'ma-4' : ''"
    v-if="$store.state.alertOn"
    dismissible
    :type="$store.state.alertType"
    @click="$store.dispatch('offAlert')"
    >{{ $store.state.alertMsg }}</v-alert
  >
</template>