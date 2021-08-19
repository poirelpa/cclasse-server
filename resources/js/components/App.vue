<template>
<div class="flex">
  <div class="flex-none w-60">
    <nav>
      <ul>
        <li>
          <Classes></Classes>
        </li>
        <li><router-link to="/">Home</router-link></li>
        <li><router-link to="/about">About</router-link></li>
      </ul>
    </nav>
  </div>
  <div class="flex-1">
    <router-view/>
  </div>
</div>
</template>

<style>
</style>

<script>
  import Classes from "../components/Classes.vue";
  export default {
    components:{Classes}
  }
</script>
