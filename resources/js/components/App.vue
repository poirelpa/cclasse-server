<template>
  <div>
    <side-bar>

      <h1>
        <span class="text-2xl"><span class="-mr-3 text-sec-700">C</span>C</span>lasse
      </h1>
      <!-- Divider -->
      <hr class="my-2 md:min-w-full" />
      <!-- Navigation -->
      <ul class="md:flex-col md:min-w-full flex flex-col list-none">
        <li class="items-center">
          <router-link :to="{name:'Home'}" class="py-3 block">
            <i class="fas fa-home"></i> Accueil
          </router-link>
        </li>
      </ul>
      <!-- Divider -->
      <hr class="my-2 md:min-w-full" />
      <!-- Classes -->
      <h2
        class="md:min-w-full block no-underline"
      >
        Classes
      </h2>
      <!-- Navigation -->
      <classes/>
    </side-bar>
    <div class="relative md:ml-64 bg-gray-50 p-3 min-h-screen">
      <router-view/>
    </div>
  </div>
</template>

<style>
</style>

<script>
  import SideBar from "./SideBar.vue";
  import Classes from "./Classes.vue";
  export default {
    components:{SideBar, Classes}
  }
</script>
