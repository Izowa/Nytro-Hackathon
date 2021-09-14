<template>
  <v-card color="red" width="800px" class="mx-auto pa-4">
    <v-card-title>
      <h1>Create a post</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-row>
          <v-col>
            <v-text-field v-model="postInfo.title" label="Title" />
            <v-textarea
              v-model="postInfo.description"
              outlined
              label="Description"
            ></v-textarea>
            <v-checkbox v-model="postInfo.nsfw" label="NSFW" />
          </v-col>
          <v-col>
            <v-file-input
              show-size
              multiple
              truncate-length="50"
              label="Choose an image!"
              @change="onSelectedFile"
            />
            <v-text-field placeholder="Press Enter to finsh a tag" v-model="tempTag" @keyup="addTag" label="Tags" />
            <div
              v-for="tag in postInfo.tags"
              :key="tag"
              class="pill"
              @click="removeTag(tag)"
            >
              <v-icon>mdi-close</v-icon>{{ tag }}
            </div>
          </v-col>
        </v-row>
        <v-btn color="white" class="black--text" @click="newPostCall"
          >Create post!</v-btn
        >
      </v-form>
    </v-card-text>
    <div class="d-inline-flex" v-for="image in tempUploadedImg" :key="image">
      <v-card class="mr-2 mb-2">
        <v-card-text>
          <img :src="image" width="300px" height="auto" />
        </v-card-text>
      </v-card>
    </div>
  </v-card>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      postInfo: {
        title: "",
        description: "",
        tags: [],
        nsfw: false,
        usersID: this.$store.state.currentUser.usersID,
        
      },
      images: [],
      tempTag: "",
      tempUploadedImg: [],
    };
  },
  mounted() {
    if (this.$store.state.loggedIn == false) {
      alert('Please login to create a post!');
      this.$router.push({name: 'Feed'})
    }
    this.postInfo.usersID = this.$store.state.currentUser["usersID"];
  },
  methods: {
    async newPostCall() {
      let response = await this.$store.dispatch("createPost", {
          data: this.postInfo,
          images: this.images
        });
        if (response["error"] != "none") {
          console.log({ response });
          console.log("Error loading data");
        } else if(response["error"] == 'none' || response["error"] == undefined) {
          alert('Post Successfully Made!');
          this.$router.push({name: 'Feed'});
          console.log({ response });
        } else {
          console.log({ response });
        }
    },
    async onSelectedFile(event) {
      this.tempUploadedImg = [];
      //console.log(event);
      this.images = event;
      //console.log(event[0]);
      for (let index = 0; index < event.length; index++) {
        const fileReader = new FileReader();
        fileReader.addEventListener("load", () => {
          this.tempUploadedImg.push(fileReader.result);
        });
        fileReader.readAsDataURL(event[index]);
      }
      console.log(this.images);
    },
    addTag(e) {
      //console.log(e);
      if (e.key === "," || (e.key === "Enter" && this.tempTag)) {
        if (e.key === ",") {
          this.tempTag = this.tempTag.substring(0, this.tempTag.length - 1);
        }
        if (!this.postInfo.tags.includes(this.tempTag)) {
          this.postInfo.tags.push(this.tempTag);
        }
        this.tempTag = "";
      }
    },
    removeTag(tag) {
      console.log("removeTag - Fired");
      this.postInfo.tags = this.postInfo.tags.filter((item) => {
        return tag !== item;
      });
    },
  },
  computed: {
    tempImg() {
      return (
        "https://nyaz.io/webStorage/nya/postImgs/tempImgs/" +
        this.tempUploadedImg
      );
    },
  },
};
</script>

<style>
</style>