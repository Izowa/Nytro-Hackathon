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
      :key="image.id"
    >
      <v-card class="mr-2 mb-2" @click="removeImage(image.id)">
        <v-icon class="pa-2" style="float: right">mdi-close</v-icon>
        <v-card-text>
          <img :src="image.image" width="150px" height="auto" />
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
      let imgFiles = [];
      for (let image of this.images) {
        imgFiles.push(image.image);
      }
      let response = await this.$store.dispatch("createPost", {
        data: this.postInfo,
        images: this.imgFiles,
      });
      if (response["error"] != "none") {
        console.log({ response });
        console.log("Error loading data");
      } else if (
        response["error"] == "none" ||
        response["error"] == undefined
      ) {
        alert("Post Successfully Made!");
        this.$router.push({ name: "Feed" });
        console.log({ response });
      } else {
        console.log({ response });
      }
    },
    async onSelectedFile(event) {
      this.addImages = false;
      if (this.images.length < 3) {
        for (let index = 0; index < event.length; index++) {
          this.images.push({ id: this.images.length + 1, image: event[index] });
          const fileReader = new FileReader();
          fileReader.addEventListener("load", () => {
            this.tempUploadedImg.push({
              id: this.images.length + 1,
              image: fileReader.result,
            });
          });
          fileReader.readAsDataURL(event[index]);
        }
        console.log(this.images);
      } else {
        this.$store.dispatch("alerts", {
          type: "warning",
          msg: "Maximum amount of images is 3.",
        });
      }
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