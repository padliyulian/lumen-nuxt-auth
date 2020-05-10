
export default {
  mode: 'universal',
  env: {
    baseUrl: process.env.BASE_URL || 'http://localhost:3000',
    apiUrl: process.env.API_URL || 'http://localhost:8000/api/v1',
    assetUrl: process.env.ASSET_URL || 'http://localhost:8000',
  },
  /*
  ** Headers of the page
  */
  head: {
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || 'esppd' },
      { hid: 'author', name: 'author', content: 'padliyulian.github.io' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' },
    ],
    // bodyAttrs: {
    //     class: 'hold-transition sidebar-mini'
    // }
  },
  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },
  /*
  ** Global CSS
  */
  css: [
    '@assets/scss/app.scss'
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    { src: '@plugins/bootstrap.js', ssr: false },
    { src: '@plugins/pusher.js', ssr: false },

  ],
  /*
  ** Nuxt.js dev-modules
  */
  buildModules: [
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/moment',
  ],
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
    baseURL: 'http://localhost:8000/api/v1',
  },

  auth: {
    redirect: {
        login: '/login',
        logout: '/login',
        callback: '/login',
        home: '/'
    },

    strategies: {
        local: {
            endpoints: {
                login: { url: '/login', method: 'post', propertyName: 'data.token' },
                logout: { url: '/logout', method: 'post' },
                user: { url: '/login/user', method: 'get', propertyName: false },
            },
            tokenRequired: true,
            tokenType: 'Bearer',
            autoFetchUser: true
        }
    },
  },

  router: {
    middleware: ['auth']
  },

  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend (config, ctx) {
    }
  }
}
