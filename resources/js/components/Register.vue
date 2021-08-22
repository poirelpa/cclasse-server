<template>
    <form @submit.prevent="register" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <notification :message="message"/>
      <h1>Création de compte</h1>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" class="w-full" required/>

        <label for="Nom" class=""><h2>Nom ou pseudonyme :</h2></label>
        <input type="text" v-model="name" id="name" class="w-full" required minlength="3"/>

        <label for="password" class=""><h2>Mot de passe :</h2></label>
        <input type="password" v-model="password" id="password" class="w-full" minlength="8" required/>

        <label for="passwordVerif" class="">Confirmation :</label>
        <input type="password" v-model="passwordVerif" id="passwordVerif" class="w-full" required/>
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
        passwordVerif:"",
        buttonClicked:false,
        message:""
      }
    },
    methods: {
      async register(){
        this.message = ""
        if(this.password != this.passwordVerif) {
          this.message = "Confirmation du mot de passe incorrecte"
          return
        }
        this.buttonClicked = true
        try{
          let response = await this.$store.dispatch("register",{email:this.email, name:this.name, password:this.password})
          if(response.message) {
            this.message = response.message
            if(response.status){
              let result = await this.$store.dispatch("loginWithCredentials",{email:this.email, password:this.password})
              this.$router.push({name:'Home'})
            }
          }
          this.$router.push({name:'Home'})
        } catch(e) {
          this.buttonClicked = false
          if(e?.response?.data?.errors) {
            _.each(e.response.data.errors,(errors,field) => {
              _.each(errors, error => {
                this.message += error + "\n"
              });
            });
          } else {
            this.message = `Exception non répertoriée : ${e.message}`
          }
        }
      }
    }
  }
</script>
