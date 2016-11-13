@if(isset($error))
<div class="col-md-6 col-xs-12" id="bookinfo">
	    <div class="alert alert-dismissible alert-danger">
	      <button type="button" class="close" data-dismiss="alert">&times;</button>
	      <strong>Lo sentimos :c </strong> {{ $error }}
	    </div>
</div>
@else
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
<div class="col-md-6 col-xs-12" id="bookinfo">
	<div class="datosreserva">
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
	</div>
	<div class="otroshorarios" id="juegoinfo">
    </div>
</div>
<script type="text/javascript">
	$( document ).ready(function() {
        $.get('/juegos', { id: {{ $juego->id }},nm:{{ $horario->id }}}, function(data) {
                $("#juegoinfo").replaceWith(data);
        });
    });
    function show_button(action='',id_juego='',id_hora='') {
    	if(action=='delete'){
    		window.location.replace("cancelar?book="+'{{ $book_id->id }}');
    	}
    	if(action=='update'){
    		window.location.replace("update?book="+'{{ $book_id->id }}&juego='+id_juego+'&hora='+id_hora);
    	}
	}
</script>
@endif