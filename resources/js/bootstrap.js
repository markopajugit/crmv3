/**
 * Load jQuery and make it available globally
 */
window.$ = window.jQuery = require('jquery');

/**
 * Load jQuery UI
 */
require('jquery-ui');

/**
 * Load Lodash
 */
window._ = require('lodash');

/**
 * Load Bootstrap 5
 * Bootstrap 5 requires Popper.js which is already included
 */
try {
    require('bootstrap');
} catch (e) {
    console.warn('Bootstrap failed to load:', e);
}

/**
 * Load CoreUI
 */
try {
    require('@coreui/coreui');
} catch (e) {
    console.warn('CoreUI failed to load:', e);
}

/**
 * Font Awesome is loaded via SCSS in app.scss
 */

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
