<?php 

class Resource_Comment_Resource_Mysql extends Pimcore_Model_Resource_Mysql_Abstract {
	
	/**
     * Contains all valid columns in the database table
     *
     * @var array
     */
	protected $validColumns = array();
	
	/**
	 * Get the valid columns from the database
	 *
	 * @return void
	 */
	public function init() {
		$data = $this->db->fetchAll("SHOW COLUMNS FROM plugin_commenting_comments");
		foreach ($data as $d) {
			$this->validColumns[] = $d["Field"];
		}
	}
	
	/**
	 * Get the data for the comment from database for the given id
	 * 
	 * @param integer $id
	 * @return Object_Comment
	 */
	public function getById ($id) {

		try {
			$data = $this->db->fetchRow("SELECT * FROM plugin_commenting_comments WHERE id = ?",$id);
			$this->assignVariablesToModel($data);
			
		} 
		catch (Exception $e){}
	}
	
	
	
	/**
	 * Get the data for the object from database for the given name
	 * 
	 * @param string $name
	 * @return void
	 */
	public function save () {
	
		if($this->model->getId()) {
			return $this->model->update();
		}
		return $this->create();
	}
	
	/**
	 * Create a new record for the object in database
	 *
	 * @return boolean
	 */
	public function create () {
		try {
			$this->db->insert("plugin_commenting_comments",array(
				"commentingTargetId" => $this->model->getCommentingTargetId(),
				"userId" => $this->model->getUserId(),
				"data" => $this->model->getData(),
				"date" => $this->model->getDate(),
				"type" => $this->model->getType(),
                "metadata" => $this->model->getMetadata()
			));
			
			$this->model->setId($this->db->lastInsertId());
			
			return $this->save();
			
		}
		catch (Exception $e) {
			throw $e;
		}
		
	}
	
	/**
	 * Save changes to database, it's an good idea to use save() instead
	 * 
	 * @return void
	 */
	public function update () {
		try {
			$data["id"]=$this->model->getId();
			$data["commentingTargetId"]=$this->model->getCommentingTargetId();
			$data["userId"]=$this->model->getUserId();
			$data["date"]=$this->model->getDate();
			$data["type"]= $this->model->getType();
            $data["data"] = $this->model->getData();
            $data["metadata"] = $this->model->getMetadata();
			
			$this->db->update("plugin_commenting_comments",$data,"id='".$this->model->getId()."'");
			
		}
		catch (Exception $e) {
			throw $e;
		}
	}
	
	/**
	 * Deletes object from database
	 *
	 * @return void
	 */
	public function delete () {

		try {
			$this->db->delete("plugin_commenting_comments","id=".$this->model->getId());
		}
		catch (Exception $e) {
			throw $e;
		}

	}
	
	/**
	 * Deletes all comments for the current target
	 * 
	 * @return void
	 */
	public function deleteAllForTarget(){
		if($this->model!=null){
			$targetId = $this->model->getCommentingTargetId();
			if(!empty($targetId)){
				try {
					$this->db->delete("plugin_commenting_comments","id='".$this->model->getCommentingTargetId()."'");
				}
				catch (Exception $e) {
					logger::log(get_class($this).": Could not delete comments for target id [".$this->model->getCommentingTargetId()."]");
					throw $e;
				}	
			}
		}
	}
	
}



?>