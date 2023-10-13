@php
    /**
     * @var \App\Exceptions\UserException $issue
     */
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$issue->getTitle()}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
          crossorigin="anonymous">
</head>
<body>
<section class="container">

    <div class="card text-bg-warning mb-3 mt-5 p-5 pt-2" style="width: fit-content">
        <div class="card-header">
            <h1>
                {{$issue->getTitle()}}
            </h1>

        </div>
        <div class="card-body">
            <h5 class="card-title">
                {{ $issue->getCode() }}
            </h5>
            <p class="card-text">
                {{$issue->getMessage()}}
            </p>
        </div>
    </div>

</section>

</body>
</html>

