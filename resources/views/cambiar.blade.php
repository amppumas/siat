@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-xs-12">
			<h1>Cambia o modifica tu reservación</h1>
			<p>Escribe tus datos a continuación</p>
			<form class="form-horizontal">
			  <input type="hidden" name="_token" value="{{ csrf_token() }}">
			  <fieldset>
			    <div class="form-group">
			      <label for="book_id" class="col-lg-2 control-label"># Reservacion</label>
			      <div class="col-lg-10">
			        <input type="number" class="form-control" name="book_id" id="inputBook" placeholder="# de reservación">
			      </div>
			    </div>
			    <div class="form-group">
			      <label for="Nombre" class="col-lg-2 control-label">Nombre Completo</label>
			      <div class="col-lg-10">
			        <input type="password" class="form-control" name="name" id="inputName" placeholder="Para confirmar tu identidad">
			      </div>
			    </div>
			    <div class="form-group">
			      <div class="col-lg-10 col-lg-offset-2">
			        <button id="submit" class="btn btn-primary">Obtener datos de reservación</button>
			      </div>
			    </div>
			  </fieldset>
			</form>
		</div>
		<div class="col-md-6 col-xs-12" id="bookinfo">
        </div>
	</div>	
</div>
<script type="text/javascript">
    $( "#submit" ).click(function(e) {
    	e.preventDefault();
        $.get('/getreserva', { book: $("#inputBook").val(),name: $("#inputName").val()}, function(data) {
                $("#bookinfo").replaceWith(data);
        });
    });
</script>
@endsection