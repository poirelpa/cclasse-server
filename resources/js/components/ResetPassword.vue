<template>
    <form @submit.prevent="submit" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Récupération de mot de passe - étape {{}}</h1>
      <p>Un mail vous sera envoyé à l'adresse indiquée, avec un code de réinitialisation du mot de passe.</p>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" class="w-full"/>
      </div>
      <div v-if="step == 2">
        <p>Veuillez copier ci-dessous le code reçu par mail. Attention, ce code n'est valable qu'une heure. Ne fermez pas cette fenêtre.</p>
        <label for="token" class=""><h2>Code de réinitialisation :</h2></label>
        <input type="text" v-model="token" id="token" class="w-full"/>
      </div>
      <div v-if="step == 2">
        <p>Veuillez choisir un nouveau mot de passe</p>
        <label for="password" class=""><h2>Mot de passe :</h2></label>
        <input type="password" v-model="password" id="password" class="w-full"/>
        <input type="password" v-model="passwordVerif" class="w-full"/>
      </div>
      <span class="space-x-2 float-right">
        <button v-if="step == 1" type="submit" :disabled="buttonClicked"><i class="fas fa-envelope"></i> Envoyer le code</button>
        <button v-if="step == 2" type="submit" :disabled="buttonClicked"><i class="fas fa-key"></i> Réinitialiser le mot de passe</button>
      </span>
    </form>
</template>

<script>
  export default {
    data() {
      return {
        email:"",
        token:"",
        password:"",
        passwordVerif:"",
        step:1,
        buttonClicked:false
      }
    },
    methods: {
      async submit(){
        this.buttonClicked = true

        let result = await this.$store.dispatch("resetPassword",{email:this.email, token:this.token, password:this.password})
        if(this.step == 1){
          console.log(result)
          this.step ++
        }
        this.buttonClicked = false
      }
    }
  }
</script>
