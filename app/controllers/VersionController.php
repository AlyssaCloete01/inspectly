<?php
/**
 * Handles the version requirements for the API
 *
 * @author Mongezi
 */
class VersionController extends \BaseController{
    
    /**
     * This endopint returns the minimum supported versions
     * for the various service consumer applications
     * 
     * @return string The JSON response
     */
    public function getMinimumSupportedVersions(){
        
        //get the application versions that are supported
        //Android
        $androidMinimum = Config::get('api.android_app_minimum_version');
        
        $response_array = array(
            'androidVersion'    =>  $androidMinimum,
        );
        
        return Response::json($response_array, 200, [], JSON_PRETTY_PRINT);
    }
}
