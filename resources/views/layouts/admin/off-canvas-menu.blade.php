@foreach($items as $item)

    @php( $hasChildren = $item->hasChildren() ? true : false )

    <li class="nav-item {{ $hasChildren ? ' has-submenu ' : '' }}{{ $item->isActive ? ' is-active ' : '' }}">
        <a class="nav-link d-f ai-c" href="{!! $item->url() !!}">
           {!! $item->title !!}
        </a>

        @if($hasChildren)
            <ul class="submenu">
                @include('layouts.admin.off-canvas-menu', ['items' => $item->children() ])
            </ul>
        @endif

    </li>

@endforeach