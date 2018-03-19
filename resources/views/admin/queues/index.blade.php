@extends('layouts.admin.master')

@section('content')
	
	<queue-monitoring inline-template>
		
		<div class="container-fluid" v-cloak>
			
			@pageTitle(['icon' => 'icon-flag'])
			Queues Monitoring
			@endpageTitle
			
			<div class="grad-error bdr-10 p-3 mb-4" v-if="redisErrors.exception">
				<p class="mb-0" v-text="redisErrors.message"></p>
				<p class="mb-0" v-text="'Exception: ' + redisErrors.exception"></p>
			</div>
			
			
			<!--- Statuses --->
		@include('admin.queues._partials.status-widgets')
		
		<!--- Workload --->
			<div class="card mt-4" v-if="workload.length">
				<div class="card-header ls-12 tt-u">Current Workload</div>
				<div class="table-responsive">
					<table class="table card-table table-hover mb-0">
						<thead>
						<tr>
							<th class="fsz-sm tt-u ls-12">Queue</th>
							<th class="fsz-sm tt-u ls-12">Processes</th>
							<th class="fsz-sm tt-u ls-12">Jobs</th>
							<th class="fsz-sm tt-u ls-12">Wait</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="queue in workload">
							<td>
								<span>@{{ queue.name | capitalize }}</span>
							</td>
							<td>@{{ queue.processes }}</td>
							<td>@{{ queue.length }}</td>
							<td>@{{ humanTime(queue.wait) }}</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			
			<!--- Workers --->
			<div class="card mt-5" v-for="worker in workers" :key="worker.name">
				<div class="card-header">@{{ worker.name }}</div>
				<div class="table-responsive">
					<table class="table card-table table-hover mb-0">
						<thead>
						<tr>
							<th class="fsz-sm tt-u ls-12">Supervisor</th>
							<th class="fsz-sm tt-u ls-12">Processes</th>
							<th class="fsz-sm tt-u ls-12">Queues</th>
							<th class="fsz-sm tt-u ls-12">Balancing</th>
						</tr>
						</thead>
						<tbody>
						<tr v-for="supervisor in worker.supervisors">
							<td class="ph2">
								<span class="fw7">@{{ superVisorDisplayName(supervisor.name, worker.name) }}</span>
							</td>
							<td>@{{ countProcesses(supervisor.processes) }}</td>
							<td>@{{ supervisor.options.queue }}</td>
							<td class="d-flex align-items-center">
                                <span v-if="supervisor.options.balance">
                                    @{{ supervisor.options.balance.charAt(0).toUpperCase() + supervisor.options.balance.slice(1) }}
                                </span>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		
		</div>
	
	</queue-monitoring>

@endsection
