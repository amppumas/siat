<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;

const SIVEB = "siveb_all.json";
const SAJU = "saju_all.json";
class HomeController extends Controller
{
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
		$juegos = json_decode(file_get_contents(SAJU));
		foreach($juegos->data as $item)
		{
		    if($item->id == Input::get("id"))
		    {
		        $juego = $item;
		    }
		}
    	return view("info")->with("juego",$juego);
    }

    public function reservar(){
    	$id_juego = Input::get("jg");
    	$id_hora = Input::get("dt");
    	$id_boleto = Input::get("tk");
    	$juegos = json_decode(file_get_contents(SAJU));

    	foreach($juegos->data as $item)
    	{
    	    if($item->id == $id_juego)
    	    {
    	        $juego = $item;
    	    }
    	}

    	if(!isset($juego)){
    		foreach($juegos->data as $item)
    		{
    		    if($item->status == "disponible")
    		    {
    		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item)
    		    						  ->with("error","El juego seleccionado no esta disponible");
    		    }
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El juego seleccionado no esta disponible");
    	}

    	foreach($juego->horarios as $item)
    	{
    	    if($item->id == $id_hora)
    	    {
    	        $horario = $item;
    	    }
    	}

    	if(!isset($horario)){
    		foreach($juegos->data as $item)
    		{
    		    if($item->status == "disponible")
    		    {
    		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item)
    		    						  ->with("error","El horario seleccionado no esta disponible");
    		    }
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El horario seleccionado no esta disponible");
    	}

    	$today = new DateTime();
    	$date    = new DateTime($horario->datetime);
    	$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
		$diffDays = (integer)$diff->format( "%R%a" );
		if($diffDays != 0){
    		foreach($juegos->data as $item)
    		{
    		    if($item->status == "disponible")
    		    {
    		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item)
    		    						  ->with("error","El horario seleccionado no esta disponible");
    		    }
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El horario seleccionado no esta disponible");
    	}

    	$personas = json_decode(file_get_contents(SIVEB));
    	foreach($personas->data as $item)
    	{
    	    if($item->boleto == $id_boleto)
    	    {
    	        $persona = $item;
    	    }
    	}

    	if(!isset($persona)){
    		foreach($juegos->data as $item)
    		{
    		    if($item->status == "disponible")
    		    {
    		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item)
    		    						  ->with("error","El boleto no existe");
    		    }
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El boleto no existe");
    	}

    	$date    = new DateTime($persona->fecha);
    	$diff = $today->setTime(0,0,0)->diff( $date->setTime(0,0,0) );
		$diffDays = (integer)$diff->format( "%R%a" );
    	if($diffDays != 0){
    		foreach($juegos->data as $item)
    		{
    		    if($item->status == "disponible")
    		    {
    		    	return view('welcome')->with("juegos",$juegos->data)->with("juego",$item)
    		    						  ->with("error","El boleto no tiene vigencia");
    		    }
    		}
    		return view("welcome")->with("juegos",$juegos->data)
    							  ->with("error","El boleto no tiene vigencia");
    	}
    	return view("reserve")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona);
    }
}
