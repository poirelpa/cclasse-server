<template>
  <h1>Accueil</h1>
  <button @click="increment">increment</button>
  <ul>
    <li v-for="classItem of classes">{{classItem.name}}</li>
  </ul>
</template>
<script>
  export default {
    computed: {
      classes() { return this.$store.state.classes}
    },
    mounted () {
      this.$store.dispatch('fetchClasses')
    }
  }
</script>
