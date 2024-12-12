import axios from 'axios';
window.axios = axios;

require('bootstrap');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
