<?php
/**
 * Define an error handler to execute if users are trying to access
 * a resource with the wrong method, e.g, a resource accessed via a GET where
 * as it's meant only for a POST request is a violation of the REST api
 */
App::error(function(BadMethodCallException $BadMethodCallException)
{
    Log::error($BadMethodCallException);
    $errorResponse = Config::get('api_custom_codes.resource_method_not_allowed');
    $httpCode = $errorResponse['httpCode'];
    unset($errorResponse['httpCode']);
    return Response::json($errorResponse, $httpCode, [], JSON_PRETTY_PRINT);
});
/*
 * Handle same error as above
 */
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
App::error(function(MethodNotAllowedHttpException $MethodNotAllowedHttpException)
{
    Log::error($MethodNotAllowedHttpException);
    $errorResponse = Config::get('api_custom_codes.resource_method_not_allowed');
    $httpCode = $errorResponse['httpCode'];
    unset($errorResponse['httpCode']);
    return Response::json($errorResponse, $httpCode, [], JSON_PRETTY_PRINT);
});


/**
 * Handle route not defined errors
 */
App::missing(function($exception)
{
    $errorResponse = Config::get('api_custom_codes.resource_method_not_allowed');
    $httpCode = $errorResponse['httpCode'];
    unset($errorResponse['httpCode']);
    return Response::json($errorResponse, $httpCode, [], JSON_PRETTY_PRINT);
});