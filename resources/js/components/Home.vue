<template>
  <h1>Accueil</h1>
  {{JSON.stringify(classes)}}
  <ul>
    <li v-for="classItem of classes">{{classItem.name}}</li>
  </ul>
</template>
<script>
  export default {
    computed: {
      classes() { return this.$store.state.classes}
    },
    async mounted () {
      await this.$store.dispatch('fetchClasses')
    }
  }
</script>
