import axios from 'axios'

let getToken = async function(credentials){
  let response = await axios.post('/oauth/token',{
    grant_type: "password",
    client_id: "1",
    client_secret: "Gl4z1uob1D1spvWZ7llFTh5orQUSF5HvzN9LunKO",
    username: credentials.email,
    password: credentials.password,
    scope: "*"
  })

  if(credentials.remember){
    localStorage.setItem('access_token', response.data.access_token)
    localStorage.setItem('expires_in', response.data.expires_in)
    localStorage.setItem('refresh_token', response.data.refresh_token)
  }

  axios.defaults.headers.common['Authorization'] = 'Bearer ' + response.data.access_token;

  return true
}

let useToken = function(){
  let token = localStorage.getItem('access_token')
  if(token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    return true;
  }
  return false;
}

let logout = function(){
  delete axios.defaults.headers.common['Authorization'];
  localStorage.removeItem('access_token')
  localStorage.removeItem('expires_in')
  localStorage.removeItem('refresh_token')
}

let getUser = function(){
  return axios.get('/api/v1/auth/current').then(response => response.data)
}

let resetPassword = function(data){
  return axios.post('/api/v1/auth/resetPassword',data).then(response => response.data)
}

let register = function(data){
  return axios.post('/api/v1/auth/register',data).then(response => response.data)
}

export default {getToken, useToken, logout, getUser, resetPassword, register}
