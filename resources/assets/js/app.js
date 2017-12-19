// Application
var App = require('./core/Application.js');
App.init(config);
window.App = App;


// Socket.io
var io = require('socket.io-client');
// var socket = io(App.config.url + ':3000');
var socket = io(App.config.url + ':3000', {
  transports: ['websocket']
});

socket.on('connect', function() {
});


socket.on('disconnect', function() {
});

App.socket = socket;
// Socket.events
var Events = require('./events/events.js');

Events.observe();

// Vue js
var Vue = require('vue');
var VueResource = require('vue-resource');


// Components
var avatar = require('./components/avatar.vue');
var evtView = require('./components/event/event-view.vue');
var evtForm = require('./components/event/event-form.vue');
var evtList = require('./components/event/event-list.vue');
var alrt = require('./components/alert.vue');
var sidebar = require('./components/sidebar.vue');
var tabs = require('./components/tabs.vue');
var tab = require('./components/tab.vue');
var dropdown = require('./components/dropdown.vue');
var checkbox = require('./components/checkbox.vue');
var settings = require('./components/settings.vue');
var fileUploader = require('./components/file-uploader.vue');
var Pikaday = require('pikaday');

$(document).ready(function () {
    var dateInputs = Array.prototype.slice.call(document.querySelectorAll('.date'));
    dateInputs.forEach(function (input) {
        var pikaday = new Pikaday({field: input, format: 'YYYY-MM-DD HH:MM'});
    });
});

Vue.use(VueResource);
Vue.http.headers.common['X-CSRF-TOKEN'] = App.config.csrfToken;

Vue.component('avatar', avatar);
Vue.component('alert', alrt);
Vue.component('event-view', evtView);
Vue.component('event-form', evtForm);
Vue.component('event-list', evtList);
Vue.component('sidebar', sidebar);
Vue.component('tabs', tabs);
Vue.component('tab', tab);
Vue.component('dropdown', dropdown);
Vue.component('checkbox', checkbox);
Vue.component('settings', settings);
Vue.component('file-uploader', fileUploader);

new Vue({
    el: '#app'
});

