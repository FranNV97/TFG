/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('profile', require('./components/profile.vue').default);
Vue.component('issues', require('./components/issues.vue').default);
Vue.component('processes', require('./components/processes.vue').default);
Vue.component('process', require('./components/process.vue').default);
Vue.component('tasks', require('./components/tasks.vue').default);
Vue.component('documents', require('./components/documents.vue').default);
Vue.component('objectives', require('./components/objectives.vue').default);
Vue.component('fivewhys', require('./components/fivewhys.vue').default);
Vue.component('notifications', require('./components/notifications.vue').default);
Vue.component('valueadded', require('./components/valueadded.vue').default);
Vue.component('incidences', require('./components/incidences.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
