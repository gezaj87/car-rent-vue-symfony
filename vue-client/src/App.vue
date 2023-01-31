<script>
import StateManager from './assets/StateManager';
import { RouterLink, RouterView } from 'vue-router'
import Header from './components/Header.vue';
import HomeView from './views/HomeView.vue';
import Helper from './assets/Helper';

export default {

  



  components: {
    Header
  },
  data() {
    return {
      states: StateManager.states
    }
  },
  methods: {
    teszt() {
      console.log(this.states.isLoggedIn);
    }
  },
  mounted() {
    Helper.Auth(this.states);
  }

  



}

</script>

<template>
  <div id="cover-spin"></div>
  
  <Header :state="states" />
    

  <RouterView :state="states" />
</template>




<style scoped>

</style>
