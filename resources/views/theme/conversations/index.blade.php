@extends('theme.master')

@section('content')
  <div class="container py-4" id="conversations-wrapper" style="min-height: 80vh;">
    @include('theme.conversations._list', compact('conversations','inviteCandidates'))

    
  </div>
@endsection


