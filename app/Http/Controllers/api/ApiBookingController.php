<?php
namespace App\Http\Controllers\api;
use Illuminate\Http\Request;
use App\Booking;
use App\BookingTransformer;
use App\Http\Requests;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Client;
use Chrisbjr\ApiGuard\Http\Controllers\ApiGuardController;

class ApiBookingController extends ApiGuardController
{

    public function all()
    {
        $books = Booking::all();

        return $this->response->withCollection($books, new BookingTransformer);
    }

    public function showbyid($id)
    {
        try {

            $book = Booking::findOrFail($id);

            return $this->response->withItem($book, new BookingTransformer);

        } catch (ModelNotFoundException $e) {

            return $this->response->errorNotFound();

        }
    }

    public function showbygame($id)
    {
        try {

            $book = Booking::where("id_juego",$id)->get();

            return $this->response->withCollection($book, new BookingTransformer);

        } catch (ModelNotFoundException $e) {

            return $this->response->errorNotFound();

        }
    }

}