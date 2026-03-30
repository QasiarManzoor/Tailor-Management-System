@extends('layouts.app')

@section('title', 'Edit Measurement')
@section('page-title', 'Edit Measurement')
@section('page-subtitle', 'Update the saved measurement slip with bilingual field guidance.')

@section('content')
    <form method="POST" action="{{ route('measurements.update', $measurement) }}">
        @csrf
        @method('PUT')
        @include('measurements._form')
    </form>
@endsection
