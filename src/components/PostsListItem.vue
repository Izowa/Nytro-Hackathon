<template>
  <div>
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
          <h2>{{ post.postsName }}</h2>
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
  </div>
</template>

<script>
export default {
  props: ["post"],
  data() {
    return {
      sheet: false,
      images: [],
    };
  },
  methods: {
    async confirmDeletePost() {
      console.log(this.post.postsID);
      let response = await this.$store.dispatch("data/deletePost", {
        postsID: this.post.postsID,
      });
      console.log(response);
      if (response["error"] != "none") {
        alert("An error has occurred");
      } else if (response["error"] == "none") {
        this.$emit("deletedPost");
      }
    },
    async getImages() {

    },
  },
  mounted() {
    if (this.post != {}) {
      this.getImages();
    }
  },
};
</script>

<style>
.zForward {
  z-index: 5;
}
</style>