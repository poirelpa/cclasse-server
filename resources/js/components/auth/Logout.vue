<template>
    <div class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Déconnexion</h1>
      <loading />
    </div>
</template>

<script>
  export default {
    async mounted(){
      await this.$store.dispatch("auth/logout")
      this.$router.push({name:'Login'})
    }
  }
</script>
