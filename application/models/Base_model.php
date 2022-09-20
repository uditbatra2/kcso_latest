<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_model extends CI_Model {
	
	public function __construct(){
		parent::__construct();
		// Your own constructor code
		$this->db->cache_delete_all();
	}
	//get all list without limit
	public function getAllRows($table,$order_by='id DESC',$where=array(), $select_column_name=null){
		// Turn caching on
	  $this->db->cache_on();
	  if(isset($select_column_name) && !empty($select_column_name)){
	     $this->db->select($select_column_name);
	   }
		if(isset($where) && !empty($where)){
			$this->db->where($where);
		}
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		$query = $this->db->get($table);
		  //echo $this->db->last_query();
		if($query->num_rows()>0){
			return $query->result();
		}
	}
	//get a one row data
	public function getOneRecordWithWhere($table=null,$where_con=array(), $select_column_name=null) {
		// Turn caching on
	  $this->db->cache_on();
		$this->db->select($select_column_name);
		$this->db->where($where_con);
		$this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
		$query = $this->db->get($table);
		//echo $this->db->last_query();
        if($query->num_rows()>0){
			return $query->row();
		}
	}
	//get a one row data
	public function getOneRecord($table=null,$field, $param, $select_column_name=null) {
		// Turn caching on
	        $this->db->cache_on();
		$this->db->select($select_column_name);
		$this->db->where($field,$param);
		$this->db->limit(1);// only apply if you have more than same id in your table othre wise comment this line
		$query = $this->db->get($table);
		// echo $this->db->last_query();die;
                if($query->num_rows()>0){
	         return $query->row();
		}
	}
	//get all data with where clause
	public function getRowWhere($table, $field, $param, $order_by='id DESC'){
		// Turn caching on
	  $this->db->cache_on();
		$this->db->where($field,$param);
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		$query=$this->db->get($table);
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			// return result set as an associative array
			return $query->result();
		}
	}
	//get all data with limit
	public function getAllRowsWithLimit($table,$where_condition=array(), $order_by='id DESC', $limit=10,$where_in_data=array(),$where_in_cloumn=''){
		// Turn caching on
	  $this->db->cache_on();
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		if(isset($where_in_data) && !empty($where_in_data)){
			$this->db->where_in($where_in_cloumn, $where_in_data);
		}
		if(isset($where_condition) && !empty($where_condition)){
			$this->db->where($where_condition);
		} 
		$query=$this->db->get($table, $limit);
		 //echo $this->db->last_query();
		if($query->num_rows()>0){
			// return result set as an associative array
			return $query->result();
		}
	}
	//get all data with limit
	public function getAllRowsWithWhereIn($table,$where_in_cloumn,$where_in_data,$where_condition=array(), $order_by='id DESC'){
		// Turn caching on
		$this->db->cache_on();
		if(isset($where_in_data) && !empty($where_in_data)){
			$this->db->where_in($where_in_cloumn, $where_in_data);
		}
		if(isset($where_condition) && !empty($where_condition)){
			$this->db->where($where_condition);
		} 
        if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}		
		$query=$this->db->get($table);
		 //echo $this->db->last_query();
		if($query->num_rows()>0){
			// return result set as an associative array
			return $query->result();
		}
	}
	//get all data with limit or offset
	public function getAllRowsWithLimitOffset($table, $order_by='id DESC', $limit='', $offset=''){
		// Turn caching on
		$this->db->cache_on();
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		$query=$this->db->get($table, $limit, $offset);
		//echo $this->db->last_query();
		if($query->num_rows()>0){
			// return result set as an associative array
			return $query->result();
		}
	}
	// get total number of rows
	public function getNumRows($table=null,$where_condition=array()){
		// Turn caching on
		$this->db->cache_on();
		if(isset($where_condition) && !empty($where_condition)){
			$this->db->where($where_condition);
		}
		$q = $this->db->get($table);
		return $q->num_rows();
	}
	// get total number of rows with conditions;
	public function getNumRowswithLike($table, $like=array(), $order_by='id DESC'){
		// Turn caching on
		$this->db->cache_on();
		$this->db->count_all_results($table);  // Produces an integer, like 25
		if(isset($like) && !empty($like)){
			$this->db->like($like);
		}
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		$this->db->from($table);
		//echo $this->db->last_query();
		return $this->db->count_all_results(); // Produces an integer, like 17
	}
    //get total number of rows with where clause
	public function getNumRowswithWhere($table, $where_con=array(), $order_by='id DESC'){
		// Turn caching on
		$this->db->cache_on();
		if(isset($where_con) && !empty($where_con)){
			$this->db->where($where_con);
		}
		if(isset($order_by) && !empty($order_by)){
			$this->db->order_by($order_by);
		}
		$this->db->from($table);
		//echo $this->db->last_query();
		return $this->db->count_all_results(); // Produces an integer, like 17
	}		
	//insert all data
	public function insert_entry($table=null, $insert_data=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->insert($table,$insert_data);
		return $this->db->insert_id();
	}
	//insert in batch list
	public function insert_multiple_entry($table=null, $insert_batch_data=array()){
	  // Turn caching off for this one query
		$this->db->cache_off();
	  $this->db->insert_batch($table, $insert_batch_data);
	  return $this->db->affected_rows() > 0 ? true : false ;	  
	}
	//update all data
	public function update_entry($table=null, $update_data=array(), $where_conditions=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->update($table, $update_data, $where_conditions);
		return $this->db->affected_rows() == 1 ? true : false ;
	}
	//update in batch list
	public function update_multiple_entry($table=null, $update_data=array(), $where_column_name=null){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->update_batch($table, $update_data, $where_column_name);
		return $this->db->affected_rows() > 0 ? true : false ;
	}
	//delete data with where conditions
	public function deleteWithWhereConditions($table=null, $where_conditions=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->delete($table, $where_conditions);
		return $this->db->affected_rows() == 1 ? true : false ;
	}
	//delete with in conditions
	public function deleteWithWhereInConditions($table=null, $field, $ids_exp=array(),$where_condition=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		if(isset($where_condition) && !empty($where_condition)){
			$this->db->where($where_condition);
		} 
		if(count($ids_exp) > 0 && !empty($ids_exp)){
			$this->db->where_in($field,$ids_exp);//
		
           $this->db->delete($table);
		}
       return $this->db->affected_rows() == 1 ? true : false ;
	}
	//delete data with muliple tables where conditions
	public function deleteWithMultipleTable($table=array(), $where_conditions=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->delete($table, $where_conditions);
		//echo $this->db->last_query();
		return $this->db->affected_rows() == 1 ? true : false ;
	}
	//truncate table
	public function truncateWithTable($table=array()){
		// Turn caching off for this one query
		$this->db->cache_off();
		$this->db->truncate($table);
		//echo $this->db->last_query();
		return $this->db->affected_rows() > 0 ? true : false ;
	}
        //update all data
	
}
