<!DOCTYPE html>
<html>
    <head>
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/dripicons/webfont/webfont.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/icons/LineIcons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/fonts/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <title>SwissData File viewer</title>
        <style type="text/css">
            body {
                background: #000;
                position: absolute;
                top: 0;
                bottom: 0;left: 0;
                right: 0;
            }
            iframe {
                width: 80%;
                margin-right: 10%;
                margin-left: 10%;
                min-height: 90%;
                margin-top: 5%;
                margin-bottom: 5%;
                background: red;
            }
            .headert {
                background: #CC2B24;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                padding: 15px 15px;
            }
            .headert img {
                width: 160px;
            }
        </style>
    </head>
    <body>
        <div class="headert">
            <a href="/"><img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark"> </a>
        </div>
        <iframe src="{{ $file }}" frameborder="0" seamless></iframe>
    </body>
</html>