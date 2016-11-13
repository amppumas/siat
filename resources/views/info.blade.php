<?php
setlocale(LC_TIME,"es_ES");
$today = new DateTime();
$hoy = utf8_encode(strftime("%A %e de %B del %Y", $today->getTimestamp() ));
?>
<div class="col-md-6 col-xs-12" id="juegoinfo">
	<h1>{{ $juego->name }}</h1>
	<h2>{{ ucfirst($hoy) }}</h2>
	@foreach($juego->horarios as $horario)
		<?php
			$date    = new DateTime($horario->datetime);
			$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
			$diffDays = (integer)$diff->format( "%R%a" );
			$datetime = new DateTime($horario->datetime);
		?>
		@if($diffDays == 0 && (int)$horario->places > 0)
			<p class="horario" data-juego="{{ $juego->id }}" data-hora="{{ $horario->id }}">
				<span class="hora">
					{{ $datetime->format('g:i A')}}
				</span>
				<span class="lugares">
					{{ $horario->places }} lugares disponibles
				</span>
			</p>
		@endif
	@endforeach
</div>
<script type="text/javascript">
	$( ".horario" ).click(function(){
        id_juego = jQuery(this).data("juego");
        id_hora = jQuery(this).data("hora");
        show_button();
    });
</script>