import { mapActions } from 'vuex'

export default {
  methods: {
    ...mapActions({ $notify: 'notify' }),
    handleError (e) {
      if (e?.response?.data?.error) {
        console.error(e)
        this.$notify(`Erreur d'API non répertoriée : ${e.response.data.error}`)
      } else {
        console.error(e)
        this.$notify(`Exception non répertoriée : ${e.message}`)
      }
    }
  }
}
