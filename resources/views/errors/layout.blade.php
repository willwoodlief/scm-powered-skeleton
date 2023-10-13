<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
              rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
              crossorigin="anonymous">
    </head>
    <body>


        <section class="container">

            <div class="card text-bg-light mb-3 mt-5 p-5 pt-2 text-center">
                <div class="card-header">
                    <h1 style="font-size: 8rem">
                        @yield('code')
                    </h1>

                </div>
                <div class="card-body">

                        <h2 class="card-text mt-5">
                            @yield('message')
                        </h2>

                </div>
            </div>

        </section>
    </body>
</html>
