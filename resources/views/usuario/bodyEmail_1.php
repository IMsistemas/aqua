<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>

<style type="text/css">

    body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    .body {

        background-color: #f6f6f6;
        width: 100%;
    }


    /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
    .container {
        display: block;
        margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px;
    }

    /* This should also be a block element, so that it will fill 100% of the .container */
    .content {
        box-sizing: border-box;
        display: block;
        margin: 0 auto;
        max-width: 580px;
        padding: 10px;
    }
    /* -------------------------------------
             HEADER, FOOTER, MAIN
         ------------------------------------- */
    .main {
        background: #ffffff;
        border-radius: 5px;
        width: 90%;
        margin: auto;
        text-align: center;
    }

    .wrapper {
        box-sizing: border-box;
        padding: 20px; }

    .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .footer {
        clear: both;
        margin-top: 10px;
        text-align: center;
        width: 100%;
    }

    .footer td,
    .footer p,
    .footer span,
    .footer a {
        color: #999999;
        font-size: 12px;
        text-align: center;
    }


    h1,
    h2,
    h3,
    h4 {
        color: #1769ff;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px;
    }

    h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize;
    }

    p,
    ul,
    ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px;
    }
    p li,
    ul li,
    ol li {
        list-style-position: inside;
        margin-left: 5px;
    }

    a {
        color: #3498db;
        text-decoration: underline;
    }


    .greeting{

        color:#9d1c30 !important;

    }
    .bodys{

        color:#58595d;
    }

</style>

<body>

<div style="text-align: center;">
    <h2>Verificación de Correo Electronico</h2>
</div>

<div class="main" style="text-align: center;">

    <div style="width:50%;background:#f6f6f6;margin:0 auto;padding: 50px;">

        <div style="text-align: center;">
            <a href="https://www.aquapotable.org/" target="_blank" >
                <img src="https://<?= $_SERVER['HTTP_HOST'] ?>/img/logotipo-interno.png" alt="AquaPotable" style="width: 25%;">
            </a>
        </div>

        <div style="width:90%;background:white;margin:0 auto;">

            <div style="padding:10px 15px;">
                <h2 class="greeting" style="color:#0ca44c;">Estimado <?= $username ?></h2>
            </div>

            <div style="padding: 0px 10px;">
                <h3 class="bodys"><b>Para reiniciar su contraseña, haz click en el siguiente enlace</b>
                    <br />
                    <a target="_blank" href="http://<?= $_SERVER['HTTP_HOST'] ?>/changePassword/<?= $token ?>">Recuperar Contraseña</a>
                </h3>
            </div>


            <div style="padding: 0px 10px 10px;">
                <h3 class="bodys"><b>En unos instantes le llegará un email con su contraseña nueva</b></h3>
            </div>



        </div>
        <!-- START FOOTER -->
        <div class="footer" >

            <div class="content-block" style="text-align:center">
                <p class="greeting">Copyright &copy; 2012 - <?= date('Y'); ?> Todos los derechos reservados</p>
            </div>


            <div class="content-block  powered-by" style="text-align:center">
                <a href="https://www.imnegocios.com" target="_blank" style="margin-left:30px;">
                    <img src="https://<?= $_SERVER['HTTP_HOST'] ?>/img/logo-powered.png" alt="IM Negocios">
                </a>
            </div>

        </div>
        <!-- END FOOTER -->

        <!--<div>
                <h4>{{trans('email.verify-mail-user-qualify.body_2')}}</h4>
            </div>-->
    </div>
</div>
</div>



</body>
</html>