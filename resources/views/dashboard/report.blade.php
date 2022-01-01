@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')

<div class="container-fluid mt--7">
  <div class="col-lg-12 md-7">
    <div class="card bg-secondary shadow border-0 xl-4">

            {{-- header tabel --}}
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col text-left">
              <h3>{{ __('Report Data Cuti') }}</h3>
            </div>

            @if(auth()->user()->has_subordinate || auth()->user()->is_admin)
            <div class="nav-wrapper text-right">
                <ul class="nav nav-pills flex-column flex-md-row align-items-right" role=tablist>

                      @if(auth()->user()->can_request_cuti)
                      <li class="nav-item">
                        <a class="nav-link"  href="#tab-pribadi" role="tab" data-toggle="tab" aria-controls="tab-pribadi" aria-selected="true">Pribadi</a>
                      </li>
                      @endif

                      <li class="nav-item">
                        <a class="nav-link active" href="#tab-asn" role="tab" data-toggle="tab" aria-controls="tab-asn" aria-selected="true">ASN</a>
                      </li>

                      @if(auth()->user()->has_subordinate_pjlp || auth()->user()->is_ppk || auth()->user()->is_kasubag_tu || auth()->user()->is_kasie || auth()->user()->is_admin)
                      <li class="nav-item">
                        <a class="nav-link" href="#tab-pjlp" role="tab" data-toggle="tab" aria-controls="tab-pjlp">PJLP</a>
                      </li>
                      @endif
                </ul>
            </div>
            @endif

            </div>
        </div>


        <div class="card-body">

        @if(auth()->user()->has_subordinate || auth()->user()->is_admin)
          <div class="tab-content" id="tab-content-report">

              @if(auth()->user()->can_request_cuti)
                <div class="tab-pane fade" id="tab-pribadi" role="tabpanel" aria-labelledby="tab-pribadi-tab" >
                  @include('datatable.report.self')
                </div>
              @endif

              @if(auth()->user()->is_approver || auth()->user()->is_kasudin || auth()->user()->is_admin)
              <div class="tab-pane fade show active" id="tab-asn" role="tabpanel" aria-labelledby="tab-asn-tab" >
                @include('datatable.assignment-asn')
              </div>
                  @if(auth()->user()->has_subordinate_pjlp || auth()->user()->is_approver || auth()->user()->is_kasudin || auth()->user()->is_admin)
                    <div class="tab-pane fade" id="tab-pjlp" role="tabpanel" aria-labelledby="tab-pjlp-tab" >
                      @include('datatable.assignment-pjlp')
                    </div>
                  @endif
              @endif

          </div>
        @else
            @include('datatable.report.self')
        @endif
        
        </div>

    </div>
  </div>

@include('layouts.footers.nav')

</div>

@endsection
