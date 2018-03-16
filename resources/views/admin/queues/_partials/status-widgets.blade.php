<div class="row">

    <!--- Status --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card"
             :class="{ 'bgc-green': stats.status == 'running', 'bgc-blue': stats.status == 'paused', 'bgc-gray-dark' :stats.status == 'inactive' }">
            <div class="card-body">
                <div class="" v-if="! loadingStats">
                    <h5 class="card-title label c-white">Status</h5>
                    <span class="stat-value c-white fsz-md ls-12 tt-u">
                                    @{{ {running: 'Active', paused: 'Paused', inactive:'Inactive'} [stats.status] }}
                                </span>
                </div>
                <div class="d-f jc-c" v-if="loadingStats">
                    <loader></loader>
                </div>
            </div>
        </div>
    </div>

    <!--- Jobs per minute --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Jobs per minute</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.jobsPerMinute }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Jobs past hour --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Jobs past hour</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.recentJobs }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Failed Jobs past hour --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Failed Jobs past hour</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.recentlyFailed }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Total Processes --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Total Processes</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.processes }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Max Wait Time --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Max Wait Time</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.max_wait_queue || '&nbsp;' }}
                                @{{ stats.max_wait_time ? humanTime(stats.max_wait_time) : '-' }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Max Runtime --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Max Runtime</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.queueWithMaxRuntime ? stats.queueWithMaxRuntime : '-' }}
                            </span>
            </div>
        </div>
    </div>

    <!--- Max Throughput --->
    <div class="col-lg-4 col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title label">Max Throughput</h5>
                <span class="stat-value fsz-md ls-12 tt-u">
                                @{{ stats.queueWithMaxThroughput ? stats.queueWithMaxThroughput : '-' }}
                            </span>
            </div>
        </div>
    </div>

</div>