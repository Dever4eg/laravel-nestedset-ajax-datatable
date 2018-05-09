@extends('layouts.app')

@section('content')
    <div id="tree"></div>
@endsection

@section('scripts')
    <script>

        $(document).ready(function(){
            $.ajax({
                url: "/api/employees",
                method:"get",
                dataType: "json",
                success: function (data)
                {
                    data = (function proc(data) {
                        data.forEach(function(item, i, data){

                            // Displayed text
                            data[i].text = data[i].fullname + ", " + data[i].position;

                            if(data[i].children)
                                data[i].children =  proc(data[i].children);
                        } );
                        return data;
                    }(data));

                    $('#tree').tree({
                        textField: 'text',
                        dataSource: data
                    });
                }
            });

        });
    </script>
@endsection