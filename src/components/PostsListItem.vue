<template>
  <v-skeleton-loader
      :loading="loading"
      class="mb-2"
      transition="scale-transition"
      height="300"
      type="list-item-three-line"
      boilerplate
    >
    <div
      v-if="!$vuetify.breakpoint.mobile"
      class="grey darken-3 rounded-lg mx-1 my-4 pa-5 white--text"
    >
      <v-row>
        <v-col>
          <h2>{{ post.postsTitle }}</h2>
        </v-col>
        <v-col md="auto">
          <v-btn
            color="primary"
            @click="
              $router.push({ name: 'Post', params: { id: post.postsID } })
            "
            >View post</v-btn
          >
          <!--  Click event here   -->
          <v-icon
            class="white--text mx-3"
            >mdi-pencil</v-icon
          >
          <v-icon class="white--text mx-3" @click="sheet = !sheet"
            >mdi-delete</v-icon
          >
        </v-col>
      </v-row>
      <v-bottom-sheet v-model="sheet">
        <v-sheet class="text-center" height="400px">
          <v-btn class="mt-6" text color="primary" @click="sheet = !sheet">
            close
          </v-btn>
          <div class="py-3 mx-12">
            <h2 class="pa-2 primary--text">CAUTION</h2>
            <div class="d-inline-flex">
              <h3>
                You are about to delete this post, this cannot be done once you
                confirm below.
              </h3>
            </div>
          </div>
          <v-btn @click="confirmDeletePost" color="primary">Delete</v-btn>
        </v-sheet>
      </v-bottom-sheet>
    </div>




    <div
      v-if="$vuetify.breakpoint.mobile"
      class="grey darken-3 rounded-lg mx-1 my-4 pa-5 white--text"
    >
      <v-row>
        <v-col>
          <h2>{{ post.postsTitle }}</h2>
          <v-btn
            @click="
              $router.push({ name: 'Post', params: { id: post.postsID } })
            "
            >View post</v-btn
          >
          <br class="my-4"/>
          <v-icon
            class="white--text mx-3"
            @click="
              $router.push({ name: 'PostEdit', params: { id: post.postsID } })
            "
            >mdi-pencil</v-icon
          >
          <v-icon class="white--text mx-3" @click="sheet = !sheet"
            >mdi-delete</v-icon
          >
        </v-col>
      </v-row>
      <v-bottom-sheet v-model="sheet">
        <v-sheet class="text-center" height="400px">
          <v-btn class="mt-6" text color="primary" @click="sheet = !sheet">
            close
          </v-btn>
          <div class="py-3 mx-12">
            <h2 class="pa-2 primary--text">CAUTION</h2>
            <div class="d-inline-flex">
              <h3>
                You are about to delete this post, this cannot be done once you
                confirm below.
              </h3>
            </div>
          </div>
          <v-btn @click="confirmDeletePost" color="primary">Delete</v-btn>
        </v-sheet>
      </v-bottom-sheet>
    </div>
  </v-skeleton-loader>
</template>

<script>
export default {
  props: ["post"],
  data() {
    return {
      sheet: false,
      images: [],
      loading: true
    };
  },
  methods: {
    async confirmDeletePost() {
      console.log(this.post.postsID);
      let response = await this.$store.dispatch("data/deletePost", {
        postsID: this.post.postsID,
      });
      if (response["error"] != 'none') {
        console.log(response["error"]);
      }
      if(response["error"] == 'none') {
        this.$emit("deletedPost");
      } else if ((response["error"] == "noPostData")) {
        this.$store.dispatch('alerts', {type: "error", msg: "There was an error sending the request."})
      } else if ((response["error"] == "stmtFailed")) {
        this.$store.dispatch('alerts', {type: "error", msg: "Something has gone wrong on our end!"})
      } else if ((response["error"] == "userNotFound")) {
        this.$store.dispatch('alerts', {type: "error", msg: "There was an issue detecting the user."})
      } else if ((response["error"] == "noPostData")) {
        this.$store.dispatch('alerts', {type: "error", msg: "There was an error sending the request."})
      } else {
        this.$store.dispatch('alerts', {type: "error", msg: "An unknown error has occurred, please try again."})
      }
    },
    async getImages() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPostImages",
        data: { postsID: this.id },
      });
      if (response["error"] != 'none') {
        //console.log(response["error"]);
      }
      if(response["error"] == 'none') {
        this.$emit("deletedPost");
        delete response["error"];
        this.images = response["images"];
      } else if ((response["error"] == "stmtFailed")) {
        //this.$store.dispatch('alerts', {type: "error", msg: "Something has gone wrong on our end!"})
      } else if ((response["error"] == "postNotFound")) {
        //this.$store.dispatch('alerts', {type: "error", msg: "There was an issue finding the images."})
      } else {
        //this.$store.dispatch('alerts', {type: "error", msg: "An unknown error has occurred, please try again."})
      }
    },
  },
  mounted() {
    if (this.post != undefined || this.post != null) {
      this.getImages();
      this.loading = false;
    }
  },
};
</script>

<style>
.zForward {
  z-index: 5;
}
</style>