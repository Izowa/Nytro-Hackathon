<template>
  <div>
    <v-tabs color="red">
      <v-tab>Account Info</v-tab>
      <v-tab>Profile Info</v-tab>
      <v-tab>Nya Management</v-tab>
        <v-tab-item>
          <AccountDetails />
        </v-tab-item>
        <v-tab-item>
          <ProfileDetails />
        </v-tab-item>
        <v-tab-item>
          <ManageNya />
        </v-tab-item>
    </v-tabs>
  </div>
</template>

<script>
import AccountDetails from "@/components/settings/AccountDetails.vue";
import ProfileDetails from "@/components/settings/ProfileDetails.vue";
import ManageNya from "@/components/settings/ManageNya.vue";
export default {
  components: { AccountDetails, ProfileDetails, ManageNya },
};
</script>

<style>
</style>