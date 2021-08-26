<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <h1>
      <span :v-if="isNew">Nouvelle </span>Programmation
    </h1>

    <div>
      <label for="name"><h2>Nom :</h2></label>
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
        <label for="color"><h2>Couleur :</h2></label>
        <input
          id="color"
          v-model="color"
          type="color"
        >
      </div>
      <div>
        <label for="subject"><h2>Matière :</h2></label>
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
          @click="deleteProgramming"
        >Supprimer</button>
        <button
          type="submit"
          :disabled="buttonClicked"
        >Modifier</button>
      </span>
    </div>
  </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

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
      subject: { name: '' },
      buttonClicked: false
    }
  },
  computed: {
    isNew () { return Number.isNaN(this.id) },
    storeProgramming () { return this.find[this.id] ?? {} },
    ...mapGetters('programmings', ['find'])
  },
  watch: {
    storeProgramming () {
      this.initDataFromStore()
    },
    id () {
      console.log('id')
      this.initDataFromApi()
    }
  },
  mounted () {
    console.log('m')
    this.initDataFromApi()
  },
  methods: {
    initDataFromStore () {
      if (!this.isNew &&
          this.idLoaded &&
          this.find[this.id] === undefined) {
        this.$router.push('/missing')
      }

      this.name = this.storeProgramming.name ?? ''
      this.color = this.storeProgramming.color ?? ''
    },
    initDataFromApi () {
      if (!this.isNew) {
        this.readProgramming(this.storeProgramming)
      }
      this.initDataFromStore()
      this.buttonClicked = false
    },
    async save () {
      this.buttonClicked = true
      try {
        const localProgramming = {
          name: this.name,
          color: this.color,
          class_id: this.classId
        }
        if (this.isNew) {
          this.$notify('Création en cours ...')
          const cl = await this.createProgramming(localProgramming)
          this.$router.push({ name: 'Programming', params: { id: cl.id } })
          this.$notify('Création effectuée')
        } else {
          this.$notify('Sauvegarde en cours ...')
          await this.updateProgramming(Object.assign({}, this.storeProgramming, localProgramming))
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
        await this.deleteProgramming(this.storeProgramming)
        this.buttonClicked = true
        this.$router.push({ name: 'Home' })
      }
    },
    ...mapActions('programmings', {
      createProgramming: 'create',
      readProgramming: 'read',
      updateProgramming: 'update',
      deleteProgramming: 'delete'
    })
  }
}
</script>
