import Vue from 'vue'

class Event {

    constructor() {
        this.vue = new Vue();
    }

    /**
     * Fire an event
     *
     * @param {string} event
     * @param {mix} data
     */
    fire(event, data = null) {
        this.vue.$emit(event, data);
    }

    /**
     * Listen For the event and trigger callback
     *
     * @param {string} event
     * @param {function} callback
     */
    listen(event, callback) {
        this.vue.$on(event, callback);
    }

}

export default Event;