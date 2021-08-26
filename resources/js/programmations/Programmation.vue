<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>
        <span v-if="isNew">Nouvelle Programmation</span>
      </h1>

      <div>
        <label
          for="name"
        ><h2>Nom :</h2></label>
        <input
          id="name"
          v-model="name"
          v-focus
          type="text"
          required
          minLength="3"
          max="255"
        >
        <div>
          <label
            for="color"
          ><h2>Couleur :</h2></label>
          <input
            id="color"
            v-model="color"
            type="color"
          >
        </div>
        <div>
          <label
            for="subject"
          ><h2>Matière :</h2></label>
          TODO
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
            @click="deleteProgrammation"
          >Supprimer</button>
          <button
            type="submit"
            :disabled="buttonClicked"
          >Modifier</button>
        </span>
      </div>
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
      color: '',
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    isLoaded () { return !!this.storeProgrammation },
    storeProgrammation () {
      return this.id
        ? this.$store.getters['progressions/progressionsById'][this.id]
        : {
            name: ''
          }
    }
  },
  watch: {
    id () {
      this.initDataFromApi()
    },
    storeProgrammation () {
      this.initDataFromStore()
    }
  },
  mounted () {
    this.initDataFromApi()
  },
  methods: {
    async initDataFromApi () {
      if (!this.isNew) {
        this.$store.dispatch('programmations/getProgrammation', this.storeProgrammation)
      }
      await this.initDataFromStore()
      this.buttonClicked = false
    },
    async save () {
      this.buttonClicked = true
      try {
        if (this.isNew) {
          this.$notify('Création en cours ...')
          const prog = await this.$store.dispatch(
            'programmations/createProgrammatin',
            { name: this.name, color: this.color, class_id: this.classId }
          )
          this.$router.push({
            name: 'Programmation',
            params: {
              class_id: this.classId,
              id: prog.id
            }
          })
          this.$notify('Création effectuée')
        } else {
          this.$notify('Sauvegarde en cours ...')
          await this.$store.dispatch(
            'programmations/updateProgrammation',
            Object.assign(
              {},
              this.storeProgrammation,
              { name: this.name, color: this.color })
          )
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
        await this.$store.dispatch('programmations/deleteProgrammation', this.storeProgrammation)
        this.$router.push({ name: 'Class', params: { id: this.classId } })
      }
    },
    initDataFromStore () {
      if (!this.isNew &&
          this.$store.state.programmations.programmations.isLoaded &&
          this.$store.getters['programmations/programmationsById'][this.id] === undefined) {
        this.$router.push('/missing')
      }

      this.name = this.storeProgrammation.name ?? ''
      this.color = this.storeColor ?? ''
    }
  }
}
</script>
