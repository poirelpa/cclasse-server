<template>
  <form
    class="bg-white shadow rounded p-4 mx-auto max-w-2xl flex-grow"
    @submit.prevent="submit"
  >
    <h1>Récupération de mot de passe</h1>
    <p>Vous recevrez un code de réinitialisation du mot de passe par email.</p>
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
    </div>
    <div
      v-if="step == 2"
      class="mt-4"
    >
      <p>Veuillez copier ci-dessous le code reçu par mail. Attention, ce code n'est valable qu'une heure.</p>

      <label
        for="token"
        class=""
      ><h2>Code de réinitialisation :</h2></label>
      <input
        id="token"
        v-model="token"
        type="text"
        class="w-full"
        required
      >

      <label
        for="password"
        class=""
      ><h2>Nouveau Mot de passe :</h2></label>
      <input
        id="password"
        v-model="password"
        type="password"
        class="w-full"
        required
        minlength="8"
        @change="verifConfirm"
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
        v-if="step == 1"
        type="submit"
        :disabled="buttonClicked"
      ><i class="fas fa-envelope" /> Envoyer le code</button>
      <button
        v-if="step == 2"
        type="submit"
        :disabled="buttonClicked"
      ><i class="fas fa-key" /> Réinitialiser le mot de passe</button>
    </span>
  </form>
</template>

<script>
export default {
  data () {
    return {
      email: '',
      token: '',
      password: '',
      passwordConfirm: '',
      step: 1,
      buttonClicked: false
    }
  },
  methods: {
    verifConfirm () {
      this.$refs.passwordConfirm.setCustomValidity(
        this.password === this.passwordConfirm ? '' : 'Confirmation du mot de passe incorrecte.')
    },

    async submit () {
      this.$notify('')
      this.buttonClicked = true

      try {
        const result = await this.$store.dispatch('auth/resetPassword', { email: this.email, token: this.token, password: this.password })
        if (this.step === 1) {
          this.step = 2
          this.buttonClicked = false
        }
        if (result.message) {
          this.$notify(result.message)
        }
      } catch (e) {
        if (!e.handled) {
          this.$notify(`Exception non répertoriée : ${e.message}`)
        }
      }
      this.buttonClicked = false
    }
  }
}
</script>
