<template>
  <h1>Accueil</h1>
  <button @click="increment">increment</button>
</template>
<script>
  export default {
    methods: {
      increment() {
        this.$store.commit('increment')
        console.log(this.$store.state.count)
      }
    },
    mounted () {
      axios
        .get('/api/v1/classes')
        .then(response => (console.log(response)))
    }
  }
</script>
