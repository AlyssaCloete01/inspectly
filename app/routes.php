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

/**
 * This version of the API
 */
$apiVersion = 1;

/*
 * Define API route group prefixed with the API version
 * Example Base URL: http://example.com/api/v1 (assumming the code for the service is under a folder "api" under the service root directory)
 */
Route::group(array('prefix' => "", 'before' => 'basic.once'), function(){

    Route::get('versionupdates', array('uses' => 'VersionController@getMinimumSupportedVersions'));
    
    //------------------------------------------Inspectly ENDPOINTS-----------------------------------------------//
    

	//======= END: DEFINE CUSTOM POST ROUTES =============
	//====================================================

});
