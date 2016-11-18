<?php

namespace App\Http\Controllers;

use DateTime;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;

// Este controlador se encarga de las pantallas y la conexion con la API
const SIVEB = "siveb_all.json";
const SAJU = "saju_all.json";
class HomeController extends Controller
{
    /*
        Regresa todos los juegos
    */
    public function show(){
		return HomeController::returnMessage();
    	
    }

    /*
        Regresa todos los horarios de un juego
    */
    public function horarios(){
    	$juego = HomeController::getJuego(Input::get("id"));

    	return view("info")->with("juego",$juego)->with("notitle",Input::get("nm"));
    }

    /*
        Regresa la instancia de un juego
    */
    public static function getJuego($id_juego)
    {
    	$juegos = json_decode(file_get_contents(SAJU));

    	foreach($juegos->data as $item)
    	{
    	    if($item->id == $id_juego)
    	    {
    	        return $item;
    	    }
    	}

    	return NULL;
    }

    /*
        Regresa el primer juego disponible
    */
    public static function getJuegoDisponible()
    {
    	$juegos = json_decode(file_get_contents(SAJU));

    	foreach($juegos->data as $item)
    	{
    	    if($item->status == "disponible")
    	    {
    	    	return $item;
    	    }
    	}

    	return NULL;
    }

    /*
        Regresa los detalles de un horario
    */
    public static function getHorario($juego,$id_hora)
    {
    	foreach($juego->horarios as $item)
    	{
    	    if($item->id == $id_hora)
    	    {
    	        return $item;
    	    }
    	}

    	return NULL;
    }

    /*
        Regresa los detalles de una persona
    */
    public static function getPersona($id_boleto)
    {
    	$personas = json_decode(file_get_contents(SIVEB));

    	foreach($personas->data as $item)
    	{
    	    if($item->boleto == $id_boleto)
    	    {
    	        return $item;
    	    }
    	}

    	return NULL;
    }

    /*
        Regresa un mensaje de error y los juegos disponibles
    */
    public function returnMessage($errormsg='')
    {
        $juegos = json_decode(file_get_contents(SAJU));
        $juegodisp = HomeController::getJuegoDisponible();
        if(isset($juegodisp)){
            return view('welcome')->with("juegos",$juegos->data)->with("juegodisp",$juegodisp)
                                  ->with("error",$errormsg);
        }

        return view("welcome")->with("juegos",$juegos->data)
                              ->with("error",$errormsg);
    }

    public function isToday($date)
    {
        $today = new DateTime();
        $datetocompare  = new DateTime($date);
        $diff = $today->setTime(0,0,0)->diff( $datetocompare->setTime(0,0,0) );
        $diffDays = (integer)$diff->format( "%R%a" );

        if($diffDays != 0){
            return True;
        }
        return false;
    }

    /*
        Regresa la pantalla de la reservación con los datos del juego, persona y horario
    */
    public function reservar(){
        //Obtenemos los id de la url
    	$id_juego = Input::get("jg");
    	$id_hora = Input::get("dt");
    	$id_boleto = Input::get("tk");

        //Verificamos que el juego exista
		$juego = HomeController::getJuego($id_juego);    	

    	if(!isset($juego)){
    		return HomeController::returnMessage("El juego seleccionado no esta disponible");
    	}

        //Verificamos que el horario exista

    	$horario = HomeController::getHorario($juego,$id_hora);

    	if(!isset($horario)){
            return HomeController::returnMessage("El horario seleccionado no esta disponible");
    	}


        //Verificamos que el horario sea de hoy
		if(HomeController::isToday($horario->datetime)){
            return HomeController::returnMessage("El horario seleccionado no es del día de hoy");
    	}

        //Verificamos que el hora no haya pasado
    	if( time() > ( (int)date("U",strtotime($horario->datetime)) - 540) ){
            return HomeController::returnMessage("La hora ya paso o es muy cercana");
    	}

        //Verificamos que la persona exista
		$persona =  HomeController::getPersona($id_boleto);	

    	if(!isset($persona)){
            return HomeController::returnMessage("El boleto ingresado no existe");
    	}

    	if(HomeController::isToday($persona->fecha) && false){ //DESACTIVADO PARA PRUEBAS
            return HomeController::returnMessage("El boleto ya no tiene vigencia");
    	}

        //Verifico si ya tiene reservación, si ya es pasada le quito el active
    	$hasbooks = Booking::where('id_boleto',$id_boleto)->where('active',"1")->get()->first();
        if(isset($hasbooks)){
            $newhorario = HomeController::getHorario(HomeController::getJuego($hasbooks->id_juego),
                                                        $hasbooks->id_hora);
            if (time() > ( (int)date("U",strtotime($newhorario->datetime))) ) { //Ya paso
                $hasbooks->active = 0;
                $hasbooks->save();
            }else{
                return HomeController::returnMessage("Usted ya tiene una reservación activa");
            }
        }

        // Checo si la persona no compro el boleto, y quien se lo compro
    	if(isset($persona->buyedby)){
    		$buyedby =  HomeController::getPersona($persona->buyedby);	
    		return view("reserve")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona)
    							  ->with("buyedby",$buyedby);
    	}

        //Regreso la pantalla de reservación
    	return view("reserve")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona);
    }

    /*
        "Verifica con el SAJU" que se hizo la reservación
    */
    public function fake($request)
    {
    	$id_juego = $request->input('id_juego');
        $id_hora = $request->input('id_hora');
        $id_boleto = $request->input('id_persona');
        return response()->json([
            'estado' => 'correcto'
        ]);
    }

    /*
        Regresa la pantalla de actualización de reservación
    */
    public function cambiar()
    {
    	return view("cambiar")->with("update",'1');
    }
}
