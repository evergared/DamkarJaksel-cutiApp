<div class="modal fade" tabindex="-1" role="dialog" id="{{ $id }}">
    <div class="modal-{{ $type }} modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">
                  {{ $title }}
                </h4>
                <button type="button" class="close" data-dismiss="modal" data-target="{{ $id }}">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

              @isset($footer)
                {{ $message }}
              @endisset

            </div>

            <div class="modal-footer">

              @empty($footer)
              <button type="button" class="btn btn-primary text-white" data-dismiss="modal" data-target="{{ $id }}">OK</button>
              @endempty

              @isset($footer)
              {{ $footer }}
              @endisset

            </div>

        </div>
    </div>
</div>
