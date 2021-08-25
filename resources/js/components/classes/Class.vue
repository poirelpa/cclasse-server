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
          @click="deleteClass"
        >Supprimer</button>
        <button
          type="submit"
          :disabled="buttonClicked"
        >Modifier</button>
      </span>
    </loading>
    <div v-if="storeClass.id">
      <h2>Programmations</h2>
      <router-link
        :to="{name:'NewProgrammation', params: {classId: storeClass.id}}"
      >
        <i class="fas fa-plus" /> Créer
      </router-link>
      <ul>
        <li
          v-for="prog in storeClass.progressions"
          :key="prog.id"
        >
          <router-link
            :to="{name:'Progression',params:{id:prog.id}}"
            :title="prog.name"
          >
            <i class="fas fa-users" /> {{ classItem.name }}
          </router-link>
        </li>
      </ul>
    </div>
  </form>
</template>

<script>

const printYear = function (year) { return year + '/' + (year + 1) }
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
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    isLoaded () { return this.$store.state.classes.classes.isLoaded && this.$store.state.classes.levels.isLoaded },
    levels () { return this.$store.getters['classes/levels'] },
    name () { return printYear(this.year) + (this.checkedLevels.length ? ' (' + this.checkedLevels.slice(0).sort().map(id => this.$store.getters['classes/levelsById'][id]?.name).join(' ') + ')' : '') },
    storeClass () { return this.$store.getters['classes/classesById'][this.id] ?? {} },
    storeYear () { return this.storeClass.year ?? new Date().getFullYear() },
    storeCheckedLevels () { return this.storeClass.levels?.map(l => l.id) ?? [] }
  },
  watch: {
    storeClass () {
      this.initDataFromStore()
    },
    levels () {
      this.initDataFromStore()
    }
  },
  created () {
    this.$store.dispatch('classes/getLevels')
  },
  mounted () {
    this.initDataFromStore()
    this.buttonClicked = false
  },
  updated () {
    this.verifLevels()
  },
  methods: {
    verifLevels () {
      const cb = document.getElementById('level0')
      if (!cb) return
      cb.setCustomValidity(this.checkedLevels.length > 0 ? '' : 'Veuillez sélectionner au moins un niveau.')
    },
    async save () {
      this.buttonClicked = true
      try {
        if (this.isNew) {
          this.$notify('Création en cours ...')
          const cl = await this.$store.dispatch('classes/createClass', { name: this.name, year: this.year, levels: this.checkedLevels })
          this.$router.push({ name: 'Class', params: { id: cl.id } })
          this.$notify('Création effectuée')
        } else {
          this.$notify('Sauvegarde en cours ...')
          await this.$store.dispatch('classes/updateClass', Object.assign({}, this.storeClass, { name: this.name, year: this.year, levels: this.checkedLevels }))
          this.$notify('Modifications sauvegardées')
        }
      } catch (e) {
        if (!e.handled) {
          this.$notify(`Exception non répertoriée : ${e.message}`)
        }
      }
      this.buttonClicked = false
    },
    async deleteClass () {
      if (confirm('Veuillez confirmer la suppression')) {
        this.buttonClicked = true
        await this.$store.dispatch('classes/deleteClass', this.storeClass)
        this.$router.push({ name: 'Home' })
      }
    },
    initDataFromStore () {
      if (!this.isNew &&
          this.$store.state.classes.classes.isLoaded &&
          this.$store.getters['classes/classesById'][this.id] === undefined) {
        this.$router.push('/missing')
      }

      this.year = this.storeYear
      this.checkedLevels = this.storeCheckedLevels
    }
  }
}
</script>
