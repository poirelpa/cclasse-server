<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="save"
  >
    <loading :is-loaded="isLoaded">
      <h1>Progressions</h1>
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
      buttonClicked: false
    }
  },
  computed: {
    ...mapGetters('progressions', [])
  },
  watch: {
    classId () {
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
    async initDataFromApi () {
      this.buttonClicked = false
    },
    ...mapActions('progressions', [])
  }
}
</script>
