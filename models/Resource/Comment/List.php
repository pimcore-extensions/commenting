<?php 
class Resource_Comment_List extends Pimcore_Model_List_Abstract {

/**
	 * @var array
	 */
	public $comments = array();
	
	/**
	 * 
	 * @var Object_Abstract
	 */
	public $object;
	
	/**
	 * @var array
	 */
	public $validOrderKeys = array(
		"date",
		"user",
		"object",
		"data",
        "metadata"
	);
	
	
	
	/**
	 * @param boolean $objectTypeObject
	 * @return void
	 */
	public function __construct() {
		$this->initResource("Resource_Comment_List");
		
	}
	
	/**
	 * @param string $key
	 * @return boolean
	 */
	public function isValidOrderKey ($key) {
		if(in_array($key,$this->validOrderKeys)) {
			return true;
		}
		return false;
	}
	
	/**
	 * @return array
	 */
	public function getComments() {
		return $this->comments;
	}
	
	/**
	 * @param string $objects
	 * @return void
	 */
	public function setComments($comments) {
		$this->comments = $comments;
	}
	
	/**
	 * @return Object_Abstract $object
	 */
	public function getObject(){
		return $this->object;
	}
	

}