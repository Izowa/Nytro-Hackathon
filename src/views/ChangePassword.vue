<template>
  <v-card width="600px" class="mx-auto pa-10 mt-5">
    <v-card-title>
      <h1>Change Password</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field
          v-model="info.email"
          label="Email"
          prepend-icon="mdi-account"
        />
        <v-text-field
          v-model="info.password"
          label="Password"
          prepend-icon="mdi-lock"
          :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
          :type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
        />
        <v-text-field
          v-model="info.pwdRepeat"
          label="Repeat Password"
          prepend-icon="mdi-lock"
          type="password"
        />
        <p>
          A strong password should include an uppercase letter, a special
          character (e.g. ! ? #) and be at least 6-15 characters long.
        </p>
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn color="primary" @click="changePasswordCall">Change Password</v-btn>
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
      info: {
        email: "",
        password: "",
        pwdRepeat: "",
      },
    };
  },
  methods: {
    async changePasswordCall() {
      let formData = new FormData();
      formData.append("email", this.info.email);
      formData.append("password", this.info.password);
      formData.append("pwdRepeat", this.info.pwdRepeat);
      let responseError = await this.$store.dispatch(
        "auth/changePassword",
        formData
      );
      console.log(responseError);
      if (responseError == "none" || responseError == undefined) {
        //alert("User Successfully Logged Up!");
        this.$router.push("/");
      } else if (responseError != "none") {
        alert("An error has occured, please try again");
      }
    },
    async authCheck() {
      let formData = new FormData();
      formData.append("auth", this.$route.params.id);
      let response = await axios.post(
        "https://nyaz.io/nya/pwdAuth.inc.php",
        formData
      );
      if (response.data["error"] != "none") {
        this.$router.push("/");
      }
    },
  },
  mounted() {
    // Hopefully this is on ok checking method, just checks DB if the cookie exists
    this.authCheck();
  },
};
</script>