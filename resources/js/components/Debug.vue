<template>
  <div class="text-gray font-mono text-xs whitespace-pre">
    {{JSON.stringify(obj,null,2)}}
  </div>
</template>

<script>
  export default {
    props:['obj']
  }
</script>
