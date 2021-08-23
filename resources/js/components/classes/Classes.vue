<template>
  <loading :isLoaded="isLoaded">
    <ul>
      <li>
        <router-link :to="{name:'NewClass'}">
          <i class="fas fa-plus"></i> Créer
        </router-link>
      </li>
      <li v-for="classItem of classes">
        <router-link :to="{name:'Class',params:{id:classItem.id}}" :title="classItem.name">
          <i class="fas fa-users"></i> {{classItem.name}}
        </router-link>
      </li>
    </ul>
  </loading>
</template>
<script>
  export default {
    computed: {
      classes() { return this.$store.getters['classes/classes'] },
      isLoaded() { return this.$store.state.classes.classes.isLoaded }
    }
  }
</script>
