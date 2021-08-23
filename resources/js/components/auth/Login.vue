<template>
    <form @submit.prevent="login" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Connexion</h1>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" v-focus required class="w-full"/>
      </div>
      <div>
        <label for="password" class=""><h2>Mot de passe :</h2></label>
        <input type="password" v-model="password" id="password" required class="w-full"/>
      </div>

      <div>
        <input v-model="remember" type="checkbox" :id="remember" />
        <label :for="remember" class="ml-1"> Retenir le mot de passe</label>
      </div>
      <hr class="my-2 md:min-w-full" />
      <span class="space-x-2 float-right">
        <button type="submit" :disabled="buttonClicked"><i class="fas fa-sign-in-alt"></i> Connexion</button>
      </span>
      <router-link :to="{name:'Register'}" class="block text-sm">
        <i class="fas fa-user-plus"></i> Créer un compte
      </router-link>
      <router-link :to="{name:'ResetPassword'}" class="block text-sm">
        <i class="fas fa-key"></i> Mot de passe oublié
      </router-link>
    </form>
</template>

<script>
  export default {
    data() {
      return {
        email:"",
        password:"",
        remember:false,
        buttonClicked:false,
      }
    },
    methods: {
      async login(){
        this.buttonClicked = true
        this.$notify("Connexion en cours")
        try {
          await this.$store.dispatch("auth/loginWithCredentials",{email:this.email, password:this.password, remember: this.remember})
          await this.$store.dispatch("classes/getClasses")

          this.$router.push(this.$route.query.redirect ?? {name:'Home'})

          this.$notify("")
        } catch(e) {
          this.buttonClicked = false

          if(e.handled) return

          if(e?.response?.data?.error == "invalid_grant") {
            this.$notify("Identifiants incorrects")
          } else if(e?.response?.data?.error) {
            console.error(e)
            this.$notify(`Erreur d'API non répertoriée : ${e.response.data.error}`)
          } else {
            console.error(e)
            this.$notify(`Exception non répertoriée : ${e.message}`)
          }
        }
      }
    }
  }
</script>
