<template>
  <v-container>
    <v-row>
      <v-col><Account-Widget v-if="$vuetify.breakpoint.mobile" /></v-col>
      <v-col>
        <div class="posts" v-for="post in fetchedPosts" :key="post.postsID">
          <PostCard :post="post" />
        </div>
      </v-col>
      <v-col><Account-Widget v-if="!$vuetify.breakpoint.mobile" /></v-col>
    </v-row>
  </v-container>
</template>

<script>
import $ from "jquery";
import PostCard from "@/components/PostCard.vue";
import AccountWidget from "@/components/AccountWidget.vue";
export default {
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
        if (this.reachedMax == false) {
        let response = await this.$store.dispatch("dataCall", {
          url: "fetchTop",
          data: {
            start: this.start,
            limit: this.limit,
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
