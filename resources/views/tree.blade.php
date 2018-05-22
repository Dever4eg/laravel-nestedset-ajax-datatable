@extends('layouts.app')

@section('content')
    <div id="app">

    </div>
    <div id="tree"></div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $('#tree').tree({
                textField: 'text',
                primaryKey: 'id',
                lazyLoading: true,
                dataSource: '{{route('api.employees.getTree')}}'+'?api_token='+window.api_token,
            });
        });
    </script>
@endsection