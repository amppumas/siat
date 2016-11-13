<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>Asignación de Turnos</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://bootswatch.com/superhero/bootstrap.min.css" crossorigin="anonymous">
        <!-- JQuery ain't nobody got time for vanilla JS-->
        <script   src="https://code.jquery.com/jquery-3.1.1.min.js"   integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="   crossorigin="anonymous"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat|Muli" rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="main.css">
    </head>
    <body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Sistema de Asignación de Turnos</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
          <ul class="nav navbar-nav">
            @if(!isset($update))
                <li class="active" ><a href="/">Nueva Reservación<span class="sr-only">(current)</span></a></li>
                <li><a href="/cambiar">Modificar/Cancelar Reservación</a></li>
            @else
                <li><a href="/" >Nueva Reservación<span class="sr-only">(current)</span></a></li>
                <li class="active" ><a href="/cambiar">Modificar/Cancelar Reservación</a></li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
        @yield('content')
    </body>
</html>