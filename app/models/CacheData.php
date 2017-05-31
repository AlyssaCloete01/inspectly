<?php

/**
 * Store data to be cached in this object and cache it
 */
class CacheData {

    /**
     * Cache data
     * @var Object
     */
    private $data;

    /**
     * Date time that the data was last updated
     * @var DateTime
     */
    private $lastUpdateTime;
    
    /**
     *  Initialize object with data to be cache
     * @param Object $data
     */
    public function __construct($data = FALSE) {
        if (!empty($data)) {
            $this->data = $data;
        }
        $now = new DateTime();
        $this->lastUpdateTime = $now;
    }

    /**
     *  cached data
     * @return 
     */
    public function getData() {
        return $this->data;
    }

    /**
     *  Set data to be cached
     * @param type $data
     */
    public function setData($data) {
        $this->data = $data;
    }
    
    /**
     *  Last time data was updated
     * @return DateTime
     */
    public function getLastUpdateTime() {
        return $this->lastUpdateTime;
    }

    /**
     *  Set last that the data in cache is updated
     * @param DateTime $DateTime
     */
    public function setLastUpdateTime(DateTime $DateTime) {
        $this->lastUpdateTime = $DateTime;
    }

}
// End CacheData
