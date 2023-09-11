@extends('layouts.app')

@section('content')
    <!--  Row 1 -->
    @include('dashboard.snippets.index.row1')

    {{-- Row 2 --}}
    @include('dashboard.snippets.index.row2')

    {{-- Row 3 --}}
    @include('dashboard.snippets.index.row3')
@endsection
