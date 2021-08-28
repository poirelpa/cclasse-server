<template>
  <div
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
  >
    <h1>Administration</h1>
    <label for="year">Année : </label>
    <input
      id="year"
      v-model="year"
      type="number"
    >
    <br>
    <button @click="initPublicHolidays">
      Initialisation jours feriés {{ year }}
    </button>
    <br>
    <button @click="initSchoolHolidays">
      Initialisation vacances scolaires {{ year }}/{{ year + 1 }}
    </button>
  </div>
</template>

<script>
import api from '../utils/api.js'

export default {
  data () {
    return {
      year: new Date().getFullYear()
    }
  },
  methods: {
    async initPublicHolidays () {
      const response = await api.post('/admin/publicHolidays', {
        source: 'api',
        year: this.year
      })
      this.$notify(response.data.message)
    },
    async initSchoolHolidays () {
      const response = await api.post('/admin/schoolHolidays', {
        source: 'api',
        year: this.year
      })
      this.$notify(response.data.message)
    }
  }
}
</script>
