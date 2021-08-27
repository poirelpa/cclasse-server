<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="login"
  >
    <h1>Connexion</h1>
    <div>
      <label
        for="email"
        class=""
      ><h2>Email :</h2></label>
      <input
        id="email"
        v-model="email"
        v-focus
        type="email"
        required
        class="w-full"
      >
    </div>
    <div>
      <label
        for="password"
        class=""
      ><h2>Mot de passe :</h2></label>
      <input
        id="password"
        v-model="password"
        type="password"
        required
        class="w-full"
      >
    </div>

    <div>
      <input
        :id="remember"
        v-model="remember"
        type="checkbox"
      >
      <label
        :for="remember"
        class="ml-1"
      > Retenir le mot de passe</label>
    </div>
    <hr class="my-2 md:min-w-full">
    <span class="space-x-2 float-right">
      <button
        type="submit"
        :disabled="buttonClicked"
      ><i class="fas fa-sign-in-alt" /> Connexion</button>
    </span>
    <router-link
      :to="{name:'Register'}"
      class="block text-sm"
    >
      <i class="fas fa-user-plus" /> Créer un compte
    </router-link>
    <router-link
      :to="{name:'ResetPassword'}"
      class="block text-sm"
    >
      <i class="fas fa-key" /> Mot de passe oublié
    </router-link>
  </form>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  data () {
    return {
      email: '',
      password: '',
      remember: false,
      buttonClicked: false
    }
  },
  methods: {
    ...mapActions('auth', ['loginWithCredentials']),
    ...mapActions('classes', {
      loadClasses: 'load'
    }),
    ...mapActions('levels', {
      loadLevels: 'load'
    }),
    async login () {
      this.buttonClicked = true
      this.$notify('Connexion en cours')
      try {
        await this.loginWithCredentials({
          email: this.email,
          password: this.password,
          remember: this.remember
        })
        await Promise.all([
          this.loadClasses(),
          this.loadLevels()
        ])
        this.$router.push(this.$route.query.redirect ?? { name: 'Home' })

        this.$notify('')
      } catch (e) {
        this.buttonClicked = false

        if (e.handled) return

        if (e?.response?.data?.error === 'invalid_grant') {
          this.$notify('Identifiants incorrects')
        } else {
          this.handleError(e)
        }
      }
    }
  }
}
</script>
