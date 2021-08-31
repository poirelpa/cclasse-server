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
          class="px-1 border-sec-500 text-center"
          :class="{
            ['col-start-' + (week.week + 1)]: true,
            'border-l': week.new_period
          }"
        >
          {{ week.week }}
        </div>
        <div
          v-for="progression in progressions"
          :key="progression.id"
          class="border-2 border-pri-500 rounded px-2"
          :class="{
            ['col-start-' + (progression.start + 1)]: progression.start !== undefined,
            'col-start-1': progression.start === undefined
          }"
        >
          {{ progression.name }}
        </div>
      </div>
      <h2>
        <router-link :to="{ name: 'NewProgression', params: { classId: classId } }">
          <i class="fas fa-plus" /> Créer
        </router-link>
      </h2>
      <h2>
        <router-link :to="{ name: 'Class', params: { id: classId } }">
          <i class="fas fa-users" /> Retour
        </router-link>
      </h2>
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
      await this.loadProgressions(this.classId)
      this.progressions = this.listProgressions(this.classId)
      this.buttonClicked = false
      this.isLoaded = true
    },
    ...mapActions('progressions', { loadProgressions: 'load' }),
    ...mapActions('weeks', { loadWeeks: 'load' })
  }
}
</script>
