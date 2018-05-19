@extends('layouts.app')

@section('content')

    <div id="app">
        <employees route="{{route('api.employees.getData')}}"></employees>
    </div>
@endsection