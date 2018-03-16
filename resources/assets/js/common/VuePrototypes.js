Vue.prototype.$confirm = (message) => {
    return new Promise((resolve, reject) => {
        Event.fire("confirm", {
            message: message,
            resolve: resolve,
            reject: reject
        })
    })
}

import normalizeWhitespace from './normalize-whitespace'

Vue.prototype.$normalizeWhitespace = () => {
    return new normalizeWhitespace()
}

// Authorize user
import authorizations from './Authorizations'

Vue.prototype.authorize = function (...params) {

    if (! window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;
