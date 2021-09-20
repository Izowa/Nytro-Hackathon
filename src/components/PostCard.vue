<template>
  <v-card class="mb-5 pa-2 pointerG topPostBox" width="100%" min-height="200px" color="primary" >
    <v-card-title style="word-break: break-word" @click="$router.push({name: 'Post', params: {id: id}})">
      <h2>{{title}}</h2>
      <v-spacer/>
      <span class="font-weight-light">@{{usersUid}}</span>
    </v-card-title>
    <hr/>
    <v-card-text @click="$router.push({name: 'Post', params: {id: id}})">
      <v-carousel v-if="oneImg == 2" class="px-3 cardCar" hide-delimiters>
      <v-carousel-item
        v-for="(item, i) in imagePath"
        :key="i"
        :src="$store.state.baseStorageURL + 'postImgs/' + item.postsImgPath"
      />
    </v-carousel>
    <img class="pa-2 mt-2" v-if="oneImg == 1" :src="$store.state.baseStorageURL + 'postImgs/' + imagePath[0]['postsImgPath']" width="100%">
    </v-card-text>
    <v-card-text class="postText" v-show="desc != ''" @click="$router.push({name: 'Post', params: {id: id}})">
      <p>
        {{desc}}
      </p>
    </v-card-text>
    <hr/>
    <v-card-actions>
      <v-row>
        <v-col>
          <div class="d-inline-flex">
            <v-icon >mdi-comment</v-icon>
             <p class="ml-2 mt-3 black--text">None</p>
          </div>
        </v-col>
        <v-col>
          <div class="d-inline-flex">
             <img class="smallPaw" src="@/assets/paw.svg" width="40" height="40" />
             <p class="smallCounter white rounded-pill black--text">{{likes}}</p>
          </div>
        </v-col>
        <v-col>
          <div class="d-inline-flex">
             <img src="@/assets/nya.png" width="40" height="40" />
             <Upvote :recPublicKey="recPublicKey" :postsID="id" :usersID="$store.state.currentUser.usersID"/>
          </div>
        </v-col>
      </v-row>
    </v-card-actions>
  </v-card>
</template>

<script>
import Upvote from '@/components/Upvote.vue';
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
    };
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
    //console.log(this.id);
    this.getImages();
  },
  methods: {
    async getImages() {
      let response = await this.$store.dispatch('dataCall', {url: 'fetchPostImages', data: {postsID: this.id}});
      if (response['error'] != 'none'){
        console.log({response})
        //console.log('Error loading images');
      } else {
        console.log({response});
        delete response['error'];
        this.imagePath = response["images"];
        console.log(response["images"].length);
        if (response["images"].length > 1){
          this.oneImg = 2;
        }
        if (response["images"].length == 1){
          this.oneImg = 1;
        }
        if (response["images"].length == 0 || this.imagePath == null || this.imagePath == undefined){
          this.oneImg = 0;
        }
      }
    }
  },
  computed: {
    commentsCount() {
      return this.comments.length;
    },
  },
};
</script>

<style>

hr{
  margin-top: 0.5rem;
  margin-bottom: 0.5rem;
  border-color: rgba(0, 0, 0, 0.144);
}

.smallPaw{
  margin-right: 1rem;
}

.smallCounter{
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
  padding-right: 1rem;
  padding-left: 1rem;
}

.postText{
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.15);
}

.PostBorder{
  border-radius: 1rem !important;
}

.topPostBox{
  border-radius: 1rem !important;
}

</style>