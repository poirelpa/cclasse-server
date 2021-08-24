<template>
  <loading :is-loaded="isLoaded">
    <ul>
      <li>
        <router-link :to="{name:'NewClass'}">
          <i class="fas fa-plus" /> Créer
        </router-link>
      </li>
      <li
        v-for="classItem of classes"
        :key="classItem.id"
      >
        <router-link
          :to="{name:'Class',params:{id:classItem.id}}"
          :title="classItem.name"
        >
          <i class="fas fa-users" /> {{ classItem.name }}
        </router-link>
      </li>
    </ul>
  </loading>
</template>
<script>
export default {
  computed: {
    classes () { return this.$store.getters['classes/classes'] },
    isLoaded () { return this.$store.state.classes.classes.isLoaded }
  }
}
</script>
