<?php
namespace App;

use App\Booking;
use League\Fractal;
use App\magazines;

class BookingTransformer extends Fractal\TransformerAbstract
{
	public function transform(Booking $booking)
	{
	    return [
		        'id'      => (int) $booking->id,
		        'id_juego'      => (int) $booking->id_juego,
		        'id_boleto'   => $booking->id_boleto,
		        'id_horario'    => $booking->id_hora,
		        'active'    => (int)$booking->active,
		        'creada'    => $booking->created_at,
		        'actualizada'    => $booking->updated_at,
		        ];
	}
}