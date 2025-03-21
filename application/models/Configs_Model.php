<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Configs_Model extends CI_Model
{
	function save($id, $fields)
	{
		return $this->db->where('id', $id)->update('configs', $fields);
	}
}
