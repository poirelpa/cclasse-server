<template>
  <h1>Classes</h1>
  <ul>
    <li v-for="classItem of classes" class="pl-5">
      <router-link :to="{name:'Class',params:{id:classItem.id}}">
        <i class="fas fa-users"></i> {{classItem.name}}
      </router-link>
    </li>
    <li class="pl-5">
      <router-link :to="{name:'NewClass'}">
        <i class="fas fa-plus"></i> Créer
      </router-link>
    </li>
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
