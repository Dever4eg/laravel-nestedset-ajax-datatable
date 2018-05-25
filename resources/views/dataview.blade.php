@extends('layouts.app')

@section('content')

    <div id="app" >
        <employees avatars-url="{{ Storage::url('avatars/') }}"></employees>
    </div>
@endsection