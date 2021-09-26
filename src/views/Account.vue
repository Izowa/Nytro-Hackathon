<template>
  <div class="tabs">
    <v-tabs color="primary" class="rounded-lg">
      <v-tab>Account Info</v-tab>
      <v-tab>Profile Info</v-tab>
      <v-tab>Nya Management</v-tab>
        <v-tab-item class="">
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
  metaInfo: {
    title: 'Account',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  mounted() {
    this.pwdCheckCall();
  },
  methods: {
    async pwdCheckCall() {
      let response = await this.$store.dispatch("auth/pwdCheck");
      console.log(response);
      if (response["error"] == "userNotFound") {
        this.$store.dispatch('alerts', {type: "error", msg: "We had trouble finding the user, make sure you are logged in, or login again."})
      } else if (response["error"] == "stmtFailed") {
        this.$store.dispatch('alerts', {type: "error", msg: "It seems something has failed on our end, try again or at a later time."})
      } else if (response["error"] == "noPostData") {
        this.$store.dispatch('alerts', {type: "error", msg: "There was an issue sending data, please try again or login again."})
      }
      if (response["correct"] == true) {
        console.log("Check Successful");
      } else if (response["correct"] == false) {
        alert("Please sign in to access this page");
        this.$router.push({ name: "Feed" });
      }
    },
  }
};
</script>

<style>
.tabs {
  margin: 0 15% 0 15%;
  width: 70%;
}
</style>