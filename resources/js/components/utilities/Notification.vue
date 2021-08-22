<template>
  <div v-if="message.length > 0 && !closed" class="w-full bg-qua-600 text-white p-2 rounded shadow mb-2 p-0">
    <button title="close" type="button" class="float-right bg-transparent shadow-none my-0 focus:bg-transparent hover:bg-transparent pt-1" @click="close"><i class="fas fa-times"/></button>
    <div class="whitespace-pre-wrap">{{message}}</div>
  </div>
</template>

<script>
  export default {
    data(){
      return{
        closed:this.message.length > 0
      }
    },
    props:{
      'message':String
    },
    watch:{
      message:function(newv,oldv){
        this.closed = false
      }
    },
    methods:{
      close(){
        this.closed = true
      }
    }
  }
</script>
