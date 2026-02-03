<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_adjustment_model extends CI_Model
{
    public function updateQuantity()
    {
        $product_id = (int) $this->input->post('product_id');
        $product_quantity = (int) $this->input->post('product_quantity');
        $product = $this->db->get_where('product', array('product_id' => $product_id))->row();

        $this->db->trans_start();

        // Update product quantity
        $this->db->set('product_quantity', $product_quantity);
        $this->db->where('product_id', $product_id);
        $response = $this->db->update('product');

        // Insert inventory adjustment record
        $data = array(
            'product_name' => $product->product_name,
            'old_quantity' => $product->product_quantity,
            'new_quantity' => $product_quantity,
            'date_adjusted' => date('m-d-Y h:i A'),
            'reason' => $this->input->post('reason')
        );
        $this->db->insert('inventory_adjustment', $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return FALSE;
        } else {
            return $product_id;
        }
    }

    function get_all_adjustment()
    {
        $query = $this->db->get('inventory_adjustment');
        $adjustment = $query->result();

        return $adjustment;
    }
    function get_all_adjust($limit = null, $offset = null, $search = null, $order_column = null, $order_dir = 'desc')
    {
        $this->db->select('*');
        $this->db->from('inventory_adjustment');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('product_name', $search);
            $this->db->or_like('old_quantity', $search);
            $this->db->or_like('new_quantity', $search);
            $this->db->or_like('reason', $search);
            $this->db->or_like('date_adjusted', $search);
            $this->db->group_end();
        }
        
        if ($order_column) {
            $this->db->order_by($order_column, $order_dir);
        } else {
            $this->db->order_by('inventory_adjustment_id', 'desc');
        }
        
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get()->result();
        return $query;
    }
    
    function count_all_adjust($search = null)
    {
        $this->db->from('inventory_adjustment');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('product_name', $search);
            $this->db->or_like('old_quantity', $search);
            $this->db->or_like('new_quantity', $search);
            $this->db->or_like('reason', $search);
            $this->db->or_like('date_adjusted', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
}
