<template>
  <v-card color="red" class="pa-4 mt-2">
    <v-card-title>
      <v-avatar size="85">
        <img :src="imagePath" />
      </v-avatar>
      <h1 class="ml-4 white--text">Profile</h1>
    </v-card-title>
    <v-card-text>
      <v-avatar v-show="fileSelected" size="85" class="mr-2 mb-2">
            <img :src="imageURL"/>
        </v-avatar>
      <v-file-input
        show-size
        truncate-length="50"
        label="Choose an image!"
        @change="onSelectedFile"
      />
      <v-textarea
        solo
        name="input-7-4"
        label="Bio"
        :placeholder="$store.state.currentUser.usersDesc"
      ></v-textarea>
      <v-btn @click="saveChanges">Save Changes</v-btn>
      <v-btn @click="getPosts" class="ml-2">Delete a Post</v-btn>
        
      <div
        v-show="showPosts"
        class="posts"
        v-for="post in fetchedPosts"
        :key="post.postsID"
      >
        <PostCard :post="post" />
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      showPosts: false,
      fetchedPosts: [],
      newDesc: "",
      image: null,
      imageURL: "",
      fileSelected: false
    };
  },
  methods: {
    async getPosts() {
      this.showPosts = true;
      let formData = new FormData();
      formData.append("usersID", this.$store.state.currentUser.usersID);
      let response = await axios.post(
        "https://nyaz.io/nya/fetchUserPosts.inc.php",
        formData
      ); // No URL
      if (response.data["error"] != "none") {
        console.log("Something went wrong");
      } else {
        let posts = response.data;
        delete posts["error"];
        this.fetchedPosts = posts;
      }
    },
    async saveChanges() {
      let formData = new FormData();
      formData.append();
      formData.append("usersDesc", this.$store.state.currentUser.usersDesc);
      let response = this.$store.dispatch('changeUser', formData)
      if (response.data["error"] != "none") {
        console.log("Something went wrong");
      } else {
        this.$router.push({name: 'Account'})
      }
    },
    async onSelectedFile(event) {
      this.fileSelected = true;
      this.tempUploadedImg = null;
      //console.log(event);
      //console.log(event[0]);
      const fileReader = new FileReader();
      fileReader.addEventListener("load", () => {
        this.imageURL = fileReader.result;
      });
      fileReader.readAsDataURL(event);
      this.image = event;
    },
  },
  computed: {
    imagePath() {
      return (
        "http://180.150.45.233/webStorage/nya/pfps/" +
        this.$store.state.currentUser.usersPfp
      );
    },
  },
};
</script>

<style>
</style>