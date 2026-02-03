<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Back_order_model extends CI_Model
{
    public function bo_no()
    {

        $year = date('Y');
        $text = "BO" . '-' . $year;
        $query = "SELECT max(back_order_no) as code_auto from back_order_no";
        $data = $this->db->query($query)->row_array();
        if ($data["code_auto"] != NULL) {
            $max_code = $data['code_auto'];
            $max_code2 =  (int)substr($max_code, 8, 5);
            $codecount = $max_code2 + 1;
            $code_auto = $text . '-' . sprintf('%03s', $codecount);
            return $code_auto;
        } else {
            return $text . '-' . sprintf('%03s', 1);
        }
    }
    public function post_back_order()
    {
        $this->updatestatus();
        $purchase_id = (int) $this->input->post('purchase_id');
        $goods = [
            'back_order_no' => $this->input->post('back_order_no'),
            'supplier_id' => $this->input->post('supplier_id'),
            'bo_date_received' => $this->input->post('date_received'),
            'purchase_order_id' => $this->input->post('po_id')
        ];

        $this->db->insert('back_order_no', $goods);

        $last_id = $this->db->insert_id();

        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $product_unitprice = $this->input->post('product_unitprice');
        $unserved_quantity = $this->input->post('unserved_quantity');
        $expiry_date = $this->input->post('expiry_date');

        $total_cost = 0; // Initialize total cost variable

        foreach ($product as $index => $product) {
            $arr_product = $product;
            $arr_quant = $quantity[$index];
            $arr_unit = $unit[$index];
            $arr_price = $product_unitprice[$index];
            $arr_un = $unserved_quantity[$index];
            $arr_exp = $expiry_date[$index];

            $arr_rec = $arr_quant - $arr_un;

            // Calculate cost for this product
            $cost = $arr_price * $arr_rec;
            $total_cost += $cost; // Add cost to total cost variable

            // Update goods_received table with received quantity
            $this->db->set('gr_received_quantity', "gr_received_quantity + $arr_rec", FALSE);
            $this->db->where('gr_product_name', $arr_product);
            $this->db->update('goods_received');

            // Insert data into back_order table
            $data_goods_received = [
                'back_order_no' => $last_id,
                'bo_product_name' => $arr_product,
                'bo_total_quantity' => $arr_quant,
                'bo_unit' => $arr_unit,
                'bo_product_unitprice' => $arr_price,
                'bo_received_quantity' => $arr_rec,
                'bo_expiry_date' => $arr_exp,
            ];

            $this->db->insert('back_order', $data_goods_received);

            // Update product_quantity and product_expirydate in product table
            $this->db->set('product_quantity', "product_quantity + $arr_rec", FALSE);

            $this->db->where('product_name', $arr_product);
            $this->db->update('product');
        }

        // Insert total cost into goods_received_no table
        $this->db->set('bo_total_cost', $total_cost);
        $this->db->where('back_order_id', $last_id);
        $this->db->update('back_order_no');

        return $last_id;
    }
    public function updatestatus()
    {
        $po_id = $this->input->post('po_id');
        $ref = array(
            'status' => 'Received'
        );
        $this->db->where('purchase_order_no_id', $po_id);
        $this->db->update('purchase_order_no', $ref);
        return $po_id;
    }
    function get_all_bo()
    {
        $this->db->select('*');
        $this->db->from('back_order_no');
        $this->db->join('suppliers', 'back_order_no.supplier_id = suppliers.supplier_id');
        $this->db->join('purchase_order_no', 'back_order_no.purchase_order_id = purchase_order_no.purchase_order_no_id');
        $this->db->where('back_order_no.isCancel', 'no');
        $this->db->where('back_order_no.status', 'received');
        $query = $this->db->get()->result();
        return $query;
    }
    function view_all_bo($id)
    {
        $this->db->select('*');
        $this->db->from('back_order');
        $this->db->join('back_order_no', 'back_order.back_order_no = back_order_no.back_order_id');
        $this->db->where('back_order.back_order_no', $id);
        $query = $this->db->get();
        return $query->result();
    }
    function code($id)
    {
        $this->db->select('*');
        $this->db->from('back_order_no');
        $this->db->join('purchase_order_no', 'back_order_no.purchase_order_id = purchase_order_no.purchase_order_no_id', 'left');
        $this->db->join('suppliers', 'back_order_no.supplier_id = suppliers.supplier_id', 'left');
        $this->db->where('back_order_no.back_order_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }

    function Select_one($id)
    {
        $this->db->select('*');
        $this->db->from('suppliers AS supplier');
        $this->db->join('purchase_order_no AS purc', 'purc.supplier_id = supplier.supplier_id');
        $this->db->join('purchase_order AS PO', 'purc.purchase_order_id= PO.purchase_order_no');
        $this->db->where('purchase_order_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
}
