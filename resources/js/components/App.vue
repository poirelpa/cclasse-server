<template>
<div class="flex p-3">
  <div class="flex-none w-60 p-2 bg-yellow-200">
    <nav>
      <ul>
        <li><router-link to="/"><i class="fas fa-home"></i> Accueil</router-link></li>
        <li>
          <Classes></Classes>
        </li>
        <li><router-link to="/about"><i class="fas fa-comment-dots"></i> A propos</router-link></li>
      </ul>
    </nav>
  </div>
  <div class="flex-1 bg-gray-100 p-2">
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
