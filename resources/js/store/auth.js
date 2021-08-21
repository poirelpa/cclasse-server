import axios from 'axios'

let getToken = async function(credentials){
  let response = await axios.post('/oauth/token',{
    grant_type: "password",
    client_id: "2",
    client_secret: "EEeXAjmAuUA5r93AJI2THCUdDil4WZkUwCll28uE",
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
  return axios.get('/api/user').then(response => {
    return response.data
  })
}

export default {getToken, useToken, logout, getUser}
