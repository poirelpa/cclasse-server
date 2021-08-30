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
      isLoaded: false,
      buttonClicked: false
    }
  },
  computed: {
    progressions () { return this.listFor(this.classId) },
    ...mapGetters('progressions', ['listFor'])
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
      await this.load(this.classId)
      this.buttonClicked = false
      this.isLoaded = true
    },
    ...mapActions('progressions', ['load'])
  }
}
</script>
