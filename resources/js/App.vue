<template>
  <div>
    <side-bar>
      <h1>
        <span class="text-2xl"><span class="-mr-3 text-sec-700">C</span>C</span>lasse
      </h1>
      <!-- Divider -->
      <hr class="my-2 md:min-w-full">
      <!-- Navigation -->
      <ul class="md:flex-col md:min-w-full flex flex-col list-none py-3">
        <li>
          <router-link
            :to="{name:'Home'}"
            class="block"
          >
            <i class="fas fa-home" /> Accueil
          </router-link>
        </li>
        <li v-if="$isGuest">
          <router-link
            :to="{name:'Login'}"
            class="block"
          >
            <i class="fas fa-sign-in-alt" /> Connexion
          </router-link>
        </li>
        <li v-if="$isGuest">
          <router-link
            :to="{name:'Register'}"
            class="block"
          >
            <i class="fas fa-user-plus" /> Créer un compte
          </router-link>
        </li>
        <li v-if="$isA('admin')">
          <router-link
            :to="{name:'Admin'}"
            class="block"
          >
            <i class="fas fa-user-cog" /> Administration
          </router-link>
        </li>
        <li v-if="!$isGuest">
          <router-link
            :to="{name:'Logout'}"
            class="block"
          >
            <i class="fas fa-sign-out-alt" /> Déconnexion
          </router-link>
        </li>
      </ul>
      <div v-if="!$isGuest">
        <!-- Divider -->
        <hr class="my-2 md:min-w-full">
        <!-- Classes -->
        <h2
          class="md:min-w-full block no-underline"
        >
          Classes
        </h2>
        <!-- Navigation -->
        <classes />
      </div>
    </side-bar>
    <div class="relative md:ml-64 bg-gray-50 p-3">
      <notification :message="notification" />
    </div>
    <div class="relative md:ml-64 bg-gray-50 p-3 min-h-screen flex items-center md:justify-center  ">
      <router-view />
    </div>
  </div>
</template>

<script>
import SideBar from './components/SideBar.vue'
import Classes from './classes/Classes.vue'
import Bouncer from './utils/bouncer.js'
import { mapState } from 'vuex'

export default {
  components: { SideBar, Classes },
  mixins: [
    Bouncer.mixin
  ],
  computed: mapState(['notification'])
}
</script>
