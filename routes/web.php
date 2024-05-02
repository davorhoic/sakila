<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


require __DIR__ . '/auth.php';

/*
Route::get('/', function () {  //anonimna funkcija, closure
    return view('welcome');
});
*/

//Default dir za view je /resources/view
// vraća nam view /resources/view/welcome.blade.php
Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        // ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('dash', 'dashboard')  //uri, view
        // ->middleware(['auth', 'verified']) // koristi auth middleware, samo logirani
        ->name('dashboard'); // svaku rutu je pozeljno imenovati
});
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



// Primjeri ruta
use Illuminate\Http\Request;
// /greeting -> anonimnu callback funkciju (closure func ) koja ispisuje neki txt
// svrha ovoh primjera je demnonstrirati DI Dependancy injection
Route::get('/greeting', function (Request $request) {
    if ($request->route()->named('greeting')) {
          return 'Hello World, tvoja IP adresa je:' . $request->getClientIp()
            . ' Tvoj querystring je: ' . $request->getQueryString()
         ;
    } else {
        return 'Bez pozdrava, tvoj IP je:' . $request->getClientIp()
            . ' Tvoja ruta je:' . $request->route()->getName();
    }
})->name('greeting2');

Route::controller(UserController::class)->middleware(['throttle:ime-limkitera'])->group(function () {
    // /user -> poziva kontroller akciju index() koja vraća raw text
    Route::get('/user',  'index')->name('korisnik');
    // /userjson -> poziva kontroller akciju indexJson() koja vraća JSON 
    Route::get('/userjson',  'indexjson')->name('test-userjson-rute');
    
    // /user -> poziva kontroller akciju sviuseri() koja vraća zapis iz baze podataka raw metodom
    Route::get('/sviuseri',  'sviuseri')->name('sviuseri');
    
})->name('korisnici');

// /userjson -> poziva kontroller akciju action1() koja preusmjerava na action2 
Route::get('/user-redirect2action', [UserController::class, 'action1'])->name('test-redirekcija-action1');
Route::get('/user-action2', [UserController::class, 'action2']);

Route::match(['get', 'post'], '/testmatch', function () {
    return 'Hello World iz match rute';
})->name('test-match-rute');

//Invoke-WebRequest -Uri http://localhost:8000/testpost -Method POST
Route::post('/testpost', function () {
    return 'Hello World iz post rute';
})->name('test-post-rute');

//Invoke-WebRequest -Uri http://localhost:8000/testany -Method PUT
Route::any('/testany',  function () {
    return 'Hello World iz any rute';
})->name('test-any-rute');

//TODO napravi csrf primjer forme u viewu

// Redirekcije
Route::redirect('/here', '/there'); //302 temporarily. 
Route::redirect('/hereperm', '/thereperm', 303);
Route::permanentRedirect('/herpermanent', '/therepermanent'); // search engine remember

Route::any('/preusmjeri',  function () {
    return redirect()->route('profile');
})->name('test-preusmjerenje');



// view forme za test CSRF
Route::view('/forma', 'forma')->name('forma');
Route::view('/formatest', 'forma', ['name' => 'Taylor'])->name('forma-test');

//Rute s parametrima
//http://localhost:8000/user/123
Route::get('/user/{id}', function (string $id) {
    return 'User ' . $id;
})->where('id', '[0-9]+');

// ograničenje na numericke parametre je postavljeno u app/providers/appserviceprovider.php
Route::get('/usernum/{id}', function (string $id) {
    return 'Usernum ' . $id;
});

Route::get('/users/{id}', function (string $id) {
    return 'Users ' . $id;
    //})->whereAlphaNumeric('id')->name("users-ruta");
})->whereAlpha('id')->name("users-ruta");  //http://localhost:8000/users/qtest

//  http://localhost:8000/user/123/pero/email/marko
Route::get('/user/{id}/{ime}/email/{email}', function (string $id, string $name, string $email) {
    return 'User ' . $id . ' Ime: ' . $name . ' Email: ' . $email; // User 123 Ime: pero Email: marko
})->where(['id' => '[0-9]+', 'name' => '[A-Za-z]+', 'email' => '[A-Za-z]+@+[A-Za-z]+.+[A-Za-z]']);

//  http://localhost:8000/user/123/pero/marko
Route::get('/user/{id}/ime/{ime?}', function (string $id, ?string $name = 'John Doe') {
    return 'User ' . $id . ' Ime: ' . $name; // User 123 Ime: pero Email: marko
});


// poddomena
// http://ddd.localhost:8000/duser/123
Route::domain('{poddomena}.localhost')->group(function () {
    Route::get('duser/{id}', function (string $poddomena, string $id) {
        return 'Users ' . $id . ' tvoja domena je:' . $poddomena;
    });
});

// Prefix
//  GET|HEAD   admin/things .......................................... admin.thingovi › UserController
//  GET|HEAD   admin/users ........................................... admin.useri › UserController
Route::prefix('admin')->name('admin.')->controller(UserController::class)->group(function () {
    Route::get('/users', function () {
        // Matches The "/admin/users" URL
        return 1;
    })->name('useri');
    Route::get('/things', function () {
        // Matches The "/admin/things" URL
        return 2;
    })->name('thingovi');
});
