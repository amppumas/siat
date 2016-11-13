<?php
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$today = new DateTime();
$hoy = $dias[$today->format('w')]." ".$today->format('d')." de ".$meses[$today->format('n')-1]. " del ".$today->format('Y') ;
?>
@if(!isset($notitle))
<div class="col-md-6 col-xs-12" id="juegoinfo">
	<h1>{{ $juego->name }}</h1>
	<h2>{{ ucfirst($hoy) }}</h2>
@else
<div class="otroshorarios" id="juegoinfo">
<p>Otros horarios</p>
@endif
	@foreach($juego->horarios as $horario)
		<?php
			$date    = new DateTime($horario->datetime);
			$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
			$diffDays = (integer)$diff->format( "%R%a" );
			$datetime = new DateTime($horario->datetime);
		?>
		@if($diffDays == 0 && (int)$horario->places > 0 &&
							  (time() < ( (int)date("U",strtotime($horario->datetime)) - 540)) )
			@if(isset($notitle))
				@if($notitle!=$horario->id)
				<p class="horario" data-juego="{{ $juego->id }}" data-hora="{{ $horario->id }}">
					<span class="hora">
						{{ $datetime->format('g:i A')}}
					</span>
					<span class="lugares">
						{{ $horario->places }} lugares disponibles
					</span>
				</p>
				@endif
			@else
				<p class="horario" data-juego="{{ $juego->id }}" data-hora="{{ $horario->id }}">
					<span class="hora">
						{{ $datetime->format('g:i A')}}
					</span>
					<span class="lugares">
						{{ $horario->places }} lugares disponibles
					</span>
				</p>
			@endif
		@endif
	@endforeach
</div>
@if(isset($notitle))
<!-- Button trigger modal -->
<button id="btn_ch" type="button" class="btn btn-primary btn-lg" data-action="update" data-text="Se cambiara la reservación a la hora elegida" data-toggle="modal" data-target="#conf" disabled>
  Cambiar horario
</button>
<!-- Button trigger modal -->
<button type="button" class="btn btn-danger btn-lg" data-action="delete" data-text="Esta seguro que desea cancelar la reservación" data-toggle="modal" data-target="#conf">
  Cancelar reservación
</button>

<!-- Modal -->
<div class="modal fade" id="conf" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">¿Esta seguro?</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        <button id="enviar" data-action="none" type="button" class="btn btn-primary">Sí</button>
      </div>
    </div>
  </div>
</div>
@endif
<script type="text/javascript">
	$( ".horario" ).click(function(){
        id_juego = jQuery(this).data("juego");
        id_hora = jQuery(this).data("hora");
        $('.horario').removeClass('selected');
    	jQuery(this).addClass('selected');
    	$( "#btn_ch" ).prop( "disabled", false );
        show_button();
    });
    @if(isset($notitle))
    $('#conf').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var text = button.data('text') // Extract info from data-* 
      var action = button.data('action') // Extract info from data-* attributes
      var modal = $(this);
      modal.find('.modal-body').html(text);
      modal.find('#enviar').data('action', action);
    })
    $( "#enviar" ).click(function(event){
        var button = $(event.relatedTarget) // Button that triggered the modal
        var action = $( "#enviar" ).data('action') // Extract info from data-* attributes
        show_button(action,$('.selected,.horario').data("juego"),$('.selected,.horario').data("hora"));
    });
    @endif
</script>