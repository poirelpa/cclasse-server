import { createStore } from 'vuex'
import auth from './auth.js'
import classes from './classes.js'
import utils from '../utils/utils.js'
// Create a new store instance.

export default createStore({
  modules: {
    auth:auth,
    classes:classes
  }
})
