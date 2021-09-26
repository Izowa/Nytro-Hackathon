<template>
  <div>
    <v-skeleton-loader
      :loading="loading"
      class="mb-2"
      transition="scale-transition"
      height="300"
      type="article"
      boilerplate
    >
      <v-card
        class="mb-5 pa-2 pointerG topPostBox"
        width="100%"
        min-height="200px"
        color="primary"
      >
        <v-card-title
          style="word-break: break-word"
          @click="$router.push({ name: 'Post', params: { id: id } })"
        >
          <h2 class="font-weight-black">{{ title }}</h2>
          <v-spacer />
          <span class="font-weight-bold">@{{ usersUid }}</span>
        </v-card-title>
        <hr />
        <v-card-text
          @click="$router.push({ name: 'Post', params: { id: id } })"
        >
          <v-carousel v-if="oneImg == 2" class="px-3 cardCar" hide-delimiters>
            <v-carousel-item
              v-for="(item, i) in imagePath"
              :key="i"
              :src="baseStorageURL + 'postImgs/' + item.postsImgPath"
            />
          </v-carousel>
          <img
            class="pa-2 mt-2"
            v-if="oneImg == 1"
            :src="baseStorageURL + 'postImgs/' + imagePath[0]['postsImgPath']"
            width="100%"
          />
        </v-card-text>
        <v-card-text
          class="postText"
          v-show="desc != ''"
          @click="$router.push({ name: 'Post', params: { id: id } })"
        >
          <p>
            {{ desc }}
          </p>
        </v-card-text>
        <hr />
        <v-card-actions>
          <v-row>
            <v-col>
              <div class="d-inline-flex">
                <v-icon>mdi-comment</v-icon>
                <p class="ml-2 mt-3 black--text">None</p>
              </div>
            </v-col>
            <v-col>
              <div class="d-inline-flex">
                <img
                  class="smallPaw"
                  src="@/assets/images/paw.svg"
                  width="40"
                  height="40"
                />
                <p class="smallCounter white rounded-pill black--text">
                  {{ likes }}
                </p>
              </div>
            </v-col>
            <v-col>
              <div class="d-inline-flex">
                <img src="@/assets/images/nya.png" width="40" height="40" />
                <Upvote
                  :recPublicKey="recPublicKey"
                  :postsID="id"
                  :usersID="currentUser.usersID"
                />
              </div>
            </v-col>
          </v-row>
        </v-card-actions>
      </v-card>
    </v-skeleton-loader>
  </div>
</template>

<script>
import Upvote from "@/components/Upvote.vue";
import { mapState } from "vuex";
export default {
  components: { Upvote },
  props: ["post"],
  data() {
    return {
      id: 0,
      usersUid: "",
      title: "",
      desc: "",
      likes: 0,
      tags: undefined,
      nsfw: "",
      recPublicKey: "",
      imagePath: [],
      comments: [],
      oneImg: 0,
      loading: true,
    };
  },
  computed: {
    commentsCount() {
      return this.comments.length;
    },
    ...mapState(["baseStorageURL"]),
    ...mapState("auth", ["currentUser"]),
  },
  mounted() {
    this.id = this.post.postsID;
    this.usersUid = this.post.usersUid;
    this.title = this.post.postsTitle;
    this.desc = this.post.postsDesc;
    this.likes = this.post.postsLikes;
    this.tags = this.post.postsTags;
    this.nsfw = this.post.postsNSFW;
    this.recPublicKey = this.post.usersPublicKey;
    if (this.post != undefined || this.post != null) {
      this.loading = false;
    }
    //console.log(this.id);
    this.getImages();
  },
  methods: {
    async getImages() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchPostImages",
        data: { postsID: this.id },
      });
      if (response["error"] != "none") {
        //this.$store.dispatch('alerts', {type: "error", msg: "There was an error loading images."})
        console.log({ response });
      } else {
        delete response["error"];
        this.imagePath = response["images"];
        console.log(response["images"].length);
        if (response["images"].length > 1) {
          this.oneImg = 2;
        }
        if (response["images"].length == 1) {
          this.oneImg = 1;
        }
        if (
          response["images"].length == 0 ||
          this.imagePath == null ||
          this.imagePath == undefined
        ) {
          this.oneImg = 0;
        }
      }
    },
  },
};
</script>

<style>
hr {
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  border-color: rgba(0, 0, 0, 0.144);
}

.smallPaw {
  margin-right: 1rem;
}

.smallCounter {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  padding-right: 1rem;
  padding-left: 1rem;
}

.postText {
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.15);
}

.PostBorder {
  border-radius: 0.5rem !important;
}

.topPostBox {
  border-radius: 0.5rem !important;
}
</style>