<template>
  <h1>{{classItem?.name}}</h1>
  <debug :obj="classItem"/>
</template>

<script>

  export default {
    computed: {
      classItem(state) {
        return this.$store.state.classes[this.$route.params.id]??{}
      },
    }
  }
</script>
