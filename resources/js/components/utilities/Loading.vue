<template>
  <slot v-if="isLoaded"/>
  <div v-else class="bg-gray-500 bg-opacity-30 inset-0 text-center text-gray-600 p-5 rounded-md">
    <i class="fas fa-spinner animate-spin text-3xl" />
  </div>
</template>

<script>
  export default {
    props:{
      isLoaded:Boolean
    }
  }
</script>
