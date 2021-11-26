/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

/**
 * Vista Categoría
 */
 $(".fa-compress-cat").on('click', function(e) {
    $('.cardBodyCategory').css('display','block');
    $(".fa-compress-arrows-alt-cat").css('display','block');
    $(".fa-compress-cat").css('display','none');
});

$(".fa-compress-arrows-alt-cat").on('click', function(e) {
    $('.cardBodyCategory').css('display','none');
    $(".fa-compress-arrows-alt-cat").css('display','none');
    $(".fa-compress-cat").css('display','block');
});

/**
 * Vista Cofiguración
 */
 $(".showSavings").on('click', function(e) {
    $('.cardBodyConfig').css('display','block');
    $(".hideSavings").css('display','block');
    $(".showSavings").css('display','none');
});

$(".hideSavings").on('click', function(e) {
    $('.cardBodyConfig').css('display','none');
    $(".showSavings").css('display','block');
    $(".hideSavings").css('display','none');
});