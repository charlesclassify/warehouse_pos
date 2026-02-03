<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_ledger_model extends CI_Model
{
    function get_all_ledger()
    {
        $this->db->select('*');
        $this->db->from('inventory_ledger');
        $query = $this->db->get()->result();
        return $query;
    }

    function get_ledger_by_date_range($date_from, $date_to)
    {
        // Convert dates to datetime format with time set to midnight
        $datetime_from = $date_from . ' 00:00:00';
        $datetime_to = $date_to . ' 23:59:59';

        $this->db->select('*');
        $this->db->from('inventory_ledger');
        // Compare dates ignoring time component
        $this->db->where('date_posted >=', $datetime_from);
        $this->db->where('date_posted <=', $datetime_to);
        $query = $this->db->get()->result();
        return $query;
    }

    function get_product_ledger($product_name)
    {
        $this->db->select('*');
        $this->db->from('inventory_ledger');
        $this->db->where('product_name', $product_name);
        $query = $this->db->get()->result();
        return $query;
    }
}
