<template>
    <form @submit.prevent="login" class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow">
      <h1>Connexion</h1>
      <div>
        <label for="email" class=""><h2>Email :</h2></label>
        <input type="email" v-model="email" id="email" class="w-full"/>
      </div>
      <div>
        <label for="password" class=""><h2>Mot de passe :</h2></label>
        <input type="password" v-model="password" id="password" class="w-full"/>
      </div>

      <div>
        <input v-model="remember" type="checkbox" :id="remember" />
        <label :for="remember" class="ml-1"> Retenir le mot de passe</label>
      </div>
      <hr class="my-2 md:min-w-full" />
      <span class="space-x-2 float-right">
        <button type="submit" :disabled="clicked">Connexion</button>
      </span>
      <router-link :to="{name:'Register'}" class="block text-sm">
        <i class="fas fa-user-plus"></i> Créer un compte
      </router-link>
      <router-link :to="{name:'RecoverPassword'}" class="block text-sm">
        <i class="fas fa-user-plus"></i> Mot de passe oublié
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
        buttonClicked:false
      }
    },
    methods: {
      async login(){
        this.buttonClicked = true
        await this.$store.dispatch("login",{email:this.email, password:this.password, remember: this.remember})
        this.$router.push({name:'Home'})
      }
    }
  }
</script>
