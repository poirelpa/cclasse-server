<template>
  <div
    v-if="debugMode"
    class="text-gray font-mono text-xs whitespace-pre"
  >
    {{ JSON.stringify(obj,null,2) }}
  </div>
</template>

<script>
export default {
  props: { obj: { type: Object, default: () => {} } },
  data () {
    return {
      debugMode: false
    }
  }
}
</script>
