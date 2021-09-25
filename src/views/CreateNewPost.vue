<template>
  <v-card color="primary" width="900px" class="mx-auto pa-4">
    <v-card-title>
      <h1>Create a post</h1>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-row>
          <v-col>
            <v-text-field
              v-model="postInfo.title"
              label="Title"
              outlined
              color="secondary"
              :rules="rules"
            />
            <v-textarea
              v-model="postInfo.description"
              outlined
              label="Description"
              color="secondary"
            ></v-textarea>
          </v-col>
          <v-col>
            <v-file-input
              show-size
              multiple
              label="Choose an image! (Max 3)"
              @change="onSelectedFile"
              outlined
              color="secondary"
            />
            <v-text-field
              placeholder="Press Enter to finsh a tag"
              v-model="tempTag"
              @keyup="addTag"
              label="Tags"
              outlined
              color="secondary"
            />
            <div
              v-for="tag in postInfo.tags"
              :key="tag"
              class="pill"
              @click="removeTag(tag)"
            >
              <v-icon>mdi-close</v-icon>{{ tag }}
            </div>
            <v-checkbox
              v-model="postInfo.nsfw"
              label="This content contains Not Safe For Work (NSFW) material"
              color="secondary"
            />
          </v-col>
        </v-row>
        <v-btn color="white" class="black--text" @click="newPostCall"
          >Create post!</v-btn
        >
      </v-form>
    </v-card-text>
    <div
      class="d-inline-flex mt-3"
      v-for="image in tempUploadedImg"
      :key="image"
    >
      <v-card class="mr-2 mb-2">
        <v-card-text>
          <img :src="image" width="150px" height="auto" />
        </v-card-text>
      </v-card>
    </div>
  </v-card>
</template>

<script>
export default {
  metaInfo: {
    title: "Create New Post",
    "http-equiv": "Content-Security-Policy",
    content: "upgrade-insecure-requests",
  },
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
      rules: [(v) => !!v || "Required Field"],
    };
  },
  mounted() {
    if (this.$store.state.loggedIn == false) {
      alert("Please login to create a post!");
      this.$router.push({ name: "Feed" });
    }
    this.postInfo.usersID = this.$store.state.currentUser["usersID"];
  },
  methods: {
    async newPostCall() {
      let response = await this.$store.dispatch("data/createPost", {
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
      console.log(event);
      this.images = event;
      console.log(event[0]);
      for (let index = 0; index < event.length; index++) {
        const fileReader = new FileReader();
        fileReader.addEventListener("load", () => {
          this.tempUploadedImg.push(fileReader.result);
        });
        fileReader.readAsDataURL(event[index]);
      }
      console.log(this.images);
    },
    removeImage(id) {
      let temp = [];
      for (let item of this.images) {
        if (item.id != id) {
          temp.push(item);
        }
      }
      this.images = temp;
      temp = [];
      for (let item of this.tempUploadedImg) {
        if (item.id != id) {
          temp.push(item);
        }
      }
      this.tempUploadedImg = temp;
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