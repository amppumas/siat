@extends('layouts.app')
@section('content')
<div class="container">
    @if(isset($error))
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Lo sentimos :c </strong> {{ $error }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Exito</strong> {{ session('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <h1>Juegos Disponibles</h1>
            <table class="table table-striped juegos">
                <thead>
                    <tr>
                        <th class="tablehead">Selecciona uno de la lista</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($juegos as $key=>$juego)
                        @if($juego->status == "disponible")
                            <tr>
                                <th class="celljuego" data-id="{{ $juego->id }}">
                                    <div class="juego">
                                        <p class="name">{{ $juego->name }}</p>
                                        <p class="age">Edad mínima: {{ $juego->agelimit }}</p>
                                    </div>
                                </th>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6 col-xs-12" id="juegoinfo">
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-4 col-md-4 col-xs-12">
            <div class="input-group hidden" id="reservar">
                <input type="number" class="form-control"  placeholder="# Boleto" id="id_boleto">
                <span class="input-group-btn">
                    <button class="btn btn-default" id="irareserva" type="button">¡Reserva Ahora!</button>
                </span>
            </div><!-- /input-group -->
        </div>
    </div>
</div>
<script type="text/javascript">
    var id_juego = 9999;
    var id_hora = 0;
    $( document ).ready(function() {
        $.get('/juegos', { id: "@if(isset($juego)){{$juego->id}}@endif"}, function(data) {
            $("#juegoinfo").replaceWith(data);
        });
    });
    $( ".celljuego" ).click(function() {
        $.get('/juegos', { id: jQuery(this).data("id")}, function(data) {
                $("#juegoinfo").replaceWith(data);
        });
    });
    function show_button() {
        $("#reservar").removeClass("hidden");
    }
    $( "#irareserva" ).click(function() {
        window.location.replace("reservar?jg="+id_juego+"&dt="+id_hora+"&tk="+$("#id_boleto").val() );
    });

</script>
@endsection