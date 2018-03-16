import _ from 'lodash'
window._ = _

// Import Popper.js
import Popper from 'popper.js/dist/umd/popper.js'
window.Popper = Popper

try {

    window.$ = window.jQuery = require('jquery')

    // MetisMenu
    window.$.metisMenu = require('metismenu')

    require('bootstrap')

} catch (e) {}

// Moment
import moment from 'moment'
window.moment = moment

/*** VUE ***/
window.Vue = require('vue')

Vue.config.productionTip = false

Array.prototype.flatResult = function (inputArray, ischild) {

    ischild = ischild || false;

    var i = 0, len = inputArray.length;

    for (i = 0; i < len; i++) {
        this.push(inputArray[i]);

        if (inputArray[i].children && typeof inputArray[i].children === typeof []) {
            this.flatResult(inputArray[i].children, true);
        }
    }

    if(ischild === false)
    {
        return this;
    }
};


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/*=== Events ===*/
import Event from '../common/Event';
window.Event = new Event();

/*** Flash ***/
window.flash = function (message, level = 'success') {
    window.Event.fire('flash', { message, level });
};

/*=== Forms ===*/
import Form from '../common/Form';
window.Form = Form;