<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Maintenance Mode | {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <style>
        body {
            background: url('/site/defaults/under-construction.jpg') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        .transparent {
            background: rgba(0,0,0,0.4);
        }
    </style>

</head>
<body>

    <section class="hero hero-down pt-5 pb-5">
        <div class="container-fluid mt-5 mb-5">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero-content transparent c-white">

                        <h1 class="tt-u ls-16 ta-c">We are upgrading</h1>

                        <p class="text-center fsz-xl mt-4 fw-300 ta-c">
                            Sorry for any inconvenience, Check us back.
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>