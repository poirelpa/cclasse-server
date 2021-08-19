<template>
  <h1>{{classItem?.name}}</h1>
  {{JSON.stringify(classItem)}}
</template>
<script>

  export default {
    computed: {
      classItem(state) {
        return this.$store.state.classes.find(c=>c.id == this.$route.params.id)
      },
    }
  }
</script>
