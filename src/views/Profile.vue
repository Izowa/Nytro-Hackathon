<template>
  <div>
    <v-card color="primary" width="800px" class="mx-auto pa-4">
      <v-card-text>
        <v-form>
          <v-row>
            <v-col>
              <div class="d-inline-flex">
                <img
                  src="@/assets/nya.png"
                  width="150px"
                  height="150px"
                  alt=""
                />
                <h2>@username</h2>
              </div>
              <h4>Bio</h4>
              <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam,
                illum aperiam assumenda blanditiis perferendis libero
                exercitationem eaque esse. Ipsam provident sunt, voluptatibus
                fuga omnis qui ea dicta laudantium dignissimos recusandae?
              </p>
              <h4>Top Posts</h4>
              <div class="d-inline-flex">
                <img
                  class="mr-2"
                  src="@/assets/meme.png"
                  width="150px"
                  height="150px"
                />
                <img
                  class="mr-2"
                  src="@/assets/meme.png"
                  width="150px"
                  height="150px"
                />
                <img src="@/assets/meme.png" width="150px" height="150px" />
              </div>
            </v-col>
            <v-col>
              <h3 class="mb-2">Nya Level</h3>
              <div class="d-inline-flex">
                <img src="@/assets/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-5 ml-5 pa-3 black--text">
                  None
                </p>
              </div>
              <h3 class="mb-2">Total Nya Gained</h3>
              <div class="d-inline-flex">
                <img src="@/assets/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-7 ml-5 pa-3 black--text">123</p>
              </div>
              <h3 class="mb-2">Highest Nya on a single post</h3>
              <div class="d-inline-flex">
                <img src="@/assets/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-7 ml-5 pa-3 black--text">123</p>
              </div>
              <router-link></router-link>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
    <h2>Will Display all Posts here</h2>
    <div v-for="post in posts" :key="post.postsID">
      <PostCard post="post" />
    </div>
  </div>
</template>

<script>
import $ from "jquery";
import PostCard from "@/components/PostCard.vue";
export default {
  components: { PostCard },
  metaInfo: {
    title: "Profile",
    "http-equiv": "Content-Security-Policy",
    content: "upgrade-insecure-requests",
  },
  data() {
    return {
      id: $route.params.id,
      fetchedPosts: [],
      start: 0,
      limit: 10,
      reachedMax: false,
    };
  },
  methods: {
    async fetchUser() {
      let response = await this.$store.dispatch("data/dataPost", {
        url: "fetchUser",
        data: {
          usersID: this.id,
        },
      });
      //console.log(response);
      if (response["reachedMax"] == true) {
        this.reachedMax = true;
      } else if (response["reachedMax"] == false) {
        this.start += this.limit;
        let posts = response["posts"];
        posts.forEach((element) => {
          this.fetchedPosts.push(element);
        });
      }
    },
    async fetchUserPosts() {
      if (this.reachedMax == false) {
        let response = await this.$store.dispatch("data/dataPost", {
          url: "fetchUserPosts",
          data: {
            start: this.start,
            limit: this.limit,
            usersID: this.id,
          },
        });
        //console.log(response);
        if (response["reachedMax"] == true) {
          this.reachedMax = true;
        } else if (response["reachedMax"] == false) {
          this.start += this.limit;
          let posts = response["posts"];
          posts.forEach((element) => {
            this.fetchedPosts.push(element);
          });
        }
      }
    },
    atBottomScroll() {
      let vm = this;
      $(window).on("scroll", function () {
        var scrollHeight = $(document).height();
        var scrollPosition = $(window).height() + $(window).scrollTop();
        if ((scrollHeight - scrollPosition) / scrollHeight === 0) {
          //console.log("abc");
          vm.fetchUserPosts();
        }
      });
    },
  },
  mounted() {
    console.log("Mounted");
    this.fetchUserPosts();
    this.atBottomScroll();
  },
};
</script>

<style>
</style>