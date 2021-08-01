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
import axios from "axios";
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
    async fetchNew() {
      if (this.reachedMax == false) {
        let formData = new FormData();
        formData.append('start', this.start);
        formData.append('limit', this.limit);
        let response = await axios.post("http://180.150.45.233/php-files/nya/fetchNew.inc.php", formData);
        console.log(response);
        //console.log(response.data["reachedMax"]);
        if (response.data["reachedMax"] == true) {
          this.reachedMax = true;
        } else if (response.data["reachedMax"] == false) {
          this.start += this.limit;
          let posts = response.data["posts"];
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
          vm.fetchNew();
        }
      });
    },
  },
  mounted() {
    console.log("Mounted");
    this.fetchNew();
    this.atBottomScroll();
  },
};
</script>
