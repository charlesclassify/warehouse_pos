<?php

class Unit_model extends CI_Model
{
    function get_all_unit()
    {
        $this->db->where('isCancel', 'no');
        $query = $this->db->get('unit');
        $unit = $query->result();

        return $unit;
    }
    public function insert_added_unit()
    {
        $unit = (string) $this->input->post('unit');

        $data = array(
            'unit' => $unit,
        );

        $response = $this->db->insert('unit', $data);

        if ($response) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function update_added_unit()
    {
        $unit_id = (int) $this->input->post('unit_id');
        $unit = (string) $this->input->post('unit');

        $data = array(
            'unit' => $unit,
        );

        $this->db->where('unit_id', $unit_id);
        $response = $this->db->update('unit', $data);

        if ($response) {
            return $unit_id;
        } else {
            return FALSE;
        }
    }
    public function get_unit($unit_id)
    {
        $this->db->where('unit_id', $unit_id);
        $query = $this->db->get('unit');
        $row = $query->row();

        return $row;
    }
    public function delete_unit($id)
    {
        $data = array(
            'isCancel' => 'yes'
        );
        $this->db->where('unit_id', $id);
        $response = $this->db->update('unit', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
}
