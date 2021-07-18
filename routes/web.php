<?php

use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$client = ClientBuilder::create()
    ->setHosts(['localhost:9200'])
    ->build();
//$result = $client->info();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product', function () use ($client) {
    $params = [
        "index" => "products",
        "id" => "12345",
    ];
    $result = $client->get($params);
    dd($result);
    return view('welcome');
});

Route::get('/product/error', function () use ($client) {
    $params = [
        "index" => "products",
        "id" => "123456",
    ];
    try {
        $result = $client->get($params);
        dd($result);
    } catch (Missing404Exception $e) {
        dd("Document Not Found", $e->getMessage());
    }
});

Route::get("/save", function () use ($client) {
    $params = [
        "index" => "products",
        "id" => "12345",
        "body" => [
            "title" => "Ileya New Clothes",
            "currencies" => [
                "accepts" => [
                    "NGN",
                    "KES",
                    "USD",
                ],
                "default" => "NGN"
            ]
        ],
    ];
    $result = $client->index($params);
    dd($result);
});

Route::get("/update", function () use ($client) {
    $params = [
        "index" => "products",
        "id" => "12345",
        "body" => [
            "doc" => [
                "title" => "Salah Clothes",
                "currencies" => [
                    "accepts" => [
                        "GBP",
                        "USD",
                    ],
                    "default" => "NGN"
                ]
            ]
        ],
    ];
    $result = $client->update($params);
    dd($result);
});

Route::get("/delete", function () use ($client) {
    $params = [
        "index" => "products",
        "id" => "1234567"
    ];
    try {
        $result = $client->delete($params);
        dd($result);
    } catch (Missing404Exception $e) {
        dd("Error Message", $e->getMessage());
    }
});

Route::get("/search", function () use ($client) {
    $params = [
        "index" => "products",
        "body" => [
            "query" => [
                "match" => [
                    "accepts" => "GBP"
                ]
            ]
        ]
    ];
    try {
        $result = $client->search($params);
        dd($result);
    } catch (Missing404Exception $e) {
        dd("Error Message", $e->getMessage());
    }
});
