import './bootstrap';

const feather = require('feather-icons');
feather.replace();

import Swal from 'sweetalert2/dist/sweetalert2.js'
import flatpickr from "flatpickr";
import { Polish } from 'flatpickr/dist/l10n/pl.js';

window.Swal = Swal;
window.flatpickr = flatpickr;
import 'sweetalert2/src/sweetalert2.scss'

import jQuery from 'jquery';
window.jQuery = jQuery;
window.$ = jQuery;

$(function () {
    flatpickr(".flatpickr", {
        "locale": "pl"
    });

    flatpickr(".flatpickr-range", {
        mode: "range",
        "locale": "pl"
    });
});