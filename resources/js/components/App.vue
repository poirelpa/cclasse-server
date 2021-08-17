<template>
  <div id="two-way-binding">
    <p>{{ message }}</p>
    <input v-model="message" />
    <button @click="changeMessage">changer</button>
  </div>
</template>


<script>
    export default {
      data() {
        return {
          message: "test"
        }
      },
        methods : {
            changeMessage () {
                this.message = "tata";
            }
        }
    }
</script>
