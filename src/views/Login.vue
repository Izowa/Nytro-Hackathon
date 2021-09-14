<template>
  <v-card width="600px" class="mx-auto pa-10 mt-5">
    <v-card-title>
      <h1>Login</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field
          v-model="loginInfo.email"
          label="Username/Email"
          prepend-icon="mdi-account"
        />
        <v-text-field
          v-model="loginInfo.password"
          label="Password"
          prepend-icon="mdi-lock"
          :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
          :type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
        />
      </v-form>
    </v-card-text>
    <v-card-actions>
      <v-btn color="primary" @click="loginUserCall">Login</v-btn>
      <v-btn to="/signup">Sign Up</v-btn>
      <v-spacer />
      <v-btn text>Forgot Password</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  metaInfo: {
    title: 'Login',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      loginInfo: {
        email: "",
        password: "",
      },
      showPassword: false,
    };
  },
  methods: {
    async loginUserCall() {
      let formData = new FormData();
      formData.append("email", this.loginInfo.email);
      formData.append("password", this.loginInfo.password);
      let userError = await this.$store.dispatch("loginUser", formData);
      console.log(userError);
      if (userError == "none" || userError == undefined) {
        //alert("User Successfully Logged Up!");
        this.$router.push({name: 'Feed'});
      } else if (userError == "stmtFailed") {
        alert("Something has gone wrong on our end!");
      } else if (userError == "enterAllFields") {
        alert("Please enter in all the information required");
      } else if (userError == "uidNoExist") {
        alert("That user seems to not exist");
      } else {
        alert("An unknown error has occured, please try again");
      }
    },
  },
};
</script>

<style>
</style>