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
          @click="delete_"
        >Supprimer</button>
        <button
          type="submit"
          :disabled="buttonClicked"
        >Modifier</button>
      </span>
    </loading>
    <div v-if="storeClass.id">
      <h2>Programmations</h2>
      <router-link :to="{name:'NewProgramming', params: {classId: id}}">
        <i class="fas fa-plus" /> Créer
      </router-link>
      <ul>
        <li
          v-for="prog in storeClass.programmings"
          :key="prog.id"
        >
          <router-link
            :to="{name:'Programming',params:{classId: id, id:prog.id}}"
            :title="prog.name"
          >
            <i class="fas fa-users" /> {{ prog.name }}
          </router-link>
        </li>
      </ul>
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
            .map(id => this.findLevel[id]?.name).join(' ') + ')'
          : ''
      )
    },
    storeClass () { return this.findClass[this.id] ?? {} },
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
  },
  updated () {
    this.verifLevels()
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
    initDataFromStore () {
      if (!this.isNew &&
          this.classesLoaded &&
          this.findClass[this.id] === undefined) {
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
    initDataFromApi () {
      if (!this.isNew) {
        this.readClass(this.storeClass)
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
          this.$handleErrors(e)
        }
      }
      this.buttonClicked = false
    },
    async delete_ () {
      if (confirm('Veuillez confirmer la suppression')) {
        await this.deleteClass(this.storeClass)
        this.buttonClicked = true
        this.$router.push({ name: 'Home' })
      }
    }
  }
}
</script>
