<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Client;

const SAJUver = 'http://siat.local/verificar';
const SAJU2 = "saju_all.json";
class BookingController extends Controller
{
    Request::setTrustedHeaderName(Request::HEADER_FORWARDED, null);

    $response = $kernel->handle($request);
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $id_juego = $request->input('id_juego');
        $id_hora = $request->input('id_hora');
        $id_boleto = $request->input('id_persona');
        /*$client = new Client();
        $res = $client->request('POST', SAJU, [
            'form_params' => [
                '_token' => csrf_token(), //solo para pruebas
                'id_juego' => $id_juego,
                'id_hora' => $id_hora,
                'id_boleto' => $id_boleto
            ]
        ]);
        $result= $res->getBody();
        dd($result);*/
        $result=true;
        if(!$result){
            return back()->with("error","Ha ocurrido un error intentalo de nuevo o prueba otro horario");
        }

        $reserva = new Booking();
        $reserva->id_juego=$id_juego;
        $reserva->id_hora=$id_hora;
        $reserva->id_boleto=$id_boleto;
        $reserva->save();

        $juego = HomeController::getJuego($id_juego); 

        $horario = HomeController::getHorario($juego,$id_hora);

        $persona = HomeController::getPersona($id_boleto);

        return view("confirm")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona)
                              ->with("booking",$reserva->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id='')
    {
        $id = Input::get('book');
        $saidname = Input::get('name');
        $saidname = preg_replace('/\s+/', '', $saidname);
        $saidname = strtolower($saidname);
        $book = Booking::where('id',$id)->get()->first();
        $boleto = HomeController::getPersona($book->id_boleto);
        $realname = preg_replace('/\s+/', '', $boleto->name);
        $realname = strtolower($realname);
        if($saidname != $realname){
            return view("bookinfo")->with("error","El nombre no es correcto");
        }
        if ($book->active == "0") {
            return view("bookinfo")->with("error","Esa reservación ya no esta activa");
        }
        $juego = HomeController::getJuego($book->id_juego); 

        $horario = HomeController::getHorario($juego,$book->id_hora);
        
        
        return view("bookinfo")->with("book_id",$book)->with("juego",$juego)->with("horario",$horario)->with("persona",$boleto);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $id = Input::get('book');
        $id_juego = Input::get('juego');
        $id_hora = Input::get('hora');
        $book = Booking::where('id',$id)->get()->first();
        $id_boleto = $book->id_boleto;
        $book->id_juego = $id_juego;
        $book->id_hora = $id_hora;
        $book->save();

        $juego = HomeController::getJuego($id_juego); 

        $horario = HomeController::getHorario($juego,$id_hora);

        $persona = HomeController::getPersona($id_boleto);

        return view("confirm")->with("juego",$juego)->with("horario",$horario)->with("persona",$persona)
                              ->with("booking",$book->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id='')
    {
        $id = Input::get('book');
        $book = Booking::where('id',$id)->get()->first();
        $book->active = "0";
        $book->save();

        $juegos = json_decode(file_get_contents(SAJU2));
        $juegodisp = HomeController::getJuegoDisponible();
        if(!isset($juegodisp)){
            return redirect('/')->with("success","La reservación fue cancelada");
        }
        return redirect("/")->with("success","La reservación fue cancelada");
    }
}
