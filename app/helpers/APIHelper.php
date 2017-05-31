<?php

class APIHelper{
    
    const POSTS_API_URL_REGEX   = '/posts/';
    const POST_API_URL_REGEX    = '/posts\/[0-9]{1,}$/';
    const QUIZZES_API_URL_REGEX = '/quizzes/';
    const QUIZ_API_URL_REGEX    = '/quizzes\/[0-9]{1,}$/';
    const FORMS_API_URL_REGEX   = '/forms/';
    const FORM_API_URL_REGEX    = '/forms\/[0-9]{1,}$/';
    const POST_CATEGORY_URL_REGEX   = '/post_categories\/[0-9]{1,}/';
    const POST_CATEGORIES_URL_REGEX = '/post_categories$/';
    const MENU_API_URL_REGEX        = '/menus\/[0-9]{1,}/';
    const MENUS_API_URL_REGEX       = '/menus$/';
    const FRONT_PAGE_API_URL_REGEX  = '/layouts\/frontpage/';
    
    /**
     *  Decide whether to use cache based on a number of factors, like cache driver in config
     * @return boolean
     */
    public static function isUseCache() {
        // By default assume we are not caching
        $isUseCache = false;
        // Get the driver configured for this app to use
        $cacheDriver = Config::get('cache.driver');
        /*
         * Use cache if config says it must be used, and if the number of memcache servers configured is > 0
         */
        if(Config::get('api.is_use_cache')) {
            if($cacheDriver == 'memcached') {
                $memcacheHostCount = is_array(Config::get('cache.memcached')) ? count(Config::get('cache.memcached')) : 0;
                if($memcacheHostCount > 0) {
                    $isUseCache = true;
                }
            } elseif(!empty ($cacheDriver)) {
                $isUseCache = true;
            }// End if
        }
        return $isUseCache;
    }
    
    public static function getResourceTypeFromURL($url){
        $resourceType = false;
        
        switch (true){
            case preg_match(self::POST_API_URL_REGEX, $url):
                $resourceType = APIResourceType::POST;break; 
            case preg_match(self::POSTS_API_URL_REGEX, $url):
                $resourceType = APIResourceType::POSTS;break;  
            case preg_match(self::QUIZ_API_URL_REGEX, $url):
                $resourceType = APIResourceType::QUIZ;break;
            case preg_match(self::QUIZZES_API_URL_REGEX, $url):
                $resourceType = APIResourceType::QUIZZES;break;
            case preg_match(self::FORM_API_URL_REGEX, $url):
                $resourceType = APIResourceType::FORM;break;
            case preg_match(self::FORMS_API_URL_REGEX, $url):
                $resourceType = APIResourceType::FORMS;break;
            case preg_match(self::POST_CATEGORY_URL_REGEX, $url);
                $resourceType = APIResourceType::POST_CATEGORY;break;
            case preg_match(self::POST_CATEGORIES_URL_REGEX, $url):
                $resourceType = APIResourceType::POST_CATEGORIES;break;
            case preg_match(self::MENU_API_URL_REGEX, $url);
                $resourceType = APIResourceType::MENU;break;
            case preg_match(self::MENUS_API_URL_REGEX, $url):
                $resourceType = APIResourceType::MENUS;break;
            case preg_match(self::FRONT_PAGE_API_URL_REGEX, $url);
                $resourceType = APIResourceType::FRONT_PAGE;
        }
        
        return $resourceType;
    }
    
    public static function getResourceID( $resourceURL ){
        
        preg_match('/[a-zA-Z]\/([0-9]{1,})/', $resourceURL, $matches);
    
        $resourceID = 0;
        if (is_array($matches) && !empty($matches)){
            $resourceID = intval($matches[1]);
        }
        
        return $resourceID;    
    }
    
    public static function getRequestCacheParams(){
        // Get cache prams from request headers
        $queryParams = array(
            'etag'  => Request::header('If-None-Match'),
            'date'  => Request::header('If-Modified-Since')
        );
        
        return $queryParams;
    }
    
    public static function setReponseHeaders($response, $headers){
        
        // Loop through all the headers and set them on the response object
        foreach ($headers as $headerKey => $headerValue){
            $response->header($headerKey, $headerValue);
        }
        
        // return the resonse with set headers
        return $response;
    }
    
        /**
     * This function returns an array of query params extracted from URL get query params.
     * 
     * @return array Array of query params which are extracted from URL query params
     */
    public static function getCollectionResourceQueryParams(){
        
        $offset     = Request::input('offset', 0);
        $limit      = Request::input('limit', 10);
        $checksum   = Request::header('If-None-Match');
        $requestedFields = Request::input('fields');
        
        $queryParams = array(
            'offset'    => $offset,
            'limit'     => $limit,
            'checksum'  => $checksum,
            'fields'    => $requestedFields
        );
        
        return $queryParams;
    }
    
    public static function processImageURL( $imageURL ){
        
        $fetchimageServiceURL = Config::get('api.fetchimage_url');
        $toRemovePrefix = Config::get('api.image_prefix');
        $imageURL = str_replace($toRemovePrefix, '', $imageURL);
        if (!$imageURL){
            return null;
        }
        
        $imageURL = $fetchimageServiceURL . '?imagepath=' . $imageURL;
        
        return $imageURL;
    }
    
}

