@extends('master')

@section('title', 'Create new voyage')

@section('content')
    {{ Form::open(['route' => 'voyages.store', 'autocomplete' => 'off', 'files' => true]) }}

    <div>
        @include('voyages.form')
    </div>
    {{ Form::close() }}
@endsection
