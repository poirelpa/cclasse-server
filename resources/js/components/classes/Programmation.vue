<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>
        <span v-if="isNew">Nouvelle Programmation : </span>
        {{ name }}
      </h1>
    </loading>
  </form>
</template>

<script>

export default {
  props: {

    classId: {
      type: Number,
      default: NaN
    },
    id: {
      type: Number,
      default: NaN
    }
  },
  data () {
    return {
      name: '',
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    isLoaded () { return false },
    storeProgrammation () {
      return this.id
        ? this.$store.getters['progressions/progressionsById'][this.id]
        : {
            name: ''
          }
    }
  } /*
  ,
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
  } */
}
</script>
