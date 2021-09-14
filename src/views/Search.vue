<template>
  <v-container>
    <v-row>
      <v-col></v-col>
      <v-col>
        <h1>Don't worry, search will be coming soon!</h1>
        <div class="posts" v-for="post in fetchedPosts" :key="post.postsID">
          <PostCard :post="post" />
        </div>
      </v-col>
      <v-col><Account-Widget /></v-col>
    </v-row>
  </v-container>
</template>

<script>
import $ from "jquery";
import PostCard from "@/components/PostCard.vue";
import AccountWidget from "@/components/AccountWidget.vue";
export default {
  components: { PostCard, AccountWidget },
  metaInfo: {
    title: 'Search Result',
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
    async fetchSearch() {
      if (this.reachedMax == false) {
        let response = await this.$store.dispatch("dataCall", {
          url: "fetchSearch",
          data: {
            start: this.start,
            limit: this.limit,
            searchTerm: this.$route.params.searchTerm
          },
        });
        console.log(response);
        //console.log(response["reachedMax"]);
        if (response["reachedMax"] == true) {
          this.reachedMax = true;
        } else if (response["reachedMax"] == false) {
          this.start += this.limit;
          let posts = response["posts"];
          posts.forEach((element) => {
            this.fetchedPosts.push(element);
          });
          //this.fetchedPosts = posts;
          //console.log(this.fetchedPosts);
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
          vm.fetchSearch();
        }
      });
    },
  },
  mounted() {
    console.log("Mounted");
    this.fetchSearch();
    this.atBottomScroll();
  },
};
</script>
