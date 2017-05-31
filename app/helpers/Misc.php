<?php

class Misc{
    public static function convertDateToSeconds ( $days, $hours, $mins ){

        $dateInSeonds   = 0;
        $hoursInSeconds = 0;
        $minsInSeconds  = 0;
        $daysInSeconds  = 0;
        
        if ( $days > 0 ){
            $daysInSeconds  = 86400 * $days;            
        }
        
        if ( $hours > 0 ){
            $hoursInSeconds = $hours * 60 * 60;
        }
        
        if ( $mins > 0 ){
            $minsInSeconds  = $mins * 60;
        }
        
        $dateInSeonds   = $daysInSeconds + $hoursInSeconds + $minsInSeconds;
        
        return $dateInSeonds;        
    }
    
    /**
     * This function checks if a file in the passed URL exists by making a curl
     * request to the URL and checking that the response has data.
     * 
     * @param type $fileURL The URL to the image to be checked.
     * @return boolean True if remote file exists, false otherwise.
     */
    public static function checkRemoteImageFileExists( $fileURL, $headers = array() ){        
       
        $remoteFile = static::getRemoteFile($fileURL, $headers);
        
        try{
            // Attempt to create an image from the request response data.
            $image = imagecreatefromstring($remoteFile);
            // check if the image was created successfully, if so, then the remote file
            // exists, return true.
            if ( $image ){
                return true;
            }
        } catch (Exception $ex) {
            // An exception can be thrown if image file cannot be created from passed data.
            // If so, then return false since the url does not point to a valid file URL.
            return false;
        }        
        // If we get t this point, we can assume that creating the image file did not go well.
        // and return false.
        return false;
    }
    
    public static function getRemoteFile( $fileURL, $headers ){
        // Initialise curl with the passed URL
        $curl = curl_init($fileURL);
        // instruct curl to return the response data instead of echo-ring it.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // Make the curl request and save the response data in $data
        
        if (!empty( $headers )){
            foreach ( $headers as $header => $value ){
                $headersString = "{$header}: {$value}";
                if ( key($headers) !== end($headers)){
                    $header .= ',';
                }
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, explode(',', $headersString));
        }
        $data = curl_exec($curl);
        // close the curl resource.
        curl_close($curl); 
        
        return $data;
    }
}

