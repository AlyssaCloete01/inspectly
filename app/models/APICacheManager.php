<?php

/**
 * This class is used as a cache manager which is responsible for perfoing caching tasks.
 * It achives caching task by mostly delegating to relevant resource cache managers.
 * 
 * @author Themba Malungan <themba@kazazoom.com>
 * @version 1.0.0
 * @since 2015/05/18
 */
class APICacheManager{
    
    public $resouceType;
    public $resourceID;
    public $queryParams;
    
    /**
     * 
     * @param string $resouceType The type of the resource e.g. post, posts, etc
     * @param array $queryParams
     * @param integer $resourceID
     */
    public function __construct( $resouceType, $queryParams, $resourceID = '' ) {
        $this->queryParams = $queryParams;
        $this->resouceType = $resouceType;
        $this->resourceID = $resourceID;        
    }


    /**
     * This function determines if the current resource is fresh on the client side
     * by delegating to the relevant resource cache manager.
     * 
     * @return boolean True if the current resource is still frsh in the client side.
     */
    public function isResourceFresh(){
        // Assume the resource is stale in the client until we determine it's still fresh.
        $isReourceFresh = false;
        // Check the resource type to delegate the task to the appropriete resource cache manager.
        switch ($this->resouceType){
            case APIResourceType::POST:
                $isReourceFresh = APIPostCacheManager::isPostFresh($this->resourceID, $this->queryParams);
                break;
            case APIResourceType::POSTS:
                $queryParams    = APIHelper::getCollectionResourceQueryParams();
                $isReourceFresh = APIPostCacheManager::isPostCollectionFresh( $queryParams );
                break;
            case APIResourceType::QUIZ:
                $isReourceFresh = APIQuizCacheManager::isQuizFresh($this->resourceID, $this->queryParams);
                break;
            case APIResourceType::QUIZZES:
                $queryParams    = APIHelper::getCollectionResourceQueryParams();
                $isReourceFresh = APIQuizCacheManager::isQuizCollectionFresh($queryParams);
                break;
            case APIResourceType::FORM:
                $isReourceFresh = APIFormCacheManager::isFormFresh($this->resourceID, $this->queryParams);
                break;
            case APIResourceType::FORMS:
                $queryParams    = APIHelper::getCollectionResourceQueryParams();
                $isReourceFresh = APIFormCacheManager::isFormCollectionFresh($queryParams);
                break;
            case APIResourceType::POST_CATEGORY:
                $isReourceFresh = APIPostCategoryCacheManager::isPostCategoryFresh($this->resourceID, $this->queryParams);
                break;
            case APIResourceType::POST_CATEGORIES:
                $queryParams    = APIHelper::getCollectionResourceQueryParams();
                $isReourceFresh = APIPostCategoryCacheManager::isPostCategoryCollectionFresh($queryParams);
                break;
            case APIResourceType::MENU:
                $isReourceFresh = APIMenuCacheManager::isMenuFresh($this->resourceID, $this->queryParams);
                break;
            case APIResourceType::MENUS:
                $queryParams    = APIHelper::getCollectionResourceQueryParams();
                $isReourceFresh = APIMenuCacheManager::isMenuCollectionFresh($queryParams);
                break;
            case APIResourceType::FRONT_PAGE:
                $queryParams    = APIHelper::getRequestCacheParams();
                $isReourceFresh = APIFrontPageCacheManager::isFrontPageFresh($queryParams);
                break;
        }
        // Return the determine resource status.
        return $isReourceFresh;
    }
    
    /**
     * This function get the response headers to be used for the current resources.
     * It achieves this by delegating the task to the appropriate resource cache manager.
     * 
     * @return Array/False Returns a key-value pair array with headers to be sent back to client
     */
    public function getResourceResponseHeaders(){
        // Initialise the array of headers to be an empty array
        $headers = array();
        
        // Determine the resource type and delegate the task to relevant resource cache manager.
        switch ($this->resouceType){
            case APIResourceType::POST:
                $headers = APIPostCacheManager::getPostCacheResponceHeaders($this->resourceID);
                break;
            case APIResourceType::POSTS:
                $requestQueryParams = APIHelper::getCollectionResourceQueryParams();
                $headers = APIPostCacheManager::getPostCollectionResponseHeaders($requestQueryParams);
                break;
            case APIResourceType::FORM:
                $headers = APIFormCacheManager::getFormCacheResponceHeaders($this->resourceID);
                break;
            case APIResourceType::FORMS:
                $requestQueryParams = APIHelper::getCollectionResourceQueryParams();
                $headers = APIFormCacheManager::getFormCollectionResponseHeaders($requestQueryParams);
                break;
            case APIResourceType::QUIZ:
                $headers = APIQuizCacheManager::getQuizCacheResponceHeaders($this->resourceID);
                break;
            case APIResourceType::QUIZZES:
                $requestQueryParams = APIHelper::getCollectionResourceQueryParams();
                $headers = APIQuizCacheManager::getQuizCollectionResponseHeaders($requestQueryParams);
                break;
            case APIResourceType::POST_CATEGORY:
                $headers = APIPostCategoryCacheManager::getPostCategoryCacheResponceHeaders($this->resourceID);
                break;
            case APIResourceType::POST_CATEGORIES:
                $requestQueryParams = APIHelper::getCollectionResourceQueryParams();
                $headers = APIPostCacheManager::getPostCollectionResponseHeaders($requestQueryParams);
                break;
            case APIResourceType::MENU:
                $headers = APIMenuCacheManager::getMenuCacheResponceHeaders($this->resourceID);
                break;
            case APIResourceType::MENUS:
                $requestQueryParams = APIHelper::getCollectionResourceQueryParams();
                $headers = APIMenuCacheManager::getMenuCollectionResponseHeaders($requestQueryParams);
                break;
            case APIResourceType::FRONT_PAGE:
                $headers = APIFrontPageCacheManager::getFrontPageReponseHeaders();
                break;
        }
        
        // Return the calculated headers.
        return $headers;
    }
    
    /**
     * This function determines if a request supports gzip encoding.
     * 
     * @param Illuminate/Http/Request $request The laravel request object
     * @return boolean True if the request supports qzip econding
     */
    public function isGZipSupported( $request ){
        // Get the value of the supported content encoding header.
        $header = $request->header('Accept-Encoding');
        // The values is a comma seperated list so we split it into an array of values.
        $supportedEncoding = explode(',', $header);
        
        // Assume gzip is not supported until calculated otherwise.
        $isGZipSupported = false;
        // Determine if gzip is supported by checking is gzip is in the list of 
        // Supported encoding.
        if (is_array($supportedEncoding) && in_array('gzip', $supportedEncoding)){
            $isGZipSupported = true;
        }
        // Return the calculated gzip support value.
        return $isGZipSupported;
    }
    
    /**
     * This function encodes data using gzip alorithm level 9
     * 
     * @param mixed $sourceData The data to be encoded.
     * @return string
     */
    public function getGzippedData( $sourceData ){
        $encodedData = gzencode((string)$sourceData, 9);
        return $encodedData;
    }
}

