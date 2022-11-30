<?php

use Illuminate\Support\Facades\Route;

/* ------------------------------------ API routes ------------------------------------------------------ */

Route::group(['namespace' => 'Api\V1', 'middleware' => ['client']], function () {
    Route::group(['prefix' => '/v1', 'as' => 'api.v1.'], function () {
        Route::post('/personne', 'MainService@storePersonne')->name('storePersonne');
        Route::post('/contrat', 'MainService@storeContrat')->name('storeContrat');
        Route::post('/operation', 'MainService@storeOperation')->name('storeOperation');
    });
});

/* ------------------------------------ Public routes -------------------------------------------------- */
Route::group(['namespace' => 'Api\Auth', 'middleware' => ['json.force']], function () {
    Route::post('/login', 'AuthController@login')->name('api.login');
    Route::post('/logout', 'AuthController@logout')->name('api.logout');
    Route::post('/register', 'AuthController@register')->name('api.register');
    Route::match(['options', 'post'], 'token', 'AccessTokenController@issueToken')->name('api.issue.token');
});
Route::group(['prefix' => '/common', 'as' => 'common.'], function () {
    Route::get('/', 'CommonController@index')->name('index');
    Route::get('/gammes', 'GammeController@index')->name('gammes');
    Route::get('/fournisseurs', 'FournisseurController@index')->name('fournisseurs');
    Route::get('/produits', 'ProduitController@index')->name('produits');
    Route::get('/villes', 'CommonController@getVilles')->name('villes');
    Route::get('/villes/{type_cp}/{cp}', 'CommonController@getVillesByCp')->name('getVillesByCp');
    Route::get('/roles', 'CommonController@getRoles')->name('roles');
    Route::get('/permissions', 'CommonController@getPermissions')->name('permissions');
    Route::get('/permissions/{id}', 'CommonController@getPermissionsByRole')->name('permissionsRole');
});

/* ------------------------------------ Secured routes ------------------------------------------------- */
Route::group(['middleware' => ['auth:api']], function () {
    // ----------------------- Produit
    Route::group(['prefix' => '/produit', 'as' => 'produit.'], function () {
        Route::get('/', 'ProduitController@index')->name('index');
        Route::post('/', 'ProduitController@store')->name('store');
        Route::get('/{id}', 'ProduitController@show')->name('show');
        Route::put('/{id}', 'ProduitController@update')->name('update');
        Route::delete('/{id}', 'ProduitController@delete')->name('delete');
    });

    // ----------------------- Gamme
    Route::group(['prefix' => '/gamme', 'as' => 'gamme.'], function () {
        Route::get('/', 'GammeController@index')->name('index');
        Route::post('/', 'GammeController@store')->name('store');
        Route::get('/{id}', 'GammeController@show')->name('show');
        Route::put('/{id}', 'GammeController@update')->name('update');
        Route::delete('/{id}', 'GammeController@delete')->name('delete');
    });

    // ----------------------- Fournisseur
    Route::group(['prefix' => '/fournisseur', 'as' => 'fournisseur.'], function () {
        Route::get('/', 'FournisseurController@index')->name('index');
        Route::post('/', 'FournisseurController@store')->name('store');
        Route::get('/{id}', 'FournisseurController@show')->name('show');
        Route::put('/{id}', 'FournisseurController@update')->name('update');
        Route::delete('/{id}', 'FournisseurController@delete')->name('delete');
    });

    // ----------------------- Ticket
    Route::group(['prefix' => '/ticket', 'as' => 'ticket.'], function () {
        Route::get('/', 'TicketController@index')->name('index');
        Route::post('/', 'TicketController@store')->name('store');
        Route::get('/{id}', 'TicketController@show')->name('show');
        Route::post('/{id}', 'TicketController@update')->name('update');
        Route::delete('/{id}', 'TicketController@delete')->name('delete');
        Route::get('/documents/{id}', 'TicketController@getDocuments')->name('getDocuments');
    });

    // ----------------------- User
    Route::group(['prefix' => '/users', 'as' => 'user.'], function () {
        Route::get('/', 'UserController@index')->name('index');
        Route::post('/', 'UserController@store')->name('store');
        Route::get('/responsables', 'UserController@getResponsables')->name('responsables');
        Route::get('/gestionnaires', 'UserController@getGestionnaires')->name('gestionnaires');
        Route::get('/me', 'UserController@getUser')->name('getUser');
        Route::post('/me', 'UserController@updateSelf')->name('updateSelf');
        Route::put('/{id}', 'UserController@update')->name('update');
        Route::get('/permissions/{id}', 'UserController@getUserPermissions')->name('permissions');
    });

    // ----------------------- Notifications
    Route::group(['prefix' => '/notif', 'as' => 'notif.'], function () {
        Route::get('/all', 'NotificationController@getAll')->name('getAll');
        Route::get('/unreaded', 'NotificationController@getUnreaded')->name('getUnreaded');
        Route::get('/mark', 'NotificationController@markAsRead')->name('markAsRead');
    });

    // ----------------------- Notifications user
    Route::group(['prefix' => '/notification', 'as' => 'notification.'], function () {
        Route::get('/', 'NotificationUserController@index')->name('index');
        Route::post('/', 'NotificationUserController@store')->name('store');
        Route::get('/{id}', 'NotificationUserController@show')->name('show');
        Route::put('/{id}', 'NotificationUserController@update')->name('update');
        Route::delete('/{id}', 'NotificationUserController@delete')->name('delete');
    });

    // ----------------------- Personne
    Route::group(['prefix' => '/personne', 'as' => 'personne.'], function () {
        Route::get('/', 'PersonneController@index')->name('index');
        Route::post('/', 'PersonneController@store')->name('store');
        Route::get('/{id}', 'PersonneController@show')->name('show');
        Route::put('/{id}', 'PersonneController@update')->name('update');
        Route::get('/numero/{id}', 'PersonneController@getByNumero')->name('getByNumero');
        Route::post('/documents/{id}/{contrat_id}', 'PersonneController@uploadDocuments')->name('documents');
        Route::get('/documents/{id}', 'PersonneController@getDocuments')->name('getDocuments');
        Route::delete('/documents/{id}', 'PersonneController@deleteDocuments')->name('deleteDocuments');
    });

    // ----------------------- Contrat
    Route::group(['prefix' => '/contrat', 'as' => 'contrat.'], function () {
        Route::get('/', 'ContratController@index')->name('index');
        Route::post('/', 'ContratController@store')->name('store');
        Route::post('/{id}', 'ContratController@update')->name('update');
        Route::get('/{id}', 'ContratController@getByNum')->name('getByNum');
        Route::get('/show/{id}', 'ContratController@show')->name('show');
        Route::get('/souscripteur/{id}', 'ContratController@getSouscripteurContrats')->name('show_souscripteurs');
        Route::post('/documents/store', 'ContratController@StoreDocuments')->name('storeDocuments');
        Route::get('/documents/{id}', 'ContratController@getDocuments')->name('getDocuments');
    });

    // ----------------------- Reclamation
    Route::group(['prefix' => '/reclamation', 'as' => 'reclamation.'], function () {
        Route::get('/', 'ReclamationController@index')->name('index');
        Route::post('/', 'ReclamationController@store')->name('store');
        Route::get('/{id}', 'ReclamationController@show')->name('show');
        Route::put('/{id}', 'ReclamationController@update')->name('update');
    });
});
