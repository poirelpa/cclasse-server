<template>
  <h1>{{name}}</h1>
  <form @submit.prevent="create">
    <input type="number" v-model="year">
    <div v-for="level of levels">
      <input v-model="checkedLevels" type="checkbox" :id="level.id" :value="level.id" />
      <label :for="level.id">{{level.name}}</label>
    </div>
    <button type="submit" class="border p-1 mt-2 border-gray-600 rounded">Créer</button>
  </form>
  <debug :obj="{name:name, year:year, levels:checkedLevels}"/>
</template>

<script>

  let printYear = function(year){return year + "/" + (year+1)}
  export default {
    data() {
      return {
        year:new Date().getFullYear(),
        checkedLevels:[]
      }
    },
    computed: {
      levels() { return this.$store.state.levels ?? []},
      name() { return printYear(this.year) + (this.checkedLevels.length ? ' (' + this.checkedLevels.map(l=>this.levels[l].name).join(' ') + ')': '')}
    },
    methods: {
      create(){
        this.$store.dispatch("createClass",{name:this.name, year:this.year, levels:this.checkedLevels})
      }
    },
    mounted() {
      this.$store.dispatch("getLevels")
    }
  }
</script>
