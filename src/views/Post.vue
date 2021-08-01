<template>
  <v-container>
    <v-row>
      <v-col></v-col>
      <v-col>
        <v-card width="600px" class="pa-3">
          <v-card-title>
            <h2>{{ postD.postsTitle }}</h2>
            <v-spacer />
            <span class="font-weight-light">@{{ postD.usersUid }}</span>
          </v-card-title>
          <v-chip class="mr-1" v-for="tag in tags" :key="tag.tagsID">
            {{ tag.tagsValue }}
          </v-chip>
          <v-card-text>
            <div v-for="image in imagePath" :key="image.postsImgID">
              <p></p>
              <img
                width="100%"
                height="auto"
                :src="
                  'http://180.150.45.233/webStorage/nya/postImgs/' +
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
              <p class="mt-3 mr-2">Comments</p>
              <Upvote :recPublicKey="postD.usersPublicKey" :postsID="postD.postsID" :usersID="postD.usersID"/>
            </div>
            <v-card v-show="$store.state.loggedIn">
              <v-card-title>Write a comment</v-card-title>
              <v-card-text>
                <v-text-field
                  v-model="newComment"
                  prepend-icon="mdi-pencil"
                  placeholder="Write something awesome"
                />
                <v-btn @click="createCommentCall">Post</v-btn>
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
import Upvote from "@/components/Upvote.vue"
export default {
  components: {
    AccountWidget,
    Comment,
    Upvote
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
  mounted() {
    this.getPost();
    this.getImages();
    this.getComments();
    this.getTags();
  },
  methods: {
    async createCommentCall() {
      if (this.$store.state.loggedIn == true) {
        let response = await this.$store.dispatch("dataCall", {
          url: "createComment",
          data: {
            postsID: this.$route.params.id,
            usersID: this.$store.state.currentUser.usersID,
            comment: this.newComment
          },
        });
        if (response["error"] != "none") {
          console.log({ response });
          console.log("Error loading data");
        } else {
          console.log({ response });
          this.getComments();
        }
      } else {
        alert('Please sign in');
      }
    },
    async getPost() {
      let response = await this.$store.dispatch("dataCall", {
        url: "fetchPost",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        //console.log({ response });
        //console.log("Error loading data");
      } else {
        //console.log({ response });
        delete response["error"];
        this.postD = response[0];
      }
    },
    async getImages() {
      let response = await this.$store.dispatch("dataCall", {
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
      let response = await this.$store.dispatch("dataCall", {
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
      let response = await this.$store.dispatch("dataCall", {
        url: "fetchPostTags",
        data: { postsID: this.$route.params.id },
      });
      if (response["error"] != "none") {
        console.log({ response });
        console.log("Error loading tags");
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