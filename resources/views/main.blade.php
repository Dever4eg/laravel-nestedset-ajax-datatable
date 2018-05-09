@extends('layouts.app')

@section('content')
    <div id="tree"></div>
@endsection

@section('scripts')
    <script>
        $('#tree').tree({
            textField: 'fullname',
            dataSource: '/api/employees'
        });
    </script>
@endsection