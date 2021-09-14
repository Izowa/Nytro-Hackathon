<template>
  <v-card width="600px" class="mx-auto pa-10 mt-5">
    <v-card-title>
      <h1>Request a new password</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field v-model="loginInfo.email" label="Username/Email" prepend-icon="mdi-account" />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn color="primary" @click="pwdRequestCall">Request Change</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  metaInfo: {
    title: 'Change Password',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      email: "",
    };
  },
  methods: {
    async pwdRequestCall() {
      let requestError = await this.$store.dispatch("pwdRequest", this.email);
      console.log(requestError);
      if (requestError == "none" || userError == undefined) {
        //alert("User Successfully Logged Up!");
        this.$router.push("/");
      } else if (requestError != "none") {
        alert('An error has occured, please try again');
      }
    },
  },
}
</script>

<style>
</style>