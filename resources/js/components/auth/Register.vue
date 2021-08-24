<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="register"
  >
    <h1>Création de compte</h1>
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
        class="w-full"
        required
      >

      <label
        for="Nom"
        class=""
      ><h2>Nom ou pseudonyme :</h2></label>
      <input
        id="name"
        v-model="name"
        type="text"
        class="w-full"
        required
        minlength="3"
      >

      <label
        for="password"
        class=""
      ><h2>Mot de passe :</h2></label>
      <input
        id="password"
        v-model="password"
        type="password"
        class="w-full"
        minlength="8"
        required
      >

      <label
        for="passwordConfirm"
        class=""
      >Confirmation :</label>
      <input
        id="passwordConfirm"
        ref="passwordConfirm"
        v-model="passwordConfirm"
        type="password"
        class="w-full"
        required
        @change="verifConfirm"
      >
    </div>

    <span class="space-x-2 float-right">
      <button
        type="submit"
        :disabled="buttonClicked"
      ><i class="fas fa-user-plus" /> S'enregistrer</button>
    </span>
  </form>
</template>

<script>
export default {
  data () {
    return {
      email: '',
      name: '',
      password: '',
      passwordConfirm: '',
      buttonClicked: false
    }
  },
  methods: {
    verifConfirm () {
      this.$refs.passwordConfirm.setCustomValidity(
        this.password === this.passwordConfirm ? '' : 'Confirmation du mot de passe incorrecte.')
    },
    async register () {
      this.buttonClicked = true
      this.$notify('Création du compte utilisateur ...')
      try {
        const response = await this.$store.dispatch('auth/register', { email: this.email, name: this.name, password: this.password })
        if (response.message) {
          this.$notify(response.message)
        }
        if (response.status) {
          await this.$store.dispatch('auth/loginWithCredentials', { email: this.email, password: this.password })
          this.$router.push({ name: 'Home' })
          this.$notify('')
          return
        }
      } catch (e) {
        this.buttonClicked = false

        if (!e.handled) {
          this.$notify(`Exception non répertoriée : ${e.message}`)
        }
      }
    }
  }
}
</script>
