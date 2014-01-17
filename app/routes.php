<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'index', 'uses' => 'HomeController@showIndex'));

Route::post('/meter-readings', array('as' => 'submit-meter-readings', 'uses' => 'MeterController@postMeterReadings'));

Route::get('/last-reading', array('as' => 'last-reading', 'uses' => 'MeterController@getMeterReadings'));

Route::get('/overall', array('as' => 'overall', 'uses' => 'MeterController@getOverall'));

Route::get('/monthly', array('as' => 'monthly', 'uses' => 'MonthlyController@index'));
