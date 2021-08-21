<template>
    <form @submit.prevent="sendMail" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Récupération de mot de passe</h1>
      <p>Un mail sera envoyé à l'adresse indiquée, pour permettre la réinitialisation du mot de passe.</p>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" class="w-full"/>
      </div>
      <span class="space-x-2 float-right">
        <button type="submit" :disabled="buttonClicked"><i class="fas fa-key"></i> Réinitialiser</button>
      </span>
    </form>
</template>

<script>
  export default {
    data() {
      return {
        email:"",
        buttonClicked:false
      }
    },
    methods: {
      async sendMail(){
        this.buttonClicked = true
        let r = await this.$store.dispatch("resetPassword",{email:this.email})
        console.log(r)
      }
    }
  }
</script>
