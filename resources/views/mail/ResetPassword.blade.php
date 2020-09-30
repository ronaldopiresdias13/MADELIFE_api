<head>
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
</body>