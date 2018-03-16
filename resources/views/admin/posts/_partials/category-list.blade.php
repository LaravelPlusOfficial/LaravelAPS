<ul class="{{ isset($classes) ? $classes : '' }}">

    @foreach($categories as $category)
        <li>
            <label class="form-radio tt-n fsz-sm">
                <input type="radio"
                       value="{{ $category['slug'] }}"
                       v-model="post.category_slug"
                       @change="post.errors.clear('category_slug')">
                <span>{{ $category['name'] }}</span>
            </label>

            @if( ! empty($category['children']) )

                @include('admin.posts._partials.category-list', ['categories' => $category['children'] ])

            @endif

        </li>
    @endforeach

</ul>