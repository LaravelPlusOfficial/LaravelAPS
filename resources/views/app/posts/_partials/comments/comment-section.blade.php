<comment-system
		comment-url="{{ route('comment.store') }}"
		:initial-comments-count="{{ $post->comments_count }}"
		:post-id="{{ $post->id }}"
></comment-system>

@push('footer-scripts-prepend')
	
	<script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer>
	</script>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.js"
	        integrity="sha256-6HjuXHkd/YgPuxRCgDxCKyTGtPnEXeHmMQTF13SJBJo=" crossorigin="anonymous"></script>

@endpush

@push('head-styles')
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.1/trix.css"
	      integrity="sha256-313zaDOgbPqoi9UeQp/K23/Rw3EjeGlsRUFTV9BLt5Y=" crossorigin="anonymous"/>

@endpush