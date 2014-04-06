<?php

class Members_Model extends Model {

	public function __construct(){
		parent::__construct();
	}	

	public function get_member_hash($username){
		return $this->_db->select("SELECT memberID,username,password FROM ".PREFIX."members WHERE active='Yes' AND username = :username",array(':username' => $username));
	}

	public function get_memberID($id,$key){
		return $this->_db->select("SELECT memberID,active FROM ".PREFIX."members WHERE memberID = :id AND active = :key",
			array(
				':id' => $id,
				':key' => $key
			));
	}

	public function get_username($username){
		return $this->_db->select("SELECT username FROM ".PREFIX."members WHERE username = :username",array(':username' => $username));
	}

	public function get_email($email){
		return $this->_db->select("SELECT email FROM ".PREFIX."members WHERE email = :email",array(':email' => $email));
	}

	public function insert_member($data){
		$this->_db->insert(PREFIX."members",$data);
		return $this->_db->lastInsertId('memberID');
	}

	public function update_member($data,$where){
		$this->_db->update(PREFIX."members",$data,$where);
	}

}