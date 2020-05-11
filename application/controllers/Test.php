<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function index()
    {
        $this->db->from(TABLE_RND_VC_VARIETY_SELECTION.' vc');
        $this->db->select('COUNT(DISTINCT(vc.variety_id)) total_variety');
        $this->db->join(TABLE_RND_SETUP_VARIETY.' variety','variety.id = vc.variety_id','INNER');
        $this->db->join(TABLE_RND_SETUP_TYPE.' type','type.id = variety.type_id','INNER');
        $this->db->where('type.crop_id',1);
        $this->db->where('vc.year',2020);
        $result=$this->db->get()->row_array();
        echo '<pre>';
        print_r($result);
        echo '</pre>';

    }
}
