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
	<div class="row">
		<div class="col-md-4 col-md-offset-4 col-xs-12 confirmar">
			<h1>¡Felicidades {{ explode(' ',trim($persona->name))[0] }}!</h1>
			<p>Tu reservación con el numero de confirmacion #{{ $booking }} se ha efectuado con exito</p>
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
				</div>
			</div>
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
				</p>
			</div>
			<img src="https://api.qrserver.com/v1/create-qr-code/?data={{$booking}}&amp;size=100x100" alt="QR" title="Su reservación">
			<br>
			<a href="/" class="btn btn-success btn-lg">Regresar</a>
			<a href="#imprimir" onClick="window.print();" class="btn btn-info btn-lg">Imprimir</a>
		</div>
	</div>
</div>
@endsection