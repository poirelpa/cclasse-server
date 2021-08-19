<template>
  <h1>Nouvelle classe</h1>
  <form @submit.prevent="create">
    <input v-model="name" placeholder="Nom" />
    <button type="submit">Créer</button>
  </form>
</template>

<script>

  export default {
    data() {
      return {
        name:""
      }
    },
    methods: {
      create(){
        this.$store.dispatch("createClass",{name:this.name})
      }
    }
  }
</script>
