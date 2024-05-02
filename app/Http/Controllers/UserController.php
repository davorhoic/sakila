<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public static function index()
     {
//         $rutica = ; // Illuminate\Routing\Route
// $name = Route::currentRouteName(); // string
// $action = Route::currentRouteAction(); // string

        return "ja sam  /app/http/Usercontroller::index() :)"
       // ." current route: ".Route::current()->
        ." current action ".Route::currentRouteName()
        ." current route name ". Route::currentRouteAction();
        ;

    }
//Route::get('/sviuseri') -> http://localhost:8000/sviuseri
    public static function sviuseri(Request $request):View{
       // $users = DB::select('select * from users where active = ?', [1]);
        $users = DB::select('select * from users where remember_token = ? OR remember_token IS NULL', [1]);
        return view('korisnici.index', ['users' => $users]);
    }
    public static function indexJson()
    {

        return response()->json(array('comment' => "ja sam  /app/http/Usercontroller::index() :)"));
    }   
    /*
     http://localhost:8000/user-redirect2action
     */
     public static function action1()
    {
        // http://localhost:8000/user-action2?jedan=dva
        return response()->redirectToAction([UserController::class, 'action2'], parameters:["jedan"=>"dva"]);

       //MOŽE I OVAKO:
       // return redirect()->action([UserController::class, 'action2']);
    }
         public static function action2(Request $request)
    {
        // http://localhost:8000/user-action2?jedan=dva
        //dd($request->all());  // display and die()
        //dd($request->jedan); // dohvaćam varijablu iz querystringa
        return "ja sam  content iz /app/http/Usercontroller::action2() :)";
    }
}
