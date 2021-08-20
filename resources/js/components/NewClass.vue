<template>
  <form @submit.prevent="create" class="bg-white shadow rounded p-4">
    <h1>Nouvelle classe : {{name}}</h1>
    <div>
      <label for="year" class=""><h2>Année :</h2></label>
      <input type="number" v-model="year" id="year" class="w-16 m-0"/> / {{year+1}}
    </div>

    <h2>Niveau(x) :</h2>
    <div class="flex flex-wrap">
      <div v-for="level of levels" class="mx-2 w-14 flex-shrink-0">
        <input v-model="checkedLevels" type="checkbox" :id="level.id" :value="level.id" />
        <label :for="level.id" class="ml-1"> {{level.name}}</label>
      </div>
     </div>
     <button type="submit">Créer</button>
  </form>
  <debug :obj="{name:name, year:year, levels:checkedLevels}"/>
</template>
<style>
  div.grid{
    grid-template-rows: repeat(auto-fit,minmax(300px,1fr));
    grid-auto-flow: column;
  }
</style>
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
