<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>
        <span v-if="isNew">Nouvelle classe : </span>
        {{ name }}
      </h1>
      <div>
        <label
          for="year"
          class=""
        ><h2>Année :</h2></label>
        <input
          id="year"
          v-model="year"
          v-focus
          type="number"
          class="w-20 m-0"
          required
          min="2020"
          max="2030"
        > / {{ year+1 }}
      </div>

      <h2 v-if="Object.keys(levels).length">
        Niveau(x) :
      </h2>
      <div class="flex flex-wrap">
        <div
          v-for="(level, i) in levels"
          :key="level.id"
          class="mx-2 w-14 flex-shrink-0"
        >
          <input
            :id="'level' + i"
            v-model="checkedLevels"
            type="checkbox"
            :value="level.id"
            @change="verifLevels"
          >
          <label
            :for="'level' + i"
            class="ml-1"
          > {{ level.name }}</label>
        </div>
      </div>
      <div v-if="isNew">
        <h2 v-if="Object.keys(levels).length">
          Jours d'école :
        </h2>
        <div class="flex flex-wrap">
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="monday"
              v-model="checkedDays[1]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="monday"
              class="ml-1"
            > Lundi</label>
          </div>
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="tuesday"
              v-model="checkedDays[2]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="tuesday"
              class="ml-1"
            > Mardi</label>
          </div>
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="wednesday"
              v-model="checkedDays[3]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="wednesday"
              class="ml-1"
            > Mercredi</label>
          </div>
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="thursday"
              v-model="checkedDays[4]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="thursday"
              class="ml-1"
            > Jeudi</label>
          </div>
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="friday"
              v-model="checkedDays[5]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="friday"
              class="ml-1"
            > Vendredi</label>
          </div>
          <div
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              id="staturday"
              v-model="checkedDays[6]"
              type="checkbox"
              @change="verifDays"
            >
            <label
              for="saturday"
              class="ml-1"
            > Samedi</label>
          </div>
        </div>
      </div>

      <button
        v-if="!id"
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
    </loading>
    <div v-if="storeClass.id">
      <h2>
        <router-link :to="{name:'Progressions', params: {classId: id}}">
          <i class="fas fa-th-list" /> Progressions
        </router-link>
      </h2>
      <h2>
        <router-link :to="{name:'Progressions', params: {classId: id}}">
          <i class="fas fa-calendar" /> Emplois du temps
        </router-link>
      </h2>
      <h2>
        <router-link :to="{name:'Progressions', params: {classId: id}}">
          <i class="fas fa-book-open" /> Cahier journal
        </router-link>
      </h2>
    </div>
  </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    id: {
      type: Number,
      default: NaN
    }
  },
  data () {
    return {
      year: 0,
      checkedLevels: [],
      checkedDays: [false, true, true, false, true, true, false],
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    isLoaded () { return this.classesLoaded && this.levelsLoaded },
    name () {
      return this.year + '/' + (this.year + 1) +
      (
        this.checkedLevels.length
          ? ' (' + this.checkedLevels.slice(0).sort()
            .map(id => this.findLevel(id)?.name).join(' ') + ')'
          : ''
      )
    },
    storeClass () { return this.findClass(this.id) ?? {} },
    ...mapGetters('classes', {
      findClass: 'find',
      classesLoaded: 'isLoaded'
    }),
    ...mapGetters('levels', {
      levels: 'list',
      findLevel: 'find',
      levelsLoaded: 'isLoaded'
    })
  },
  watch: {
    storeClass () {
      this.initDataFromStore()
    },
    levels () {
      this.initDataFromStore()
    },
    id () {
      this.initDataFromApi()
    }
  },
  mounted () {
    this.initDataFromApi()
    this.verifLevels()
    this.verifDays()
  },
  updated () {
    this.verifLevels()
    this.verifDays()
  },
  methods: {
    verifLevels () {
      const cb = document.getElementById('level0')
      if (!cb) return
      cb.setCustomValidity(
        this.checkedLevels.length > 0
          ? ''
          : 'Veuillez sélectionner au moins un niveau.'
      )
    },
    verifDays () {
      const cb = document.getElementById('monday')
      if (!cb) return
      cb.setCustomValidity(
        this.checkedDays.reduce(
          (prev, val) => prev + (val ? 1 : 0),
          0
        ) > 0
          ? ''
          : 'Veuillez sélectionner au moins un jour d\'école.'
      )
    },
    initDataFromStore () {
      if (!this.isNew &&
          this.classesLoaded &&
          this.findClass(this.id) === undefined) {
        this.$router.push('/missing')
      }

      this.year = this.storeClass.year ?? new Date().getFullYear()
      this.checkedLevels = this.storeClass.levels?.map(l => l.id) ?? []
    },
    ...mapActions('classes', {
      createClass: 'create',
      readClass: 'read',
      updateClass: 'update',
      deleteClass: 'delete'
    }),
    async initDataFromApi () {
      this.initDataFromStore()
      if (!this.isNew) {
        await this.readClass(this.storeClass)
      }
      this.initDataFromStore()
      this.buttonClicked = false
    },
    async save () {
      this.buttonClicked = true
      try {
        const localClass = {
          name: this.name,
          year: this.year,
          levels: this.checkedLevels
        }
        if (this.isNew) {
          this.$notify('Création en cours ...')
          const cl = await this.createClass(localClass)
          this.$router.push({ name: 'Class', params: { id: cl.id } })
          this.$notify('Création effectuée')
        } else {
          this.$notify('Sauvegarde en cours ...')
          await this.updateClass(Object.assign({}, this.storeClass, localClass))
          this.$notify('Modifications sauvegardées')
        }
      } catch (e) {
        if (!e.handled) {
          this.$notify(`Exception non répertoriée : ${e.message}`)
        } else {
          this.handleErrors(e)
        }
      }
      this.buttonClicked = false
    },
    async delete_ () {
      if (confirm('Veuillez confirmer la suppression')) {
        this.deleteClass(this.storeClass)
        this.buttonClicked = true
        this.$router.push({ name: 'Home' })
      }
    }
  }
}
</script>
