<?php

return array(
    /**
     * Define the base URL for the api
     */
    'api_base_url'                  => 'inspectlyqa.kazazoom.com',
    
    /**
     * Define the base URL for the api
     */
    'agents_url'                   => 'http://inspectlyqa.kazazoom.com/mxamplify/Agent/GetAgent/',
    
    /**
     * Define API version number
     */
    'version'                       => 'v1_0',
    
    /**
     * Custom response code to be used together with http codes
     * This are so we can better documents response status
     */
    'error_codes'                    => array(
        
        'xml_parse_error'               => 5001      
        
    ),
    /*
     * Config that tells the app whether or not to use caching
     */
    'is_use_cache'         => true,
    
    /**
     * Android App minimum supported version 
     */
    'android_app_minimum_version'   =>  "1",
    
    /**
     * HTTP Basic Auth username
     */
    'http_basic_auth_username'      =>  "inspectly-api-user",
    
    /**
     * HTTP Basic Auth password
     */
    'http_basic_auth_password'      =>  "luxemberg35",
    
);

