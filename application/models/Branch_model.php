<?php

class Branch_model extends CI_Model
{

    function get_all_branch()
    {
        $this->db->where('isCancel', 'no');
        $query = $this->db->get('branch');
        $procat = $query->result();

        return $procat;
    }

    public function insert_added_branch()
    {
        $branch = (string) $this->input->post('branch');

        $data = array(
            'branch' => $branch,
        );

        $response = $this->db->insert('branch', $data);

        if ($response) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }
    public function update_added_branch()
    {
        $branch_id = (int) $this->input->post('branch_id');
        $branch = (string) $this->input->post('branch');

        $data = array(
            'branch' => $branch,
        );

        $this->db->where('branch_id', $branch_id);
        $response = $this->db->update('branch', $data);

        if ($response) {
            return $branch_id;
        } else {
            return FALSE;
        }
    }
    public function get_branch($branch_id)
    {
        $this->db->where('branch_id', $branch_id);
        $query = $this->db->get('branch');
        $row = $query->row();

        return $row;
    }

    public function delete_branch($id)
    {
        $data = array(
            'IsCancel' => 'Yes'
        );
        $this->db->where('branch_id', $id);
        $response = $this->db->update('branch', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
}
