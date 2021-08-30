<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-8xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>Progressions</h1>
      <table>
        <tr>
          <td />
          <td
            v-for="week in weeks"
            :key="week.id"
          >
            {{ week.week }}
          </td>
        </tr>
      </table>
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
      buttonClicked: false
    }
  },
  computed: {
    progressions () { return this.listProgressions(this.classId) },
    weeks () { return this.listWeeks(this.classId) },
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
      await this.loadProgressions(this.classId)
      await this.loadWeeks(this.classId)
      this.buttonClicked = false
      this.isLoaded = true
    },
    ...mapActions('progressions', { loadProgressions: 'load' }),
    ...mapActions('weeks', { loadWeeks: 'load' })
  }
}
</script>
