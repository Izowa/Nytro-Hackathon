<template>
  <v-container>
    <v-row>
      <v-col></v-col>
      <v-col>
        <v-card width="900px" class="pa-3">
          <v-card-title style="word-break: break-word">
            <h2>{{ postD.postsTitle }}</h2>
            <v-spacer v-show="!$vuetify.breakpoint.mobile"/>
            <span
              class="font-weight-light pointerG"
              @click="$router.push({ name: 'Profile', params: postD.usersUid })"
              >@{{ postD.usersUid }}</span
            >
          </v-card-title>
          <div class="ml-3" v-if="tags.length > 0">
            <v-chip class="mr-1" v-for="tag in tags" :key="tag.tagID">
              {{ tag.tagsValue }}
            </v-chip>
          </div>
          <v-card-text v-if="imagePath.length > 0">
            <div v-for="image in imagePath" :key="image.postsImgID">
              <p></p>
              <img
                width="100%"
                height="auto"
                :src="
                  baseStorageURL + 'postImgs/' +
                  image.postsImgPath
                "
              />
            </div>
          </v-card-text>
          <v-card-text>
            <p>
              {{ postD.postsDesc }}
            </p>
            <div class="d-inline-flex">
              <v-icon>mdi-comment</v-icon>
              <p class="mt-3 mr-2 ml-1">Comments</p>
              <Upvote
                class="mt-1"
                :recPublicKey="postD.usersPublicKey"
                :postsID="postD.postsID"
                :usersID="postD.usersID"
              />
            </div>
            <v-card elevation="5" v-show="loggedIn">
              <v-card-title>Write a comment</v-card-title>
              <v-card-text>
                <v-text-field
                  v-model="newComment"
                  prepend-icon="mdi-pencil"
                  placeholder="Write something awesome"
                />
                <v-btn width="150px" color="accent" @click="createCommentCall">Post</v-btn>
              </v-card-text>
            </v-card>
            <div v-for="comment in comments" :key="comment.id">
              <Comment :comment="comment" />
            </div>
          </v-card-text>
        </v-card>
      </v-col>
      <v-col class="mx-auto">
        <AccountWidget />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import AccountWidget from "@/components/AccountWidget.vue";
import Comment from "@/components/Comment.vue";
import Upvote from "@/components/Upvote.vue";
import { mapState } from 'vuex';
export default {
  components: {
    AccountWidget,
    Comment,
    Upvote,
  },
  metaInfo: {
    title: "Post",
    "http-equiv": "Content-Security-Policy",
    content: "upgrade-insecure-requests",
  },
  data() {
    return {
      postD: {},
      imagePath: [],
      tags: [],
      comments: [],
      newComment: "",
    };
  },
  computed: {
    ...mapState(['baseStorageURL']),
    ...mapState('auth', ['currentUser', 'loggedIn'])
  },
  mounted() {
    this.getPost();
    this.getImages();
    this.getComments();
    this.getTags();
  },
  methods: {
    async createCommentCall() {
      if (this.loggedIn == true) {
        let response = await this.$store.dispatch("data/dataPost", {
          url: "createComment",
          data: {
            postsID: this.$route.params.id,
            usersID: this.currentUser.usersID,
            comment: this.newComment,
          },
        });
        if (response["error"] != "none") {
          console.log({ response });
          this.$store.dispatch('alerts', {type: "error", msg: "There was an issue creating the comment, please try again."})
        } else {
          console.log({ response });
          this.getComments();
        }
      } else {
        this.$store.dispatch('alerts', {type: "error", msg: "Please sign in to access this feature."})
      }
    },
    async getPost() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPost",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        this.$store.dispatch('alerts', {type: "error", msg: "An error has occurred, please try again."})
        console.log({ response });
      } else {
        //console.log({ response });
        delete response["error"];
        this.postD = response[0];
      }
    },
    async getImages() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPostImages",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        //console.log({ response });
        //console.log("Error loading images");
      } else {
        //console.log({ response });
        delete response["error"];
        this.imagePath = response;
      }
    },
    async getComments() {
      this.comments = [];
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPostComments",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        //console.log({ response });
        //console.log("Error loading comments");
      } else {
        //console.log({ response });
        delete response["error"];
        this.comments = response["comments"];
      }
    },
    async getTags() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPostTags",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        //console.log({ response });
        //console.log("Error loading tags");
      } else {
        console.log({ response });
        delete response["error"];
        this.tags = response["tags"];
      }
    },
  },
};
</script>

<style>
</style>