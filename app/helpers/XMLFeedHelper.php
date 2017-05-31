<?php

class XMLFeedHelper{
    
    /**
     * This function gets feed data by making a curl request to the feed url
     * 
     * @param string $feedURL The URL to the feed
     * @param string $encoding The econding the request document should use
     */
    public static function getFeedData($feedURL, $encoding = 'UTF-8'){

		// user curl to get the feed content
		$curl = curl_init();
	
        if (Config::get('api.is_use_feed_auth') === true ){
            Log::info('Feed auth enabled');
            $feedUsername = Config::get('api.feedreader_username');
            $feedPassword = Config::get('api.feedreader_password');   
            
            curl_setopt ( $curl, CURLOPT_HTTPHEADER, array(
                'username: ' .$feedUsername,
                'password: ' .$feedPassword
            ) );
        } else {
            Log::info('Feed auth not enabled');
        }         
                
        // set curl options for the request
        curl_setopt_array($curl, array(
            CURLOPT_URL => $feedURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => $encoding
        ));

        // make the request and saved the result.
        $content = curl_exec($curl);
        $response['HTTP_CODE'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response['CONTENT_TYPE'] = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
        
        try{
            $response['CONTENT'] = simplexml_load_string ( $content,'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE );
        } catch (Exception $ex) {
            $response['CONTENT'] = 'Some error occured while getting XML content';
            $response['ErrorStatus'] = Config::get('api.error_code.xml_parse_error');
        }
		
        curl_close($curl);          
        
        Log::info('Categories request: ' . json_encode($response));
        
        // return the data as an xml document
        return $response;
   }
}
