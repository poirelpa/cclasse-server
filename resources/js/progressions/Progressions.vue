<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-8xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>Progressions</h1>
      <div :class="'grid grid-cols-' + (weeks.length + 1)">
        <div
          v-for="week in weeks"
          :key="week.id"
          class="px-1"
          :class="'col-start-' + (week.week + 1)"
        >
          {{ week.week }}
        </div>
        <div
          v-for="progression in progressions"
          :key="progression.id"
          class="border border-black"
          :class="{
            ['col-start-' + (progression.start + 1)]: progression.start !== undefined,
            'col-start-1': progression.start === undefined
          }"
        >
          {{ progression.name }}
        </div>
      </div>
      <div>
        <button
          type="button"
          @click="addNewProgression"
        >
          <i class="fas fa-plus" /> Créer
        </button>
      </div>
    </loading>
  </form>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: {
    classId: {
      type: Number,
      default: NaN
    }
  },
  data () {
    return {
      isLoaded: false,
      buttonClicked: false,
      progressions: [],
      weeks: [],
      localIdSequence: -1,
      added: [],
      modified: [],
      deleted: []
    }
  },
  computed: {
    ...mapGetters('progressions', { listProgressions: 'listFor' }),
    ...mapGetters('weeks', { listWeeks: 'listFor' })
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
      await this.loadWeeks(this.classId)
      this.weeks = this.listWeeks(this.classId)
      console.log(this.$store.getters['weeks/listFor'](this.classId))
      await this.loadProgressions(this.classId)
      this.progressions = this.listProgressions(this.classId)
      this.buttonClicked = false
      this.isLoaded = true
    },
    addNewProgression () {
      const prog = {
        id: this.localIdSequence--,
        name: 'nouvelle progression'
      }
      this.progressions.push(prog)
      this.added.push(prog)
    },
    ...mapActions('progressions', { loadProgressions: 'load' }),
    ...mapActions('weeks', { loadWeeks: 'load' })
  }
}
</script>
