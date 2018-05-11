@extends('layouts.app')

@section('content')
    <div id="tree"></div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $('#tree').tree({
                textField: 'text',
                primaryKey: 'id',
                lazyLoading: true,
                dataSource: '/api/employees'
            });
        });
    </script>
@endsection