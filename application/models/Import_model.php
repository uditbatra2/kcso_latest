<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_model extends CI_Model {

    private $_batchImport;
	private $_batchUpdateImport;

    public function setBatchImport($batchImport) {
        $this->_batchImport = $batchImport;
    }
	
	
    public function setUpdateBatchImport($batchImport) {
        $this->_batchUpdateImport = $batchImport;
    }

    // save data
    public function importData($table_name) {
        $data = $this->_batchImport;
        $this->db->insert_batch($table_name, $data);
    }
	
	//update
	public function updateImportData($table_name) {
        $data = $this->_batchUpdateImport;
        $this->db->update_batch($table_name, $data, 'id');
    }
}

?>