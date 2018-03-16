@php($embed = isset($embed) ? ':embed=' . $embed : '' )

<media-library type="embed"
               media-url="{{ route('admin.media.index') }}"
               {{ $embed  }}
               inline-template>

    <div class="mlib-wrap"
         :class="{ 'no-embed' : ! embed }"
         v-if="show"
         v-cloak>

        <div class="mlib bgc-white card">

            <div class="card-header" style="border-bottom: none;">

                <div class="d-f jc-sb">
                    
                    <div class="d-f ai-c">
                        <h2 class="fsz-md lh-1 tt-u ls-12 mb-0">Media</h2>
                        <p class="fsz-sm lh-1 tt-u ls-12 text-muted mb-0 ml-2" v-text="'('+paginate.total+')'"></p>
                    </div>

                    <div class="d-f ai-c">

                        <button type="button"
                                v-show="!uploading"
                                @click.prevent="uploading = true, editing = false"
                                class="bg-n p-0 m-0 lh-1 bd-n fill-gray fill-primary-hv cur-p o-n-fc">
                            <vue-svg name="icon-upload" square="20"></vue-svg>
                        </button>

                        <button type="button"
                                v-show="uploading && this.mediaItems.length > 0"
                                @click.prevent="uploading = false"
                                class="bg-n p-0 m-0 lh-1 bd-n fill-gray fill-primary-hv cur-p o-n-fc">
                            <vue-svg name="icon-x-circle" square="20"></vue-svg>
                        </button>

                    </div>
                    
                </div>

            </div>

            <div class="card-body ovf-y-a mh-400px">

                <!--- Uploader --->
                <file-uploader upload-url="{{ route('admin.media.store') }}"
                               @upload-complete="filesUploaded"
                               v-if="uploading"
                               :reset="uploading"
                ></file-uploader>

                <!--- Media list --->
                <div class="mlib-list d-f fxw-w ovf-y-a" v-show="!uploading && !editing">

                    <div class="d-f fxw-w w-100">

                        <div v-for="(item, index) in mediaItems"
                             class="d-f fxd-c mlib-item col-lg-2 col-md-3 col-sm-3 mb-4">

                            <div class="w-100">
                                <img :src="item.variations.thumbnail.path" class="w-100 mw-100">
                            </div>

                            <div class="bgc-gray-light ta-c">

                                <p class="mb-0 mt-1">@{{ item.name | substr(30) }}</p>

                                <div class="d-f jc-c">
                                    <p class="m-0 fsz-xs tt-u ls-12" v-text="item.file_type"></p>
                                    <p class="fsz-xs m-0 ml-2">- &nbsp; @{{ sizeOnDisk(item) | prettyBytes }}</p>
                                </div>

                                <div class="mt-2 mb-2">

                                    <button type="button"
                                            @click="select(item)"
                                            v-if="selection.type"
                                            class="bg-n lh-1 m-0 p-0 bd-n cur-p fill-primary-hv fill-gray mr-2">
                                        <vue-svg name="icon-check-circle" square="16"></vue-svg>
                                    </button>

                                    <button type="button"
                                            @click="media = item, editing = true"
                                            class="bg-n lh-1 m-0 p-0 bd-n cur-p fill-primary-hv fill-gray">
                                        <vue-svg name="icon-eye" square="16"></vue-svg>
                                    </button>

                                    <!--- Delete button --->
                                    <button type="button"
                                            @click="deleteMedia(item.id)"
                                            class="bg-n lh-1 m-0 p-0 bd-n ml-2 cur-p fill-primary-hv fill-gray">
                                        <vue-svg name="icon-delete" square="16"></vue-svg>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                </div>

                <!--- Editing --->
                <div class="mlib-edit" v-if="editing">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-4 mb-4">
                                <img :src="media.variations.thumbnail.path" class="w-100 mw-100">

                                <div class="d-f jc-c mt-4 mb-2">
                                    <button type="button"
                                            @click="editing = false"
                                            class="bg-n lh-1 m-0 p-0 bd-n cur-p fill-primary-hv fill-gray">
                                        <vue-svg name="icon-x-circle" square="24"></vue-svg>
                                    </button>

                                    <button type="button"
                                            @click="deleteMedia(media.id)"
                                            class="bg-n lh-1 m-0 p-0 bd-n ml-4 cur-p fill-primary-hv fill-gray">
                                        <vue-svg name="icon-delete" square="24"></vue-svg>
                                    </button>
                                </div>

                            </div>
                            <div class="col-lg-8">

                                <h4 class="fsz-sm tt-u ls-12 mb-0 c-gray">Media Detail</h4>
                                <hr class="mt-1">

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">Uploaded On</h5>
                                    <p>@{{ media.created_at }}</p>
                                </div>

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">File type</h5>
                                    <p>@{{ media.file_type }}</p>
                                </div>

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">Mime type</h5>
                                    <p>@{{ media.mime_type }}</p>
                                </div>

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">Name</h5>
                                    <p>@{{ media.name }}</p>
                                </div>

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">Path</h5>
                                    <p>@{{ media.path }}</p>
                                </div>

                                <div class="">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-gray">Total size on disk</h5>
                                    <p>@{{ sizeOnDisk(media) | prettyBytes }}</p>
                                </div>

                                <h4 class="fsz-sm tt-u ls-12 mb-0 c-gray">Available Variations</h4>
                                <hr class="mt-1">

                                <div class="mb-4" v-for="(value, type, index) in media.variations">
                                    <h5 class="fsz-xs tt-u ls-12 mb-0 c-blue">@{{ type }}</h5>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Size:</span>
                                        @{{ value.size | prettyBytes }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Width:</span>
                                        @{{ value.width + 'px' }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Height:</span>
                                        @{{ value.height + 'px'}}
                                    </p>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Path:</span>
                                        @{{ value.path }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Type:</span>
                                        @{{ value.type }}
                                    </p>
                                    <p class="mb-0">
                                        <span class="fsz-xs tt-u ls-12 c-gray">Mime Type:</span>
                                        @{{ value.mime }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer d-f ac-c" v-show="!embed">
                <button type="button"
                        v-show="!embed"
                        @click.prevent="hide()"
                        class="d-f ac-c ai-c bg-n p-0 m-0 lh-1 bd-n c-gray c-primary-hv fill-gray fill-primary-hv cur-p o-n-fc">
                    <vue-svg name="icon-x-circle" square="20"></vue-svg>
                    <span class="fsz-xs tt-u ls-12 ml-1">Close</span>
                </button>
            </div>

        </div>

    </div>


</media-library>