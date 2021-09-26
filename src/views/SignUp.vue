<template>
  <v-card width="700px" class="mx-auto pa-10 mt-5">
    <v-card-title>
      <h1>Sign Up</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field
          :rules="[rules.required]"
          v-model="registerInfo.username"
          label="Username"
          prepend-icon="mdi-account"
          
        />
        <v-text-field
          v-model="registerInfo.email"
          label="Email"
          prepend-icon="mdi-email"
        />
        <v-text-field
          :rules="[rules.required, rules.min]"
          v-model="registerInfo.pwd"
          label="Password"
          prepend-icon="mdi-lock"
          :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
          :type="showPassword ? 'text' : 'password'"
          @click:append="showPassword = !showPassword"
        />
        <v-text-field
          :rules="[rules.required, rules.min]"
          v-model="registerInfo.pwdRepeat"
          label="Repeat Password"
          prepend-icon="mdi-lock"
          type="password"
        />
        <p>
          A strong password should include an uppercase letter, a special
          character (e.g. ! ? #) and be at least 6-15 characters long.
        </p>
        <p>
          Please remember your password, the ability to change password will be coming soon.
        </p>
        <br>
        <v-checkbox v-model="registerInfo.tos" label="By ticking you agree to the Lost Night Studios Privacy Policy" />
        <p>You can find the Privacy Policy <a href="https://www.nyaz.io/terms-of-service.pdf">Here</a></p>
        <v-checkbox v-model="registerInfo.privacy" label="By ticking you agree to the Nyaz Terms of Service" />
        <p>You can find the Terms of Service <a href="https://www.nyaz.io/privacy-policy.pdf">Here</a></p>
      </v-form>
    </v-card-text>
    <v-card-title><h2>Link Nyzo Wallet</h2></v-card-title>
    <v-card-text>
      <v-text-field
        v-model="registerInfo.publicKey"
        label="Public Wallet Key"
      />
      <p>
        Policy: We <strong>DO NOT</strong> store your private key, this is stored locally on your
        machine
      </p>
    </v-card-text>
    <v-card-actions>
      <v-btn color="primary" @click="registerUserCall">Register</v-btn>
      <v-btn to="/login">Login</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  metaInfo: {
    title: 'Sign Up',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      registerInfo: {
        username: "",
        email: "",
        pwd: "",
        pwdRepeat: "",
        publicKey: "",
        tos: false,
        privacy: false,
      },
      error: true,
      showPassword: false,
      rules: {
          required: value => !!value || 'Required.',
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => (`The email and password you entered don't match`),
        },
    };
  },
  methods: {
    async registerUserCall() {
      console.log("Handle Submit");
      if (this.registerInfo.pwd != this.registerInfo.pwdRepeat) {
        this.$store.dispatch('alerts', {type: "warning", msg: "Passwords do not match."})
      } else if (this.registerInfo.tos != true) {
        this.$store.dispatch('alerts', {type: "warning", msg: "Please agree to the Terms of Service."})
      } else if (this.registerInfo.privacy != true) {
        this.$store.dispatch('alerts', {type: "warning", msg: "Please agree to the Privacy Policy."})
      } else {
        let response = await this.$store.dispatch(
          "auth/registerUser",
          this.registerInfo
        );
        console.log(response.data);
        if (response['usersID'] > 0){
          this.$router.push({name: 'Feed'});
        } else {
          this.$store.dispatch('alerts', {type: "warning", msg: "An unknown error has occurred, please try again later."})
        }
      }
    },
    checkPassword() {
      var decimal =
        /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{6,15}$/;
      if (this.registerInfo.pwd.match(decimal)) {
        this.error = true;
      } else {
        this.error = false;
      }
    },
  },
};
</script>

<style>
</style>