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
            v-for="(day, i) in days"
            :key="i"
            class="mx-2 w-14 flex-shrink-0"
          >
            <input
              :id="day"
              v-model="checkedDays"
              type="checkbox"
              :value="i"
              @change="verifDays"
            >
            <label
              :for="day"
              class="ml-1"
            > {{ day }}</label>
          </div>
        </div>
        <div>
          <label
            for="academy"
            class=""
          ><h2>Académie :</h2></label>
          <select
            id="academy"
            v-model="academy"
            required
            class="w-80"
          >
            <option
              v-for="a in academies"
              :key="a.id"
              :value="a.id"
            >
              {{ a.name }}
            </option>
          </select>
        </div>
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
    </loading>
    <div v-if="!isNew">
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
      days: {
        1: 'Lundi',
        2: 'Mardi',
        3: 'Mercredi',
        4: 'Jeudi',
        5: 'Vendredi',
        6: 'Samedi'
      },
      checkedDays: [1, 2, 4, 5],
      academy: undefined,
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
    }),
    ...mapGetters('academies', {
      academies: 'list'
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
      const cb = document.getElementById('Lundi')
      if (!cb) return
      cb.setCustomValidity(
        this.checkedDays.length > 0
          ? ''
          : 'Veuillez sélectionner au moins un jour.'
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
    ...mapActions('academies', { loadAcademies: 'load' }),
    async initDataFromApi () {
      this.initDataFromStore()
      if (this.isNew) {
        await this.loadAcademies()
      }
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
          localClass.days = this.checkedDays
          localClass.academy_id = this.academy
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
          this.handleError(e)
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
