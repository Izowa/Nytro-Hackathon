<template>
  <div>
    <v-row>
      <v-col><Account-Widget v-if="$vuetify.breakpoint.mdAndDown" /></v-col>
      <v-col>
        <div class="posts" v-for="post in fetchedPosts" :key="post.postsID">
          <PostCard :post="post" />
        </div>
      </v-col>
      <v-col v-if="!$vuetify.breakpoint.mdAndDown"><Account-Widget /></v-col>
    </v-row>
  </div>
</template>

<script>
import $ from "jquery";
import PostCard from "@/components/PostCard.vue";
import AccountWidget from "@/components/AccountWidget.vue";
export default {
  metaInfo: {
    title: 'Top Posts',
    'http-equiv': "Content-Security-Policy",
    content: "upgrade-insecure-requests"
  },
  components: { PostCard, AccountWidget },
  data() {
    return {
      fetchedPosts: [],
      start: 0,
      limit: 10,
      reachedMax: false,
    };
  },
  methods: {
    async fetchTop() {
      if (this.reachedMax == false) {
        let response = await this.$store.dispatch("data/dataPost", {
          url: "fetchTop",
          data: {
            start: this.start,
            limit: this.limit,
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
          vm.fetchTop();
        }
      });
    },
  },
  mounted() {
    console.log("Mounted");
    this.fetchTop();
    this.atBottomScroll();
  },
};
</script>

<style scoped>

</style>
