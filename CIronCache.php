<?php
/**
 * Extends the Yii class 'CCache' to store cached data in Iron Cache.
 *
 * @author Aaron Francis <aarondfrancis@gmail.com>
 * @link https://github.com/aarondfrancis
 * 
 * Extracted and modified to stand alone from the YiiIron extension by John Eskilsson
 * @author John Eskilsson <john.eskilsson@gmail.com>
 * @link https://github.com/br0sk/yiiron
 * @link http://br0sk.blogspot.co.uk/
 *  
 */
class CIronCache extends CCache
{
	/**
	 * @var string name of the Iron Cache. You can have several cache "buckets" in Iron Cache.
   * This is the name of the "bucket" and it will be created automatically if there is no bucket with that
   * name already.
	 */
	public $cacheName = 'yiiiron_cache';
	
	/**
	 * @var string IronCache token. getenv('IRON_CACHE_TOKEN') if you're on heroku
	 */
	public $token;
	
	/**
	 * @var string IronCache project id. getenv('IRON_CACHE_PROJECT_ID') if you're on heroku
	 */
	public $project_id;
	
	/**
	 * @var cache instance
	 */
	 protected $_yiiron;


  /**
   * Here we connect the Iron Cache to prepare for setting, getting or deleting cache entries.
   */
  public function init()
  {
    parent::init();
		require_once "IronCache/IronCore.class.php";
		require_once "IronCache/IronCache.class.php";

		$this->_yiiron = new IronCache(array(
	    'token' => $this->token,
	    'project_id' => $this->project_id
		), $this->cacheName);
		
  }

	public function setCacheName($cacheName)
	{
		$this->cacheName = $cacheName;
		$this->_yiiron->setCacheName($this->cacheName);
	}

	/**
	 * Retrieves a value from cache with a specified key.
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key A unique key identifying the cached value
	 * @return string The value stored in cache, false if the value is not in the cache or expired.
	 */
	public function getValue($key)
	{
    $cacheItem = $this->_yiiron->get($key);
		if ($cacheItem != null && $cacheItem->value != null)
			return $cacheItem->value;
    else
      return false;
	}

	/**
	 * Stores a value identified by a key in cache.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * @param string $key the key identifying the value to be cached
	 * @param string $value the value to be cached
	 * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	public function setValue($key,$value,$expire=2592000)
	{
    try{
      $cacheResult = $this->_yiiron->put($key, array(
				'value'=>$value,
				'expires_in'=>$expire
			));
      return true;
    }
    catch(Exception $e){
      Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
      return false;
    }
	}

	/**
	 * Stores a value identified by a key into cache if the cache does not contain this key.
	 * This is the implementation of the method declared in the parent class.
	 *
	 * If the key exists the value will be updated, otherwise inserted
	 *
	 * @param string $key The key identifying the value to be cached
	 * @param string $value The value to be cached
	 * @param integer $expire The number of seconds in which the cached value will expire. 0 means never expire.
	 * @return boolean true if the value is successfully stored into cache, false otherwise
	 */
	protected function addValue($key,$value,$expire=0)
	{
    //Same implementation as setValue, Iron Cache handles this
    return $this->setValue($key, $value, $expire);
  }

	/**
	 * Deletes a value with the specified key from cache
	 * This is the implementation of the method declared in the parent class.
	 * @param string $key The key of the value to be deleted
	 * @return boolean If no error happens during deletion. If something goes wrong we return false.
	 */
	protected function deleteValue($key)
	{
    try{
      $this->_yiiron->delete($this->cacheName, $key);
      return true;
    }
    catch(Exception $e){
      Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
      return false;
    }
	}

	/**
	 * Deletes all values from cache.
	 * This is the implementation of the method declared in the parent class.
	 * @return boolean Whether the flush operation was successful.
	 */
	protected function flushValues()
	{	
    try{
      $this->_yiiron->clear($this->cacheName);
      return true;
    }
    catch(Exception $e){
      Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
      return false;
    }
	}
}