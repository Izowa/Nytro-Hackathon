<template>
  <div>
    <v-card color="primary" width="800px" class="mx-auto pa-4">
      <v-card-text>
        <v-form>
          <v-row>
            <v-col>
              <div class="d-inline-flex">
                <v-avatar size="100" class="mb-4">
                  <img :src="baseStorageURL + 'pfps/' + user.usersPfp" />
                </v-avatar>
                <h2 class="ml-3 mt-8">@{{ user.usersUid }}</h2>
              </div>
              <h3>Bio</h3>
              <p>
                {{ user.usersDesc }}
              </p>
              <h4>Top Posts (Coming Soon)</h4>
              <div class="d-inline-flex">
                <v-img class="mr-2" width="150px" height="150px" />
                <v-img class="mr-2" width="150px" height="150px" />
                <v-img width="150px" height="150px" />
              </div>
            </v-col>
            <v-col>
              <h3 class="mb-2">Nya Level</h3>
              <div class="d-inline-flex">
                <img src="@/assets/images/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-5 ml-5 pa-3 black--text">
                  Coming Soon
                </p>
              </div>
              <h3 class="mb-2">Total Nya Gained</h3>
              <div class="d-inline-flex">
                <img src="@/assets/images/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-7 ml-5 pa-3 black--text">
                  Coming Soon
                </p>
              </div>
              <h3 class="mb-2">Highest Nya on a single post</h3>
              <div class="d-inline-flex">
                <img src="@/assets/images/paw.svg" width="50" height="50" />
                <p class="white rounded-pill mb-7 ml-5 pa-3 black--text">
                  Coming Soon
                </p>
              </div>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
import PostCard from "@/components/PostCard.vue";
import { mapState } from "vuex";
export default {
  components: { PostCard },
  metaInfo: {
    title: "Profile",
    "http-equiv": "Content-Security-Policy",
    content: "upgrade-insecure-requests",
  },
  data() {
    return {
      id: "",
      start: 0,
      limit: 10,
      reachedMax: false,
      user: {},
    };
  },
  computed: {
    ...mapState(["baseStorageURL"]),
  },
  mounted() {
    this.id = this.$route.params.uID;
    console.log(this.$route.params.uID);
    this.fetchUser();
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
      this.user = response["user"];
    },
  },
};
</script>

<style>
</style>