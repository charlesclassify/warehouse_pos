<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Goods_return_model extends CI_Model
{
    public function grt_no()
    {

        $year = date('Y');
        $text = "GRT" . '-' . $year;
        $query = "SELECT max(goods_return_no) as code_auto from goods_return_no";
        $data = $this->db->query($query)->row_array();
        if ($data["code_auto"] != NULL) {
            $max_code = $data['code_auto'];
            $max_code2 = (int) substr($max_code, 8, 5);
            $codecount = $max_code2 + 1;
            $code_auto = $text . '-' . sprintf('%03s', $codecount);
            return $code_auto;
        } else {
            return $text . '-' . sprintf('%03s', 1);
        }
    }
    function get_barcode()
    {
        $this->db->select('*');
        $this->db->from('barcode');
        $query = $this->db->get()->result();
        return $query;
    }
    function get_all_grt()
    {
        $this->db->select('*');
        $this->db->from('goods_received_no');
        $this->db->join('suppliers', 'goods_received_no.supplier_id = suppliers.supplier_id');
        $this->db->where('goods_received_no.isCancel', 'no');
        $this->db->where('goods_received_no.status', 'received');
        $query = $this->db->get()->result();
        return $query;
    }

    function get_all_grt1()
    {
        $this->db->select('*');
        $this->db->from('goods_return_no');
        $this->db->join('suppliers', 'goods_return_no.supplier_id = suppliers.supplier_id');
        $this->db->join('goods_received_no', 'goods_return_no.goods_return_no_id = goods_received_no.goods_received_no_id');
        $this->db->where('goods_return_no.isCancel', 'no');
        $query = $this->db->get()->result();
        return $query;
    }
    function Select_two($id)
    {
        $this->db->select('goods_received_no.purchase_order_no_id, purchase_order_no.purchase_order_no');
        $this->db->from('goods_received_no');
        $this->db->join('purchase_order_no', 'goods_received_no.purchase_order_no_id = purchase_order_no.purchase_order_no_id');
        $this->db->where('goods_received_no.goods_received_no_id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    function view_all_grt($id)
    {
        $this->db->select('*');
        $this->db->from('goods_received_no AS grt');
        $this->db->join('goods_received AS gr', 'grt.goods_received_no_id= gr.goods_received_no');
        $this->db->where('goods_received_no_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function post_goods_return()
    {
        $this->updatestatus();
        $purchase_id = (int) $this->input->post('purchase_id');
        $goods = [
            'goods_return_no' => $this->input->post('goods_return_no'),
            'goods_received_no' => $this->input->post('grt_id'),
            'supplier_id' => $this->input->post('supplier_id'),
            'date_returned' => $this->input->post('date_returned'),
            'purchase_order_no' => $this->input->post('purchase_order_no'),
        ];

        $this->db->insert('goods_return_no', $goods);
        $last_id = $this->db->insert_id();

        $grt_product_name = $this->input->post('grt_product_name');
        $grt_returned_quantity = $this->input->post('grt_returned_quantity');
        $grt_product_unit = $this->input->post('grt_product_unit');
        $grt_product_unitprice = $this->input->post('grt_product_unitprice');
        $grt_received_quantity = $this->input->post('grt_received_quantity');
        $grt_expiry_date = $this->input->post('grt_expiry_date');

        $total_cost = 0; // Initialize total cost variable

        foreach ($grt_product_name as $index => $grt_product_name) {
            $arr_product = $grt_product_name;
            $arr_quant = $grt_received_quantity[$index];
            $arr_unit = $grt_product_unit[$index];
            $arr_price = $grt_product_unitprice[$index];
            $arr_ret = $grt_returned_quantity[$index];
            $arr_exp = $grt_expiry_date[$index];

            // Calculate cost for this product
            $cost = $arr_price * $arr_ret;
            $total_cost += $cost; // Add cost to total cost variable

            // Insert data into goods_return table
            $data_goods_return = [
                'goods_return_no' => $last_id,
                'grt_product_name' => $arr_product,
                'grt_total_quantity' => $arr_quant,
                'grt_unit' => $arr_unit,
                'grt_price' => $arr_price,
                'grt_returned_quantity' => $arr_ret,
                'grt_exp_date' => $arr_exp,
            ];

            $this->db->insert('goods_return', $data_goods_return);

            // Insert data into inventory_ledger table for the return activity
            $data_inventory_ledger = [
                'product_name' => $arr_product,
                'unit' => $arr_unit,
                'quantity' => -$arr_ret, // Negative quantity for return
                'price' => $arr_price,
                'activity' => 'Return', // Adjust based on your activity types
                'date_posted' => date('Y-m-d'), // Adjust based on your date format
            ];

            $this->db->insert('inventory_ledger', $data_inventory_ledger);

            // Update product_quantity in the product table
            // First, get the current product_quantity
            $this->db->select('product_quantity');
            $this->db->from('product');
            $this->db->where('product_name', $arr_product);
            $query = $this->db->get();
            $current_quantity = $query->row()->product_quantity;

            // Calculate the new product quantity
            $new_quantity = $current_quantity - $arr_ret;

            // Update product_quantity in the product table
            $this->db->set('product_quantity', $new_quantity);
            $this->db->where('product_name', $arr_product);
            $this->db->update('product');
        }

        // Insert total cost into goods_return_no table
        $this->db->set('grt_total_cost', $total_cost);
        $this->db->where('goods_return_no_id', $last_id);
        $this->db->update('goods_return_no');

        return $last_id;
    }

    public function updatestatus()
    {
        $grt_id = $this->input->post('grt_id');
        $ref = array(
            'status' => 'Returned'
        );
        $this->db->where('goods_received_no_id', $grt_id);
        $this->db->update('goods_received_no', $ref);
        return $grt_id;
    }
    function code($id)
    {
        $this->db->select('*');
        $this->db->from('goods_return_no');
        $this->db->where('goods_return_no_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    function Select_one($id)
    {
        $this->db->select('*');
        $this->db->from('suppliers AS supplier');
        $this->db->join('goods_received_no AS grt', 'grt.supplier_id = supplier.supplier_id');
        $this->db->join('goods_received AS gr', 'grt.goods_received_no_id= gr.goods_received_no');
        $this->db->where('goods_received_no_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    public function view_all_grt1($id)
    {
        $this->db->select('GR.*, P.*, GRT.*');
        $this->db->from('goods_return_no AS GRT');
        $this->db->join('goods_return AS GR', 'GRT.goods_return_no_id = GR.goods_return_no');
        $this->db->join('product AS P', 'GR.grt_product_name = P.product_name');
        $this->db->where('goods_return_no_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
}
