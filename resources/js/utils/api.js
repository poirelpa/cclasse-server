import axios from 'axios';
import config from '../config.js'
import store from '../store/index.js'
import router from '../router'

const api = axios.create({
  baseURL: config.API_URL,
  headers: {
    Accept: "application/json",
    "Content-Type": "application/json"
  }
});

api.interceptors.response.use(null, error => {

  switch (error.response.status) {

    case 401:
      store.dispatch('notify','Connexion nécessaire.')
      router.push('/login',{params:{redirect:router.currentRoute.path}})
      error.handled = true
      break
    case 403:
      store.dispatch('notify','Accès refusé.')
      error.handled = true
      break
    case 404:
      store.dispatch('notify','Ressource inexistante.')
      error.handled = true
      break
    case 422:
      if(error?.response?.data?.errors) {
        store.dispatch('notify',error)
        error.handled = true
      }
      break
  }
  return Promise.reject(error);
});

export default api;
