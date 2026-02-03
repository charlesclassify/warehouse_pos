<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_requisition_model extends CI_Model
{
    public function sr_no()
    {
        $year = date('Y');

        $prefix = "SR-";

        $query =  $this->db->query("SELECT max(stock_requisition_no) as max_stock_requisition_code FROM stock_requisition_no where stock_requisition_no LIKE '{$prefix}%'");
        $result = $query->row();


        if ($result->max_stock_requisition_code) {
            $next_stock_requisition_code = ++$result->max_stock_requisition_code;
        } else {
            $next_stock_requisition_code = $prefix . '0001';
        }
        return $next_stock_requisition_code;
    }
    function code($id)
    {
        $this->db->select('*');
        $this->db->from('stock_requisition_no');
        $this->db->where('stock_requisition_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    function Select_one($id)
    {
        $this->db->select('*');
        $this->db->from('stock_requisition_no as srn');
        $this->db->join('stock_requisition AS sr', 'srn.stock_requisition_id= sr.stock_requisition_no');
        $this->db->where('stock_requisition_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    function view_all_sr($id)
    {
        $this->db->select('st.*,sto.*');
        $this->db->from('stock_requisition_no AS sto');
        $this->db->join('stock_requisition AS st', 'sto.stock_requisition_id= st.stock_requisition_no');
        $this->db->where('stock_requisition_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    function get_all_sr()
    {
        $this->db->select('*');
        $this->db->from('stock_requisition_no');
        $this->db->where('stock_requisition_no.isCancel', 'no');
        $query = $this->db->get()->result();
        return $query;
    }
    function get_all_sr2()
    {
        $this->db->select('*');
        $this->db->from('stock_requisition_no');
        $this->db->where('stock_requisition_no.status', 'received');
        $query = $this->db->get()->result();
        return $query;
    }
    function insertstockrequisition()
    {
        $purchase = [
            'stock_requisition_no' => $this->input->post('stock_requisition_no'),

            'date_created' => $this->input->post('date_created'),

        ];

        $this->db->insert('stock_requisition_no', $purchase);

        $last_id = $this->db->insert_id();

        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $product_sellingprice = $this->input->post('product_sellingprice');
        $total_cost = 0;

        foreach ($product as $index => $product) {
            $arr_product = $product;
            $arr_quant = $quantity[$index];
            $arr_unit = $unit[$index];
            $arr_price = $product_sellingprice[$index];

            $data = [
                'stock_requisition_no' => $last_id,
                'product_name' => $arr_product,
                'quantity' => $arr_quant,
                'unit' => $arr_unit,
                'product_sellingprice' => $arr_price,
            ];

            $this->db->insert('stock_requisition', $data);

            $total_cost += $arr_quant * $arr_price;
        }

        $this->db->set('total_cost', $total_cost);
        $this->db->where('stock_requisition_id', $last_id);
        $this->db->update('stock_requisition_no');

        return $last_id;
    }

    function poststockrequisition()
    {
        $this->updatestatus();
        $purchase = [
            'stock_requisition_no' => $this->input->post('stock_requisition_no'),
            'date_created' => $this->input->post('date_created'),
            'date_posted' => $this->input->post('date_posted'),
        ];

        $this->db->insert('stock_requisition_admin_no', $purchase);

        $last_id = $this->db->insert_id();

        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $product_sellingprice = $this->input->post('product_sellingprice');
        $total_cost = 0;

        foreach ($product as $index => $product) {
            $arr_product = $product;
            $arr_quant = $quantity[$index];
            $arr_unit = $unit[$index];
            $arr_price = $product_sellingprice[$index];

            $data = [
                'stock_requisition_no' => $last_id,
                'product_name' => $arr_product,
                'quantity' => $arr_quant,
                'unit' => $arr_unit,
                'product_sellingprice' => $arr_price,
            ];

            $this->db->insert('stock_requisition_admin', $data);

            // insert data to inventory ledger
            $ledger_data = [
                'product_name' => $arr_product,
                'unit' => $arr_unit,
                'quantity' => $arr_quant,
                'price' => $arr_price,
                'activity' => 'stock transfered',
                'date_posted' => date('Y-m-d'),
            ];
            $this->db->insert('inventory_ledger', $ledger_data);

            $total_cost += $arr_quant * $arr_price;
            $this->db->set('product_quantity', "product_quantity - $arr_quant", FALSE);
            $this->db->where('product_name', $arr_product);
            $this->db->update('product');
        }

        $this->db->set('total_cost', $total_cost);
        $this->db->where('stock_requisition_admin_id', $last_id);
        $this->db->update('stock_requisition_admin_no');

        return $last_id;
    }


    public function update_sr()
    {
        $purchase_id = (int) $this->input->post('purchase_id');
        $sr_code = $this->input->post('sr_code');
        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $unit = $this->input->post('unit');
        $product_sellingprice = $this->input->post('product_sellingprice');

        // Calculate the new total cost
        $total_cost = 0;
        foreach ($product_sellingprice as $index => $price) {
            $total_cost += $price * $quantity[$index];
        }

        $branch = $this->input->post('branch');
        // Update the purchase order with the new total cost and supplier id
        $purchase = [
            'branch' => $branch,
            'prepared_by' => $_SESSION['name'],
            'total_cost' => $total_cost,
        ];
        $this->db->where('stock_requisition_id', $purchase_id);
        $this->db->update('stock_requisition_no', $purchase);

        // Loop through the array of product data
        foreach ($product as $index => $product) {
            $arr_product = $product;
            $arr_quant = $quantity[$index];
            $arr_unit = $unit[$index];
            $arr_code = $sr_code[$index];
            $arr_price = $product_sellingprice[$index];

            // If a po_id is present, update the record with the matching po_id
            if ($arr_code) {
                $this->db->set('product_name', $arr_product);
                $this->db->set('quantity', $arr_quant);
                $this->db->set('unit', $arr_unit);
                $this->db->set('product_sellingprice', $arr_price);
                $this->db->where('stock_requisition_no', $purchase_id);
                $this->db->where('sr_id', $arr_code);
                $this->db->update('stock_requisition');
            }
            // Otherwise, insert a new record
            else {
                $data = [
                    'stock_requisition_no' => $purchase_id,
                    'product_name' => $arr_product,
                    'quantity' => $arr_quant,
                    'unit' => $arr_unit,
                    'product_sellingprice' => $arr_price
                ];
                $this->db->insert('stock_requisition', $data);

                // Insert data to inventory_ledger
                $inventory_data = [
                    'product_name' => $arr_product,
                    'quantity' => $arr_quant,
                    'unit' => $arr_unit,
                    'activity' => 'stock transfered',
                    'date_posted' => date('Y-m-d'),
                ];
                $this->db->insert('inventory_ledger', $inventory_data);
            }
            $inventory_data = [
                'quantity' => $arr_quant,
                'unit' => $arr_unit,
                'date_posted' => date('m-d-Y h:i A'),
            ];
            $this->db->where('product_name', $arr_product);
            $this->db->where('activity', 'stock transfered');
            $this->db->where('date_posted',  date('Y-m-d'));
            $this->db->update('inventory_ledger', $inventory_data);
        }
        return $purchase_id;
    }

    public function cancel_sr($id)
    {
        $data = array(
            'status' => 'cancelled'
        );
        $this->db->where('stock_requisition_id', $id);
        $response = $this->db->update('stock_requisition_no', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
    public function delete_sr($id)
    {
        $data = array(
            'isCancel' => 'yes'
        );
        $this->db->where('stock_requisition_id', $id);
        $response = $this->db->update('stock_requisition_no', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
    public function received_sr($id)
    {
        $data = array(
            'status' => 'Received'
        );
        $this->db->where('stock_requisition_id', $id);
        $response = $this->db->update('stock_requisition_no', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
    public function updatestatus()
    {
        $sr_id = $this->input->post('sr_id');
        $ref = array(
            'status' => 'To Be Delivered'
        );
        $this->db->where('stock_requisition_id', $sr_id);
        $this->db->update('stock_requisition_no', $ref);
        return $sr_id;
    }
    public function get_product_quantity($product_name)
    {
        $this->db->select('product_quantity');
        $this->db->where('product_name', $product_name);
        $query = $this->db->get('product');
        $result = $query->row();
        return $result->product_quantity;
    }
    public function get_all_stock_requisitions()
    {
        $this->db->where('status', 'pending');
        $query = $this->db->get('stock_requisition_no');
        return $query->result();
    }
}
