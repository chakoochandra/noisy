<?php

class Configs
{
    function get_configs()
    {
        $CI = &get_instance();
        $CI->load->model('Sippconfig_Model', 'sippconfigs');
        foreach ($CI->sippconfigs->get_all() as $row) {
            if (isset($row->key)) {
                defined($row->key) or define($row->key, $row->value);
            } else {
                defined($row->name) or define($row->name, $row->value);
            }
        }
        if (hash('sha256', kode_satker) !== the) exit;
    }
}
