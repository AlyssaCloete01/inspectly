<?php

/**
 * This class is used to handle post caching logic. It has all the functions which are
 * needed to fully provided caching mechanics to clients.   
 * 
 * @author Themba Malungan <themba@kazazoom.com>
 * @version 1.0.0
 * @since 2015/05/18
 */
class APIMenuCacheManager{


    public static function isMenuFresh($menuID, $queryParams){
        // set the default status of the content on the requesting client to be stale.
        $isFresh = false;        
        /**
         * @var $menu Menu object with passed id
         */
        $menu = Menu::getMenuByMenuId($menuID); 
        // Get E-tag from query params if it's set
        $etag = isset($queryParams['etag']) ? $queryParams['etag'] : false;
        
        // check if e-tag was passed and compare with saved tag 
        if ( $etag && ($menu instanceof Menu)){
            // E-tag was set, check if passed e-tag is the same as one calculated for the menu.
            if (strcmp($etag, $menu->checksum) === 0 ){
                $isFresh = true;
            }
        }
        // return the determine content status
        return $isFresh;
    }

    public static function getMenuCacheResponceHeaders( $menuID ){
        /**
         * @var $menu Menu object with passed id
         */
        $menu = Menu::getMenuByMenuId( $menuID );
        
        // get the menu expiry
        $maxAge = MenuHelper::getMenusMaxAge();
        
        // Check if menu with passed id was found and set headers if so
        if ( $menu instanceof Menu){
            $headers = array(
                'ETag'              => $menu->checksum,
                'Cache-Control'     => 'max-age=' . $maxAge . ',public'
            );
            
            // return the set array of headers. These will be used for caching.
            return $headers;
        } else {
            // return false since we can not set headers for a non existent resource.
            return false;
        }
    }
    
    /**
     * This function determines if the requested menu list of still in sync with data
     * of the requesting client side.
     * 
     * @param array $queryParams List of request query params.
     * @return boolean True of the requested menu collection has not been modified and still
     *      in sync with data on the requesting client.
     */
    public static function isMenuCollectionFresh( $queryParams ){
        
        $offset     = isset($queryParams['offset']) ? $queryParams['offset'] : 0;
        $limit      = isset($queryParams['limit']) ? $queryParams['limit'] : 10;
        $checksum   = isset($queryParams['checksum']) ? $queryParams['checksum'] : 0;        
        
        $menuList   = Menu::getMenus();
        $menuPage   = array_slice($menuList, $offset, $limit);
        $pageHash   = MenuHelper::getMenuCollectionHash( $menuPage );

        $isCollectionFresh = ($pageHash === $checksum) ? true : false;
        
        return $isCollectionFresh;
    }
    
    /**
     * This function calculates posts request response headers.
     * 
     * @param type $requestQueryParams The request params passed when requesting posts data.
     * @return array Array of headers to be sent back to the client with response data.
     */
    public static function getMenuCollectionResponseHeaders( $requestQueryParams ){
        
        $offset     = isset($requestQueryParams['offset']) ? $requestQueryParams['offset'] : 0;
        $limit      = isset($requestQueryParams['limit']) ? $requestQueryParams['limit'] : 10;
        
        $menuList       = Menu::getMenus();
        $postCollection = array_slice($menuList, $offset, $limit);
        // get the menu expiry
        $maxAge         = MenuHelper::getMenusMaxAge();
        
        $headers = array(
            'ETag'  => MenuHelper::getMenuCollectionHash($postCollection),
            'Cache-Control'     => 'max-age=' . $maxAge . ', public'
        );
        // Return the calculated response headers.
        return $headers;
    }
       
}

