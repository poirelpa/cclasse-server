<template>
  <form @submit.prevent="create" class="bg-white shadow rounded p-4 mx-auto max-w-2xl">
    <h1>Nouvelle classe : {{name}}</h1>
    <div>
      <label for="year" class=""><h2>Année :</h2></label>
      <input type="number" v-model="year" id="year" class="w-16 m-0"/> / {{year+1}}
    </div>

    <h2 v-if="Object.keys(levels).length">Niveau(x) :</h2>
    <div class="flex flex-wrap">
      <div v-for="level of levels" class="mx-2 w-14 flex-shrink-0">
        <input v-model="checkedLevels" type="checkbox" :id="level.id" :value="level.id" />
        <label :for="level.id" class="ml-1"> {{level.name}}</label>
      </div>
     </div>
     <button type="submit">Créer</button>
  </form>
  <debug :obj="checkedLevels"/>
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
        year:0,
        checkedLevels:[]
      }
    },
    computed: {
      levels() { return this.$store.getters.levels },
      name() { return printYear(this.year) + (this.checkedLevels.length ? ' (' + this.checkedLevels.sort().map(id=>this.$store.getters.levelsById[id]?.name).join(' ') + ')': '')},
      id() { return this.$route.params.id },
      storeClass() { return this.$store.getters.classesById[this.id] ?? {}},
      storeYear() { return this.storeClass.year ?? new Date().getFullYear()},
      storeCheckedLevels() { return this.storeClass.levels?.map(l=>l.id) ?? []}
    },
    watch:{
      storeClass(){
        this.initDataFromStore()
      },
      levels(){
        this.initDataFromStore()
      }
    },
    methods: {
      async create(){
        let cl = await this.$store.dispatch("createClass",{name:this.name, year:this.year, levels:this.checkedLevels})
        this.$router.push({name:'Class',params:{id:cl.id}})
      },
      initDataFromStore(){
          this.year=this.storeYear
          this.checkedLevels = this.storeCheckedLevels
      }
    },
    mounted() {
      this.$store.dispatch("getLevels")
      this.initDataFromStore()
    }
  }
</script>
