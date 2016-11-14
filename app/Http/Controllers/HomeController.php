<?php

namespace App\Http\Controllers;

use DateTime;
use App\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Bugsnag;

const SIVEB = "siveb_all.json";
const SAJU = "saju_all.json";
class HomeController extends Controller
{
    public function error(){
        Bugsnag::notifyError('ErrorType', 'Test Error');
        return NULL;
        
    }
    public function show(){
		$juegos = json_decode(file_get_contents(SAJU));
		foreach($juegos->data as $item)
		{
		    if($item->status == "disponible")
		    {
		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item);
		    }
		}
		return view('welcome')->with("juegos",$juegos->data);
    	
    }

    public function horarios(){
    	$juego = HomeController::getJuego(Input::get("id"));
    	return view("info")->with("juego",$juego)->with("notitle",Input::get("nm"));
    }

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

    public function reservar(){
    	$id_juego = Input::get("jg");
    	$id_hora = Input::get("dt");
    	$id_boleto = Input::get("tk");
    	$juegos = json_decode(file_get_contents(SAJU));
		$juego = HomeController::getJuego($id_juego);    	

    	if(!isset($juego)){
    		$juegodisp = HomeController::getJuegoDisponible();
    		if(!isset($juegodisp)){
    			return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    				      ->with("error","El juego seleccionado no esta disponible");
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El juego seleccionado no esta disponible");
    	}

    	$horario = HomeController::getHorario($juego,$id_hora);

    	if(!isset($horario)){
    		$juegodisp = HomeController::getJuegoDisponible();
    		if(!isset($juegodisp)){
    			return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    						  ->with("error","El horario seleccionado no esta disponible");
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El horario seleccionado no esta disponible");
    	}

    	$today = new DateTime();
    	$date    = new DateTime($horario->datetime);
    	$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
		$diffDays = (integer)$diff->format( "%R%a" );
		if($diffDays != 0){
			$juegodisp = HomeController::getJuegoDisponible();
			if(!isset($juegodisp)){
				return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    						  ->with("error","El horario seleccionado no esta disponible");
			}
			return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El horario seleccionado no esta disponible");
    	}
    	if( time() > ( (int)date("U",strtotime($horario->datetime)) - 540) ){
			$juegodisp = HomeController::getJuegoDisponible();
			if(!isset($juegodisp)){
				return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    				      ->with("error","La hora ya paso o es muy cercana");
			}
			return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","La hora ya paso o es muy cercana");
    	}

		$persona =  HomeController::getPersona($id_boleto);	

    	if(!isset($persona)){
			$juegodisp = HomeController::getJuegoDisponible();
			if(!isset($juegodisp)){
				return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    						  ->with("error","El boleto no existe");
			}
			return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El boleto no existe");
    	}

    	$date    = new DateTime($persona->fecha);
    	$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
		$diffDays = (integer)$diff->format( "%R%a" );
    	if($diffDays != 0){
    		$juegodisp = HomeController::getJuegoDisponible();
			if(!isset($juegodisp)){
				return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    						  ->with("error","El boleto no tiene vigencia");
			}
			return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El boleto no tiene vigencia");
    	}
    	$hasbooks = Booking::where('id_boleto',$id_boleto)->where('active',"1")->get()->first();
        if(isset($hasbooks)){
        	$juegodisp = HomeController::getJuegoDisponible();
			if(!isset($juegodisp)){
				return view('welcome')->with("juegos",$juegos->data)->with("juego",$juegodisp)
    		    						  ->with("error","Usted ya tiene una reservación activa");
			}
			return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","Usted ya tiene una reservación activa");
        }

    	if(isset($persona->buyedby)){
    		$buyedby =  HomeController::getPersona($persona->buyedby);	
    		return view("reserve")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona)
    							  ->with("buyedby",$buyedby);
    	}
    	return view("reserve")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona);
    }

    public function fake($request)
    {
    	$id_juego = $request->input('id_juego');
        $id_hora = $request->input('id_hora');
        $id_boleto = $request->input('id_persona');
        return response()->json([
            'estado' => 'correcto'
        ]);
    }

    public function cambiar()
    {
    	return view("cambiar")->with("update",'1');
    }
}
