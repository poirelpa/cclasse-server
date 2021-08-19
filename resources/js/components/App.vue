<template>
  <div>
    <Classes></Classes>
  </div>
  <div id="nav">
    <router-link to="/">Home</router-link> |
    <router-link to="/about">About</router-link>
  </div>
  <router-view/>
</template>
<style>
</style>

<script>
  import Classes from "../components/Classes.vue";
  export default {
    components:{Classes}
  }
</script>
