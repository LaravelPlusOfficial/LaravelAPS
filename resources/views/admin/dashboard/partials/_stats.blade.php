<div class="col-lg-4 col-md-4 col-sm-4 mb-4">
	<div class="card">
		<div class="card-body">
			<div class="">
				<h5 class="card-title label">Posts</h5>
				<span class="stat-value fsz-md ls-12 tt-u">
					{{ $dashboardUser->posts_count }}
				</span>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-4 col-md-4 col-sm-4 mb-4">
	<div class="card">
		<div class="card-body">
			<div class="">
				<h5 class="card-title label">Comments</h5>
				<span class="stat-value fsz-md ls-12 tt-u">{{ $dashboardUser->comments_count }}</span>
			</div>
		</div>
	</div>
</div>

<div class="col-lg-4 col-md-4 col-sm-4 mb-4">
	<div class="card">
		<div class="card-body">
			<div class="">
				<h5 class="card-title label">Media</h5>
				<span class="stat-value fsz-md ls-12 tt-u">{{ $dashboardUser->media_count }}</span>
			</div>
		</div>
	</div>
</div>