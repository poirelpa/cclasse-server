<template>
    <form @submit.prevent="save" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">

      <loading :isLoaded="isLoaded">
        <h1>
          <span v-if="isNew">Nouvelle classe : </span>
          {{name}}
        </h1>
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
        <button type="submit" :disabled="clicked" v-if="!id">Créer</button>
        <span v-else class="space-x-2">
          <button type="button" @click="initDataFromStore" :disabled="clicked">Annuler</button>
          <button type="button" @click="deleteClass" :disabled="clicked">Supprimer</button>
          <button type="submit" :disabled="clicked">Modifier</button>
        </span>
      </loading>
    </form>
  <debug :obj="storeClass"/>
</template>

<style>
</style>

<script>

  let printYear = function(year){return year + "/" + (year+1)}
  export default {
    data() {
      return {
        year:0,
        checkedLevels:[],
        clicked:false
      }
    },
    props:{
      id:{
        type:Number,
        default:NaN
      }
    },
    computed: {
      isNew() { return Number.isNaN(this.id) },
      isLoaded() { return this.$store.state.classes.isLoaded && this.$store.state.levels.isLoaded },
      levels() { return this.$store.getters.levels },
      name() { return printYear(this.year) + (this.checkedLevels.length ? ' (' + this.checkedLevels.sort().map(id=>this.$store.getters.levelsById[id]?.name).join(' ') + ')': '')},
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
      async save(){
        this.clicked = true
        if(this.isNew){
          let cl = await this.$store.dispatch("createClass",{name:this.name, year:this.year, levels:this.checkedLevels})
          this.$router.push({name:'Class',params:{id:cl.id}})
        } else {
          await this.$store.dispatch("updateClass",Object.assign({},this.storeClass,{name:this.name, year:this.year, levels:this.checkedLevels}))
        }
        this.clicked = false
      },
      async deleteClass(){
        if(confirm("Veuillez confirmer la suppression")){
          this.clicked = true
          await this.$store.dispatch("deleteClass",this.storeClass)
          this.$router.push({name:'Home'})
        }
      },
      initDataFromStore(){
        if(!this.isNew
          && this.$store.state.classes.isLoaded
          && this.$store.getters.classesById[this.id]==undefined){
          this.$router.push('/missing')
        }

        this.year=this.storeYear
        this.checkedLevels = this.storeCheckedLevels
      }
    },
    created() {
      this.$store.dispatch("getLevels")
    },
    mounted() {
      this.initDataFromStore()
      this.clicked = false
    }
  }
</script>
