<?php
function fecha($today,$day)
{
	$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio",
				   "Agosto","Septiembre","Octubre","Noviembre","Diciembre");
	$today = new DateTime($today);
	if (!$day) {
		return $today->format('d').
	             " de ".$meses[$today->format('n')-1]. " del ".$today->format('Y');
	}
	return $dias[$today->format('w')]." ".$today->format('d').
	             " de ".$meses[$today->format('n')-1]. " del ".$today->format('Y');
}
$horareserva = new DateTime($horario->datetime);
?>
@extends('layouts.app')
@section('content')
<div class="container">
	@if(session('error'))
	    <div class="alert alert-dismissible alert-danger">
	      <button type="button" class="close" data-dismiss="alert">&times;</button>
	      <strong>Lo sentimos :c </strong> {{ session('error') }}
	    </div>
	@endif
	<div class="row">
		<div class="col-md-6 col-xs-12 datospersonales">
			<h1>¡Hola {{ explode(' ',trim($persona->name))[0] }}!</h1>
			<p>Corrobora que los datos de tu reservación sean correctos</p>
			<div class="reservacion">
				<div class="persona">
					<p class="name">
						<span class="nombre">Nombre completo:</span>
						{{ $persona->name }}
					</p>
					<p class="age">
						<span class="edad">Edad:</span>
						{{ $persona->edad }}
					</p>
					@if(isset($persona->email))
						<p class="email">
							<span class="email">Email:</span>
							{{ $persona->email }}
						</p>
						<p class="birthday">
							<span class="cumple">Cumpleaños:</span>
							{{ fecha($persona->birthday,false) }}
						</p>
						<p class="address">
							<span class="direccion">Dirección:</span>
							{{ $persona->address }}
						</p>
					@elseif(isset($buyedby))
						<p class="buyedby">
							<span class="compradopor">Boleto comprado por:</span>
							{{ $buyedby->name }}
						</p>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-6 col-xs-12 datosreserva">
			<div class="juego">
				<p class="name">
					<span class="nombre">Juego:</span>
					{{ $juego->name }}
				</p>
				<p class="status">
					<span class="estado">Estado:</span>
					{{ ucfirst($juego->status) }}
				</p>
				<p class="hora">
					<span class="nombre">Horario:</span>
					<span>Hoy a las {{ $horareserva->format('g:i A') }}</span>
					<span class="quedan">Quedan <strong>{{ $horario->places }}</strong> a esa hora</span>
				</p>
			</div>
			<p>Tus Boletos no estan apartados aun, debes presionar el boton de reservar para confirmar</p>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-4 col-md-4 col-xs-12">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/reservar') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id_juego" value="{{ $juego->id }}">
				<input type="hidden" name="id_hora" value="{{ $horario->id }}">
				<input type="hidden" name="id_persona" value="{{ $persona->boleto }}">
			 	<input type="submit" value="Confirmar" class="btn btn-success btn-lg"></a>
				<a href="/" class="btn btn-danger btn-lg">Cancelar</a>
			</form>
		</div>
	</div>
</div>
@endsection