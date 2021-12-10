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

/**
 * Vista Salario
 */
 $(".fa-compress-sal").on('click', function(e) {
    $('.cardBodySalary').css('display','block');
    $(".fa-compress-arrows-alt-sal").css('display','block');
    $(".fa-compress-sal").css('display','none');
});

$(".fa-compress-arrows-alt-sal").on('click', function(e) {
    $('.cardBodySalary').css('display','none');
    $(".fa-compress-arrows-alt-sal").css('display','none');
    $(".fa-compress-sal").css('display','block');
});

$(".fa-compress-salMonth").on('click', function(e) {
    $('.cardBodySalaryMonth').css('display','block');
    $(".fa-compress-arrows-alt-salMonth").css('display','block');
    $(".fa-compress-salMonth").css('display','none');
});

$(".fa-compress-arrows-alt-salMonth").on('click', function(e) {
    $('.cardBodySalaryMonth').css('display','none');
    $(".fa-compress-arrows-alt-salMonth").css('display','none');
    $(".fa-compress-salMonth").css('display','block');
});

/**
 * Vista Home
 */
 $(".fa-compress-home").on('click', function(e) {
    $('.carBodyHome').css('display','block');
    $(".fa-compress-arrows-alt-home").css('display','block');
    $(".fa-compress-home").css('display','none');
});

$(".fa-compress-arrows-alt-home").on('click', function(e) {
    $('.carBodyHome').css('display','none');
    $(".fa-compress-arrows-alt-home").css('display','none');
    $(".fa-compress-home").css('display','block');
});

/**
 * Vista Income
 */
 $(".fa-compress-income").on('click', function(e) {
    $('.cardBodyIncome').css('display','block');
    $(".fa-compress-arrows-alt-income").css('display','block');
    $(".fa-compress-income").css('display','none');
});

$(".fa-compress-arrows-alt-income").on('click', function(e) {
    $('.cardBodyIncome').css('display','none');
    $(".fa-compress-arrows-alt-income").css('display','none');
    $(".fa-compress-income").css('display','block');
});

/**
 * Vista Expense
 */
 $(".fa-compress-expense").on('click', function(e) {
    $('.cardBodyExpense').css('display','block');
    $(".fa-compress-arrows-alt-expense").css('display','block');
    $(".fa-compress-expense").css('display','none');
});

$(".fa-compress-arrows-alt-expense").on('click', function(e) {
    $('.cardBodyExpense').css('display','none');
    $(".fa-compress-arrows-alt-expense").css('display','none');
    $(".fa-compress-expense").css('display','block');
});