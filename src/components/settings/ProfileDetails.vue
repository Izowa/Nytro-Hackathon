<template>
  <div>
    <v-card color="accent" class="pa-4" flat>
      <v-card-text>
        <h1 class="my-3 mb-6">Profile Picture</h1>
        <div class="d-inline-flex">
          <v-avatar size="100" class="mb-4">
            <img :src="baseStorageURL + 'pfps/' + currentUser.usersPfp" />
          </v-avatar>
          <v-avatar v-show="tempUploadedImg != ''" size="100" class="mb-4 mx-6">
            <img :src="tempUploadedImg" />
          </v-avatar>
        </div>
        <br />
        <v-btn
          v-show="!pfpChange"
          color="primary"
          class="white--text mt-3"
          @click="pfpChange = !pfpChange"
          >Change Profile Picture</v-btn
        >
        <v-row v-show="pfpChange">
          <v-col cols="2">
            <v-btn block color="secondary" class="white--text mt-3" @click="changePfp"
              >Save Changes</v-btn
            >
          </v-col>
          <v-col cols="1">
            <v-btn
              color="primary"
              class="white--text mt-3"
              @click="pfpChange = !pfpChange"
              >Cancel</v-btn
            >
          </v-col>
          <v-col>
            <v-file-input
              show-size
              label="Update Profile Picture here!"
              @change="onSelectedFile"
            />
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
    <v-card class="mt-3" color="accent" flat>
      <v-card-text>
        <h1 class="my-3 mb-6">User Information</h1>
        <v-row>
          <v-col>
            <v-text-field solo disabled value="Profile Description:" />
          </v-col>
          <v-col>
            <v-text-field
              solo
              v-model="newInfo.newDesc"
              :disabled="!userChange"
            />
          </v-col>
        </v-row>
        <v-btn
          v-show="!userChange"
          color="secondary"
          width="150px"
          @click="userChange = !userChange"
          >Edit</v-btn
        >
        <v-row v-show="userChange">
          <v-col cols="2">
            <v-btn color="secondary" class="white--text" @click="editUserCall()"
              >Save Changes</v-btn
            >
          </v-col>
          <v-col>
            <v-btn
              color="primary"
              class="white--text ml-5"
              @click="userChange = !userChange"
              >Cancel</v-btn
            >
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import { mapState } from "vuex";
export default {
  data() {
    return {
      newInfo: {
        newDesc: null,
      },
      image: null,
      userChange: false,
      pfpChange: false,
      tempUploadedImg: '',
    };
  },
  computed: {
    ...mapState("auth", ["currentUser"]),
    ...mapState(["baseStorageURL"]),
    imagePath() {
      return (
        "http://180.150.45.233/webStorage/nya/pfps/" + this.currentUser.usersPfp
      );
    },
  },
  mounted() {
    this.newInfo.newDesc = this.currentUser.usersDesc
  },
  methods: {
    onSelectedFile(event) {
      this.tempUploadedImg = "";
      this.image = event;
      const fileReader = new FileReader();
      fileReader.addEventListener("load", () => {
        this.tempUploadedImg = fileReader.result;
      });
      fileReader.readAsDataURL(event);
      console.log(this.image);
    },
    async editUserCall() {
      let response = await this.$store.dispatch("auth/editUserProfile", this.newInfo);
      console.log({ response });
      if (response["error"] == "none") {
        this.$store.dispatch('alerts', {type: "success", msg: "Changes successfully made!"})
      } else if (response["error"] != "none") {
        this.$store.dispatch('alerts', {type: "error", msg: "An error has occurred, please try again."})
        console.log(response["error"]);
      }
      this.userChange = false;
    },
    async changePfp() {
      let response = await this.$store.dispatch("auth/changePfp", {
        usersID: this.currentUser.usersID,
        image: this.image,
      });
      console.log({ response });
      if (response["error"] == "none") {
        this.$store.dispatch('alerts', {type: "success", msg: "Changes successfully made!"})
      } else if (response["error"] != "none") {
        this.$store.dispatch('alerts', {type: "error", msg: "An error has occurred, please try again."})
        console.log(response["error"]);
      }
      this.imagePath = null;
      this.tempUploadedImg = '';
    },
  },
};
</script>

<style>
</style>