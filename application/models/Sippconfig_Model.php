<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sippconfig_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->db_sipp = $this->load->database('db_sipp', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
	}

	function get_all()
	{
		return $this->db_sipp->from('sys_config')->get()->result();
	}

	function get_app_configs()
	{
		return $this->db->from($this->db->database . '.' . TBL_CONFIGS)->order_by('category ASC, key ASC, value ASC')->get()->result();
	}
}
