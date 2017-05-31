<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('basic.once', function()
{
    //we set this to ensure that the "Set-Cookie" header is not sent
    Config::set('session.driver', 'array');
    
    return Auth::onceBasic("username");
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| API Cache Control
|--------------------------------------------------------------------------
|
| The API Cache Control is responsible for examining the request. This fulter
| Determines if the requested resource is still fresh on the client side if they 
| already have it. If it is still fresh, the filter will return a status 
| informing the client that they the copy they have is still fresh.
|
*/

Route::filter('cache', function()
{
    $resourceURL    = Request::url();
    $resourceType   = APIHelper::getResourceTypeFromURL($resourceURL);
    $resourceID     = APIHelper::getResourceID($resourceURL);
    $queryParams    = APIHelper::getRequestCacheParams();

    $APICacheManager = new APICacheManager($resourceType, $queryParams, $resourceID);    
    $isResourceFresh = $APICacheManager->isResourceFresh();

    if ( $isResourceFresh ){
        // Get the response status indicating requested resource is still fresh.
        $response = Config::get('api_custom_codes.content_not_modified');
        
        $headers = array(
            'Etag'  => Request::header('If-None-Match')
        );
        
        /**
         * return resource not modified status informing the client that the resource is still fresh
         */
        return Response::json($response, $response['httpCode'], $headers, JSON_PRETTY_PRINT);
    }
    
});


/*
|--------------------------------------------------------------------------
| API Response filter
|--------------------------------------------------------------------------
|
| This filter will be fired for all responses sent by the server to the client
| The functions that this filter can perform are listed below:
| 1. Determine response data content type and set it
| 2. Set headers if any.
|
| ...and more.
|
*/
Route::filter('prepare_response', function($route, $request, $response){

    $resourceURL        = Request::url();
    $resourceType       = APIHelper::getResourceTypeFromURL($resourceURL);
    $resourceID         = APIHelper::getResourceID($resourceURL);
    $queryParams        = APIHelper::getRequestCacheParams();
    $APICacheManager    = new APICacheManager($resourceType, $queryParams, $resourceID);
    $headers            = $APICacheManager->getResourceResponseHeaders($resourceType, $resourceID);
    
//    if ( $resourceType === APIResourceType::POSTS ){
//        if (isset($queryParams['date'])){
//            $dateModified   = $queryParams['date'];
//            $originalData   = $response->getOriginalContent();
//            $responseData   = APIPostCacheManager::filterPostsByDateModifid($originalData['posts'], $dateModified);
//            $response->setContent(array('posts' => $responseData));
//        }
//    }
//    $isGzipSupported    = $APICacheManager->isGZipSupported($request);    
    
//    if ( $isGzipSupported ){
//        $response->header('Content-Encoding', 'gzip');
//        
//        if ( $response instanceof Illuminate\Http\Response){
//            $gzippedData = $APICacheManager->getGzippedData(json_encode($response->getOriginalContent()));
//            $response->setContent($gzippedData);
//        }else if ( $response instanceof Illuminate\Http\JsonResponse){
//            $gzippedData = $APICacheManager->getGzippedData(json_encode($response->getData()));
//            $response->setData( $gzippedData );
//        }
//        
//    }
    
    if ( $headers ){
        $response   = APIHelper::setReponseHeaders($response, $headers);
    }
});
