import axios from 'axios';
window.axios = axios;

window.$ = window.jQuery = require('jquery')

require('bootstrap');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
