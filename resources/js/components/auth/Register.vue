<template>
    <form @submit.prevent="register" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Création de compte</h1>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" class="w-full" v-focus required/>

        <label for="Nom" class=""><h2>Nom ou pseudonyme :</h2></label>
        <input type="text" v-model="name" id="name" class="w-full" required minlength="3"/>

        <label for="password" class=""><h2>Mot de passe :</h2></label>
        <input type="password" v-model="password" id="password" class="w-full" minlength="8" required/>

        <label for="passwordConfirm" class="">Confirmation :</label>
        <input type="password" v-model="passwordConfirm" id="passwordConfirm" class="w-full" required ref="passwordConfirm" @change="verifConfirm"/>
      </div>

      <span class="space-x-2 float-right">
        <button type="submit" :disabled="buttonClicked"><i class="fas fa-user-plus"></i> S'enregistrer</button>
      </span>
    </form>
</template>

<script>
  export default {
    data() {
      return {
        email:"",
        name:"",
        password:"",
        passwordConfirm:"",
        buttonClicked:false,
      }
    },
    methods: {
      verifConfirm(){
        this.$refs.passwordConfirm.setCustomValidity(
          this.password == this.passwordConfirm ? "" : "Confirmation du mot de passe incorrecte.")
      },
      async register(){
        this.buttonClicked = true
        this.$notify("Création du compte utilisateur ...")
        try{
          let response = await this.$store.dispatch("auth/register",{email:this.email, name:this.name, password:this.password})
          if(response.message) {
            this.$notify(response.message)
          }
          if(response.status){
            await this.$store.dispatch("auth/loginWithCredentials",{email:this.email, password:this.password})
            this.$router.push({name:'Home'})
            this.$notify("")
            return
          }
        } catch(e) {
          this.buttonClicked = false

            if(!e.handled) {
              this.$notify(`Exception non répertoriée : ${e.message}`)
            }
        }
      }
    }
  }
</script>
