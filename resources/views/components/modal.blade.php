<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-{{ $type }} modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{ $message }}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('Tutup') }}
                </button>
            </div>

        </div>
    </div>
</div>