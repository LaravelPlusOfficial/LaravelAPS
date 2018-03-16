import Errors from './Errors';
import axios from 'axios'

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors;
    }


    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }


    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }

        this.errors.clear();
    }

    /**
     * Send a GET request to the given URL.
     * .
     * @param {string} url
     */
    get(url) {
        return this.submit('get', url);
    }


    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     * @param config
     */
    post(url, config = {}) {
        return this.submit('post', url, config);
    }


    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     * @param config
     */
    patch(url, config = {}) {
        return this.submit('patch', url, config);
    }


    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     * @param config
     */
    delete(url, config = {}) {
        return this.submit('delete', url, config);
    }


    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     * @param config
     */
    submit(requestType, url, config = {}) {

        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data(), config)
                .then(response => {
                    this.onSuccess(response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    error.response.data.errors ? this.onFail(error.response.data) : '';
                    reject(error)
                });
        });
    }


    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {
        this.reset();
    }


    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors.errors);
    }
}

export default Form;