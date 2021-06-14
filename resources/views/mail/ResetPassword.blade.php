{{-- <head>
    <link rel="stylesheet" href="/css/ResetPassword.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet" type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js" rel="stylesheet">

</head>

<body>
    <div class="container">
        <div class="banner">
            <img src="/images/logo-madelife.png" class="logo">
        </div>
        <div class="content">
            <p>Olá {{ $user->pessoa->nome }}, tudo bem ? </p>
            <p>Sua senha de acesso ao App ML foi redefinida com sucesso.</p>
            <p>Aqui está sua nova senha: <strong class="bold"> {{ $senha }} </strong></p>
            <p>Se desejar, poderá alterá-la no App ML</p>
            <br />
            <p>Bom trabalho!!!</p>
            <p><strong class="bold">Equipe da MadeLife</strong></p>
            <br />
            <p><a href="http://www.madelife.med.br/" target="_blank"><i class="fa fa-globe"></i>
                    www.madelife.med.br</a>
            </p>
            <a href="mailto:comercial@madelife.med.br" target="_blank"><i class="fa fa-envelope"></i>
                comercial@madelife.med.br</a>
            <p><i class="fa fa-phone-square-alt"></i> +55 17 98192-0223</p>
            <p><i class="fa fa-clock"></i> Segunda a Sexta das 08:00 as 18:00 horas</p>
        </div>
    </div>
</body> --}}

<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet"
        type='text/css'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js" rel="stylesheet">

    <style>
        body {
            background-color: #fdf8f7;
            font-family: 'Montserrat', sans-serif;
        }

        a {
            text-decoration: none;
            color: #000;
        }

        .banner {
            background-color: #f8f9fa;
            height: 18%;
            border-radius: 30px;
            text-align: center;
        }

        .logo {
            height: 80%;
            padding-top: 10px;
        }

        .content {
            padding: 0 10%;
        }

        .bold {
            font-weight: 900;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="banner">
            <img src="data:image/png;base64,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"
                class="logo">
        </div>
        <div class="content">
            <p>Olá {{ $user->pessoa->nome }}, tudo bem ? </p>
            <p>Sua senha de acesso ao App ML foi redefinida com sucesso.</p>
            <p>Aqui está sua nova senha: <strong class="bold"> {{ $senha }} </strong></p>
            <p>Se desejar, poderá alterá-la no App ML</p>
            <br />
            <p>Bom trabalho!!!</p>
            <p><strong class="bold">Equipe da MadeLife</strong></p>
            <br />
            <p><a href="http://www.madelife.med.br/" target="_blank"><i class="fa fa-globe"></i>
                    www.madelife.med.br</a>
            </p>
            <a href="mailto:comercial@madelife.med.br" target="_blank"><i class="fa fa-envelope"></i>
                comercial@madelife.med.br</a>
            <p><i class="fa fa-phone-square-alt"></i> +55 17 98192-0223</p>
            <p><i class="fa fa-clock"></i> Segunda a Sexta das 08:00 as 18:00 horas</p>
        </div>
    </div>
</body>
