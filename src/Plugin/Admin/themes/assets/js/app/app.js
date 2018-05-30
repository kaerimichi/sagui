const instance = axios.create({
  baseURL: 'http://192.168.99.100:8080/admin'
});

const router = new VueRouter({
    routes: [
      { path: '/register', component: Register, name: 'register' },
      { path: '/login', component: Login, name: 'login' },
      { path: '/', component: Dashboard, name: 'dashboard' },
    ],
})

const store = new Vuex.Store({
  state: {
    logged: false
  },
  mutations: {
    logged (state) {
      state.logged = true
    }
  },
  getters: {
    isLogged: state => {
        return state.logged
    }
  },
  actions: {
    attemptRegisterAdmin: (store, form) => {
      const params = new URLSearchParams();

      params.append('name', form.name);
      params.append('email', form.email);
      params.append('password', form.password);

      return axios.post('/admin/create', params)
    },
    attemptLogin: (store, form) => {
      const params = new URLSearchParams();

      params.append('email', form.email);
      params.append('password', form.password);

      return axios.post('/admin/authorize', params)
    }
  }
})

new Vue({
    el: '#app',
    components: {
        'login': Login,
        'register': Register,
        'dashboard': Dashboard
    },
    router: router,
    store: store
})