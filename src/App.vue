<template>
  <v-app class="coreBG">
  <NavDesktop/>
  <!-- Sizes your content based upon application components -->
  <v-main>

    <!-- Provides the application the proper gutter -->
    <v-container fluid>
      <!-- If using vue-router -->
      <router-view></router-view>
    </v-container>
    
  </v-main>
  <v-footer app color="grey">
    <img src="@/assets/cat.png" :width="$vuetify.breakpoint.mobile ? '150px' : '300px'" height="auto" :class="{'cat': $vuetify.breakpoint. mdAndUp, 'catMobile': $vuetify.breakpoint. smAndDown}">
    <h5 class="mx-auto pa-5">@ Lost Night Studios</h5>
  </v-footer>
</v-app>
</template>

<script>
import NavDesktop from '@/components/NavDesktop.vue'
export default  {
  name: 'App',
  metaInfo: {
        title: 'test nya'
  },
  components: {
    NavDesktop
  },
  created() {
    this.$vuetify.theme.dark;
    this.$store.dispatch("loadUser");
  }
};
</script>

<style>
.coreBG{
  background: #312F2F !important;
}

.cat {
  position: absolute;
  bottom: 20px;
  left: 50px;
}

.catMobile{
    position: absolute;
    bottom: -5rem;
    left: -2.5rem;
}

.pointerG {
  cursor: pointer;
}
</style>
