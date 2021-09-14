import Vue from 'vue';
import Vuetify from 'vuetify/lib/framework';

Vue.use(Vuetify);

export default new Vuetify({
    theme: { 
      dark: true,
    themes: {
      dark: {
        primary: '#BF3D3D',
        secondary: '#312F2F',
        accent: '#707070'
      },
      light: {
        primary: '#BF3D3D',
        secondary: '#312F2F',
        accent: '#707070'
      }
      }
    },
  })