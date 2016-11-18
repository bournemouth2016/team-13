<html ng-app>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Bringo</title>
    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/vendor/form/form-elements.css" rel="stylesheet" type="text/css">
    <link href="/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link
        href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800'
        rel='stylesheet' type='text/css'>


    <!-- Theme CSS -->
    <link href="/css/creative.css" rel="stylesheet">

    <!-- JQuery JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <style>
        html, body {
            overflow-x: hidden;
        }

        body {
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-family: 'Open Sans', sans-serif;
            background-color: #191F26;
            text-align: center;
            overflow-x: hidden;
            margin: 0px;
            font-size: 24px;
        }

        h1 {
            color: white;
            font-size: 44px;
            font-weight: 100;
            margin: 5px;
        }

        a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <h1>SafeSail test</h1>
        </div>
    </div>
    <!-- /.container-fluid -->
</nav>
<?php


function greeting()
{
    $now = new DateTime();
    $hrs = $now->format('H');
    if ($hrs >= 0) $msg = "Night, Night";
    if ($hrs >= 4) $msg = "Shh, Good Morning";
    if ($hrs >= 6) $msg = "Good Morning";
    if ($hrs > 12) $msg = "Good Afternoon";
    if ($hrs >= 17) $msg = "Good Evening";
    if ($hrs >= 21) $msg = "Good Night";
    return $msg;
}


?>
<body id="page-top">
<header>
    <div class="header-content">
        <div class="header-content-inner">
            <div style="text-align: left" class="col-md-6">
                <div class="row login-form">
                    <div class="col-sm-10 col-sm-offset-1">
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<section class="bg-primary" id="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h2 class="section-heading">SafeSail!</h2>
                <hr class="light">
                <p class="text-faded">Will be here soon.</p>
            </div>
        </div>
    </div>
</section>
</body>
</html>