import './bootstrap';

const feather = require('feather-icons');
feather.replace();

import Swal from 'sweetalert2/dist/sweetalert2.js'
window.Swal = Swal;
import 'sweetalert2/src/sweetalert2.scss'

import jQuery from 'jquery';
window.jQuery = jQuery;
window.$ = jQuery;
