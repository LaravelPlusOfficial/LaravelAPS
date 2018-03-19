<script>
    import _ from 'lodash'
    import moment from 'moment'

    export default {
        name: "queue-monitoring",

        data() {
            return {
                loadingStats: true,
                loadingWorkers: true,
                loadingWorkload: true,
                stats: {},
                workers: [],
                workload: [],
                redisConnected: false,
                redisErrors: {
                    message: null,
                    exception: null
                }
            }
        },

        mounted() {
            this.init()
        },

        methods: {

            init() {

                this.loadStats()

                this.loadWorkers()

                this.loadWorkload()

                this.refreshStatsPeriodically()

            },

            loadStats(reload = true) {

                if (reload) {
                    this.loadingStats = true;
                }

                axios.get('/horizon/api/stats')
                    .then(response => {
                        this.stats = response.data

                        if (_.values(response.data.wait)[0]) {
                            this.stats.max_wait_time = _.values(response.data.wait)[0];
                            this.stats.max_wait_queue = _.keys(response.data.wait)[0].split(':')[1];
                        }

                        this.redisConnected = true;
                        this.loadingStats = false
                    })
                    .catch(error => {
                        //Predis\Connection\ConnectionException
                        this.stats.status = 'error';
                        this.redisConnected = false;
                        this.redisErrors.message = error.response.data.message;
                        this.redisErrors.exception = error.response.data.exception;
                        this.loadingStats = false
                    });
            },

            /**
             * Load the workers stats.
             */
            loadWorkers(reload = true) {
                if (reload) {
                    this.loadingWorkers = true;
                }
                axios.get('/horizon/api/masters')
                    .then(response => {
                        this.workers = response.data;
                        this.loadingWorkers = false;
                    });
            },
            /**
             * Load the workload stats.
             */
            loadWorkload(reload = true) {
                if (reload) {
                    this.loadingWorkload = true;
                }
                axios.get('/horizon/api/workload')
                    .then(response => {
                        this.workload = response.data;
                        this.loadingWorkload = false;
                    });
            },


            refreshStatsPeriodically() {

                if (this.redisConnected) {
                    this.interval = setInterval(() => {
                        this.loadStats(false)
                        this.loadWorkers(false)
                        this.loadWorkload(false)
                    }, 5000);
                }

            },

            /**
             *  Count processes for the given supervisor.
             */
            countProcesses(processes) {
                return _.chain(processes).values().sum().value()
            },
            /**
             *  Format the Supervisor display name.
             */
            superVisorDisplayName(supervisor, worker) {
                return _.replace(supervisor, worker + ':', '');
            },

            /**
             *
             * @returns {string}
             */
            humanTime(time) {
                return moment.duration(time, "seconds").humanize().replace(/^(.)|\s+(.)/g, function ($1) {
                    return $1.toUpperCase();
                });
            }

        }


    }
</script>