@extends('layout.app')


@section('content')
<h1>
    hello from welcome blade file
</h1>
@endsection


@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("Document is ready from Blade section!");
    });
</script>
@endsection


@section('styles')


@endsection


