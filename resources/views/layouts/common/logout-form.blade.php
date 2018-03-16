@auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;" ref="logoutForm">
        {{ csrf_field() }}
    </form>
@endauth