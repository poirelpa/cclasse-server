<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1 v-if="isNew">
        Nouvelle Progression
      </h1>
      <h1 v-else>
        Progression
      </h1>
      <div>
        <label
          for="name"
          class=""
        ><h2>Nom :</h2></label>
        <input
          id="name"
          v-model="name"
          v-focus
          type="text"
          class="w-80 m-0"
          required
        >
      </div>

      <button
        v-if="isNew"
        type="submit"
        :disabled="buttonClicked"
      >
        Créer
      </button>
      <span
        v-else
        class="space-x-2"
      >
        <button
          type="button"
          :disabled="buttonClicked"
          @click="initDataFromStore"
        >Annuler</button>
        <button
          type="button"
          :disabled="buttonClicked"
          @click="delete_"
        >Supprimer</button>
        <button
          type="submit"
          :disabled="buttonClicked"
        >Modifier</button>
      </span>
      <h2>
        <router-link :to="{name:'Progressions', params: {classId: classId}}">
          <i class="fas fa-th-list" /> Retour
        </router-link>
      </h2>
    </loading>
  </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    id: {
      type: Number,
      default: NaN
    },
    classId: {
      type: Number,
      default: NaN
    }
  },
  data () {
    return {
      name: '',
      isLoaded: true,
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    storeProgression () { return this.findProgression(this.id) ?? {} },
    ...mapGetters('progressions', {
      findProgression: 'find',
      progressionsLoadedFor: 'isLoadedFor'
    })
  },
  watch: {
    classId () {
      this.initDataFromApi()
    }
  },
  mounted () {
    this.initDataFromApi()
  },
  methods: {
    async initDataFromApi () {
      await this.loadProgressions(this.classId)
      this.isLoaded = true
      this.buttonClicked = false
    },
    ...mapActions('progressions', { loadProgressions: 'load' })
  }
}
</script>
