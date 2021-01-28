@extends('master')

@section('title', 'Edit voyage')

@section('content')
    {{ Form::open(['route' => ['voyages.update', $voyage->id], 'method' => 'put', 'id' => 'frmEditRecord']) }}

    <div>
        @include('voyages.form')
    </div>
    {{ Form::close() }}
@endsection
