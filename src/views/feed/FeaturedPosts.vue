<template>
  <div>
    <v-row>
      <v-col><Account-Widget v-if="$vuetify.breakpoint.mobile" /></v-col>
      <v-col>
        <div class="posts" v-for="post in fetchedPosts" :key="post.postsID">
          <PostCard :post="post" />
        </div>
      </v-col>
      <v-col v-if="!$vuetify.breakpoint.mobile"><Account-Widget /></v-col>
    </v-row>
  </div>
</template>

<script>
import $ from "jquery";
import PostCard from "@/components/PostCard.vue";
import AccountWidget from "@/components/AccountWidget.vue";
export default {
  components: { PostCard, AccountWidget },
  metaInfo: {
    title: 'Featured Posts',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  data() {
    return {
      fetchedPosts: [],
      start: 0,
      limit: 10,
      reachedMax: false,
    };
  },
  methods: {
    async fetchFeatured() {
      if (this.reachedMax == false) {
        let response = await this.$store.dispatch("data/dataPost", {
          url: "fetchFeatured",
          data: {
            start: this.start,
            limit: this.limit,
          },
        });
        //console.log(response);
        //console.log(response["reachedMax"]);
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
          vm.fetchFeatured();
        }
      });
    },
  },
  mounted() {
    console.log("Mounted");
    this.fetchFeatured();
    this.atBottomScroll();
  },
};
</script>
