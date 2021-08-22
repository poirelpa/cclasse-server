<template>
  <div v-if="debugMode" class="text-gray font-mono text-xs whitespace-pre">
    {{JSON.stringify(obj,null,2)}}
  </div>
</template>

<script>
  export default {
    data(){
      return {
        debugMode:false
      }
    },
    props:['obj']
  }
</script>
