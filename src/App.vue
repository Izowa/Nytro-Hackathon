<template>
  <v-app class="coreBG">
  <NavBar/>
  <Alert />
  <v-main>
    <v-container fluid>
      <router-view></router-view>
    </v-container>
  </v-main>
  <v-footer app color="grey">
    <img src="@/assets/images/cat.png" :width="$vuetify.breakpoint.mobile ? '150px' : '300px'" height="auto" :class="{'cat': $vuetify.breakpoint. mdAndUp, 'catMobile': $vuetify.breakpoint. smAndDown}">
    <h5 class="mx-auto pa-5">@ Lost Night Studios</h5>
  </v-footer>
</v-app>
</template>

<script>
import NavBar from '@/components/NavBar.vue';
import Alert from '@/components/Alert.vue';
export default  {
  name: 'App',
  metaInfo: {
    title: 'Nyaz'
  },
  components: {
    NavBar,
    Alert
  },
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
