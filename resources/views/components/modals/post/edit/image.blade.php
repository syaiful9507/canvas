<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" id="featured-image-unsplash-modal" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <p class="font-weight-bold lead">{{ __('canvas::posts.forms.image.header') }}</p>

                <featured-image-uploader
                        :post="'{{ $data['post']->id }}'"
                        :url="'{{ $data['post']->featured_image }}'"
                        :caption="'{{ $data['post']->featured_image_caption }}'"
                        :unsplash="'{{ config('canvas.unsplash.access_key') }}'">
                </featured-image-uploader>
            </div>
            <div class="modal-footer">
                <button class="btn btn-link text-muted" data-dismiss="modal">{{ __('canvas::buttons.general.done') }}</button>
            </div>
        </div>
    </div>
</div>
