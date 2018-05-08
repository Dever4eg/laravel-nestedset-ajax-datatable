<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href="/css/app.css">
    </head>
    <body>

    <div class="container">
        <h1>test</h1>

        <div id="tree"></div>

    </div>

    <script src="/js/app.js"></script>

    <script>
        $('#tree').tree({
            dataSource:
            [
                {
                    text: 'foo',
                    children:
                    [
                        {
                            text: 'bar'
                        },
                        {
                            text: 'badsavr'
                        }
                    ]
                },
                {
                    text: 'foo',
                    children:
                        [
                            {
                                text: 'bar'
                            },
                            {
                                text: 'badsavr'
                            },
                            {
                                text: 'foo',
                                children:
                                    [
                                        {
                                            text: 'bar'
                                        },
                                        {
                                            text: 'badsavr'
                                        }
                                    ]
                            }
                        ]
                }
            ]
        });
    </script>
    </body>
</html>
