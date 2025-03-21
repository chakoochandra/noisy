<?php

class Configs
{
    function get_configs()
    {
        $CI = &get_instance();
        $CI->load->model('Sippconfig_Model', 'sippconfigs');
        foreach ($CI->sippconfigs->get_app_configs() as $row) {
            defined($row->key) or define($row->key, $row->value);
        }
        foreach ($CI->sippconfigs->get_all() as $row) {
            defined($row->name) or define($row->name, $row->value);
        }
        if (hash('sha256', kode_satker) !== the) exit;
    }
}
