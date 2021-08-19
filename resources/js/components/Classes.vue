<template>
  <h1>Classes</h1>
  {{JSON.stringify(classes)}}
  <ul>
    <li v-for="classItem of classes"><router-link :to="{name:'Class',params:{id:classItem.id}}">{{classItem.name}}</router-link></li>
  </ul>
</template>
<script>
  export default {
    computed: {
      classes() { return this.$store.state.classes}
    },
    async mounted () {
      await this.$store.dispatch('getClasses')
      //console.log(this.classes)
    }
  }
</script>
