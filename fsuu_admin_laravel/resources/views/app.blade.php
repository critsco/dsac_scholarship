<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('MIX_APP_NAME') }}</title>

    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="{{ env('MIX_APP_BG_COLOR') }}" />
    <meta name="description" content="{{ env('MIX_APP_DESCRIPTION') }}" />

    <link rel="preconnect" href="/fonts/Roboto-Regular.ttf" as="font" type="font/ttf" crossorigin />
    <link rel="preconnect" href="/fonts/Roboto-Medium.ttf" as="font" type="font/ttf" crossorigin />
    <link rel="preconnect" href="/fonts/Roboto-Bold.ttf" as="font" type="font/ttf" crossorigin />

    <style>
        @font-face {
            font-family: "RobotoRegular";
            src: url("/fonts/Roboto-Regular.ttf") format("truetype");
        }

        @font-face {
            font-family: "RobotoMedium";
            src: url("/fonts/Roboto-Medium.ttf") format("truetype");
        }

        @font-face {
            font-family: "RobotoBold";
            src: url("/fonts/Roboto-Bold.ttf") format("truetype");
        }

        * {
            font-family: RobotoRegular !important;
            /* font-weight: bold; */
        }

        :root {
            font-family: RobotoRegular !important;
        }

        html,
        body {
            /* font-family: RobotoRegular !important; */
            background-image: url("/images/login_bg.png");
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0px;
            padding: 0px;
            min-height: 100vh;
        }

        .globalLoading {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 999999999999;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* splas screen */
        .splash-centered {
            position: fixed;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 100px !important;
        }

        .splash-loader {
            /* position: absolute;
            top: calc(51% - 32px);
            left: calc(50% - 32px); */
            margin-top: 10px !important;
            margin-left: 63px !important;
            margin: auto;
            width: 64px;
            height: 64px !important;
            border-radius: 50%;
            perspective: 800px;
        }

        .splash-inner {
            position: absolute;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .splash-inner.one {
            left: 0;
            top: 0;
            animation: rotate-one 1s linear infinite;
            border-bottom: 3px solid #72141c;
        }

        .splash-inner.two {
            right: 0;
            top: 0;
            animation: rotate-two 1s linear infinite;
            border-right: 3px solid #72141c;
        }

        .splash-inner.three {
            right: 0;
            bottom: 0;
            animation: rotate-three 1s linear infinite;
            border-top: 3px solid #72141c;
        }

        @keyframes rotate-one {
            0% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(0);
            }

            100% {
                transform: rotateX(35deg) rotateY(-45deg) rotateZ(360deg);
            }
        }

        @keyframes rotate-two {
            0% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(0);
            }

            100% {
                transform: rotateX(50deg) rotateY(10deg) rotateZ(360deg);
            }
        }

        @keyframes rotate-three {
            0% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(0);
            }

            100% {
                transform: rotateX(35deg) rotateY(55deg) rotateZ(360deg);
            }
        }
    </style>

    @laravelPWA

    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />
</head>

<body>

    <div id="root">
        <div class="splash-centered">
            <img width="180" src="/images/logo.png" />
            <div class="splash-loader">
                <div class="splash-inner one"></div>
                <div class="splash-inner two"></div>
                <div class="splash-inner three"></div>
            </div>
        </div>
    </div>

    <script src="{{ mix('js/app.js') }}"></script>

</body>

</html>
