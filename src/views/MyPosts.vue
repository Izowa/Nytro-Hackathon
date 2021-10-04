<template>
  <div>
    <v-row>
      <v-col></v-col>
      <v-col cols="9">
        <h1 class="mt-2">Your Posts list</h1>
        <div v-show="!hasPosts">
          <h2>You currently have no posts created, you can create one here...</h2>
          <v-btn color="primary" class="white--text" :to="{name: 'CreateNewPost'}">Create New Post</v-btn>
        </div>
        <PostsListItem v-for="post in posts" :key="post.postsID" :post="post" @deletedPost="deletedPost()"/>
      </v-col>
      <v-col></v-col>
    </v-row>
  </div>
</template>

<script>
import PostsListItem from "@/components/PostsListItem.vue";
import { mapState } from 'vuex';
export default {
  metaInfo: {
      title: 'My Posts',
      'http-equiv': "Content-Security-Policy",
      content: "upgrade-insecure-requests"
  },
  emits: ["deletedPost"],
  components: { PostsListItem },
  data() {
    return {
      posts: [],
      hasPosts: true,
    };
  },
  computed: {
    ...mapState('auth', ['currentUser'])
  },
  mounted() {
    this.pwdCheckCall();
    this.getPosts();
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
        this.$store.dispatch('alerts', {type: "error", msg: "Please sign in to access this page."})
        this.$router.push({ name: "Feed" });
      }
    },
    deletedPost() {
      this.posts = [];
      this.getPosts();
    },
    async getPosts() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchUserPosts",
        data: { usersID: this.currentUser.usersID },
      });
      console.log(response);
      if (response["error"] == "none") {
        if (response["posts"] == "none"){
          this.hasPosts = false;
        } else {
          this.posts = response["posts"];
        }
      } else if (response["error"] != "none") {
        this.$store.dispatch('alerts', {type: "error", msg: "It seems an error has occurred, please try again."})
        console.log(response["error"]);
      }
    },
  },
};
</script>

<style>
</style>