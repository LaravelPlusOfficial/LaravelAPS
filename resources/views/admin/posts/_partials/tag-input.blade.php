<tag-input tag-url="{{ route('admin.tag.index') }}"
           @tag-selected="tagSelected"
           @tag-removed="tagRemoved"
           :tags-data="postData ? postData.tags : []"
           inline-template>
    <div class="" v-cloak>

        <div class="selected-tags d-f jc-fs fxw-w pb-2">
            <div class="selected-tag d-f jc-c ac-c ai-c bd"
                 v-for="(selectedTag, index) in tags"
                 v-if="tags.length > 0"
            >
                <p class="mb-0" v-text="selectedTag.name"></p>
                <button type="button" class="tag-close" @click="removeTag(index, selectedTag)">x</button>
            </div>
            <p class="mb-0" v-if="tags.length <= 0 ">No tags have been added yet</p>
        </div>

        <div class="tag-input-wrap mb-2">
            <input type="text"
                   class="form-control tag-input"
                   :class="{ 'right-space' : addingToDatabase }"
                   placeholder="Add tag..."
                   v-model="tag"
                   @blur="reset()"
                   @keydown.up="focusSuggestion('up')"
                   @keydown.down="focusSuggestion('down')"
                   @keydown.enter="addTag()"
                   @keydown.delete="searchTags()"
                   @keydown.esc="reset()"
                   @keyup="searchTags()">

            <fade-transition>
                <div class="tag-loader" v-if="addingToDatabase">
                    <loader height="12px" width="2px"></loader>
                </div>
            </fade-transition>

            <ul class="tags-suggestion" v-if="suggestions.length > 0">
                <li v-for="(suggestion, index) in suggestions"
                    v-text="suggestion.name"
                    :class="{ active: focusedIndex === index }"
                ></li>
            </ul>
        </div>

        <fade-transition>
            <p class="c-red mb-0 ml-2 mr-2 mb-2 ta-c fsz-sm" v-show="tagAddedAlready">
                Tag is already added
            </p>
        </fade-transition>

    </div>
</tag-input>