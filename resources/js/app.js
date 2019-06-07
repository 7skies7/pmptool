
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router'; 
window.Vue = require('vue');
Vue.use(VueRouter)
import router from './routes';
import Form from './utilities/Form';
window.Form = Form;
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);
import { library } from '@fortawesome/fontawesome-svg-core'
import { faCoffee } from '@fortawesome/free-solid-svg-icons'
import { faClock } from '@fortawesome/free-solid-svg-icons'
import { faEdit } from '@fortawesome/free-solid-svg-icons'
import { faTrash } from '@fortawesome/free-solid-svg-icons'
import { faPlusSquare } from '@fortawesome/free-solid-svg-icons'
import { faUserCircle } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

library.add(faCoffee)
library.add(faClock)
library.add(faEdit)
library.add(faTrash)
library.add(faPlusSquare)
library.add(faUserCircle)
// import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import NProgress from 'nprogress';
import '../../node_modules/nprogress/nprogress.css'

//Vuetify plugin
import Vuetify from 'vuetify'
Vue.use(Vuetify)
import 'vuetify/dist/vuetify.min.css' 
//Vuetify plugin end



import Toasted from 'vue-toasted';
Vue.use(Toasted, 
	{
		position:'bottom-right',
		action : {
	        text : 'Cancel',
	        onClick : (e, toastObject) => {
	            toastObject.goAway(0);
	        }
	    }
})

Vue.component('font-awesome-icon', FontAwesomeIcon)


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('dropdown', require('./components/Dropdown.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
router.beforeResolve((to, from, next) => {
  if (to.name) {
      NProgress.start()
  }
  next()
})

router.afterEach((to, from) => {
  NProgress.done()
})
const app = new Vue({
    el: '#app',
    router
});