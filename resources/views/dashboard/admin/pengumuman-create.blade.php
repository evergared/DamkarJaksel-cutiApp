@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')


<div class="container-fluid mt--7" id="pengumuman-creator-page">

<pengumuman-creator id="pengumuman-creator"></pengumuman-creator>
<pengumuman-preview id="pengumuman-preview"></pengumuman-preview>

  @include('layouts.footers.nav')


</div>

@endsection

@push('js')
<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js">
</script>

<script src="{{ asset('js/PengumumanCreatorPage.js') }}">

</script>
@endpush