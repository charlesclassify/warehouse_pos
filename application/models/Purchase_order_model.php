<?php

class Purchase_order_model extends CI_Model
{
    public function po_no()
    {

        $year = date('Y');
        $text = "PR" . '-' . $year;
        $query = "SELECT max(purchase_order_no) as code_auto from purchase_order_no";
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


    public function gr_no()
    {

        $year = date('Y');
        $text = "GR" . '-' . $year;
        $query = "SELECT max(goods_received_no) as code_auto from goods_received_no";
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


    function insertpurchaseorder()
    {
        $purchase = [
            'purchase_order_no' => $this->input->post('purchase_order_no'),
            'supplier_id' => $this->input->post('supplier_id'),
            'date_created' => $this->input->post('date_created'),
            'payment_method' => $this->input->post('payment_method'),
        ];

        $this->db->insert('purchase_order_no', $purchase);

        $last_purchase_id = $this->db->insert_id();

        $product_name = $this->input->post('product_name');
        $po_product_quantity = $this->input->post('po_product_quantity');
        $product_unit = $this->input->post('product_unit');
        $product_unitprice = $this->input->post('product_unitprice');
        $total_cost = 0;

        foreach ($product_name as $index => $product_name) {
            $arr_product = $product_name;

            $arr_quant = $po_product_quantity[$index];
            $arr_unit = $product_unit[$index];
            $arr_price = $product_unitprice[$index];

            // Insert data into purchase_order table
            $data_purchase_order = [
                'purchase_order_no' => $last_purchase_id,
                'product_name' => $arr_product,
                'po_product_quantity' => $arr_quant,

                'product_unit' => $arr_unit,
                'product_unitprice' => $arr_price,
            ];
            $this->db->insert('purchase_order', $data_purchase_order);


            // Insert data into inventory_ledger table for the purchase activity
            $data_inventory_ledger = [
                'product_name' => $arr_product,
                'unit' => $arr_unit,
                'quantity' => $arr_quant,
                'price' => $arr_price,
                'activity' => 'Purchased',
                'date_posted' => date('Y-m-d'),
            ];

            $this->db->insert('inventory_ledger', $data_inventory_ledger);

            // Calculate total cost
            $total_cost += $arr_quant * $arr_price;
        }


        $this->db->set('total_cost', $total_cost);
        $this->db->where('purchase_order_no_id', $last_purchase_id);
        $this->db->update('purchase_order_no');

        return $last_purchase_id;
    }


    function get_all_po()
    {
        $this->db->select('*');
        $this->db->from('purchase_order_no');
        $this->db->join('suppliers', 'purchase_order_no.supplier_id = suppliers.supplier_id');
        $this->db->where('purchase_order_no.is_Delete', 'no');
        $query = $this->db->get()->result();
        return $query;
    }

    function get_product_unit()
    {
        $this->db->select('*');
        $this->db->from('barcode');
        $this->db->join('product', 'barcode.product_name = product.product_name');
        $query = $this->db->get()->result();
        return $query;
    }


    function get_all_gr()
    {
        $this->db->select('*');
        $this->db->from('purchase_order_no');
        $this->db->join('suppliers', 'purchase_order_no.supplier_id = suppliers.supplier_id');
        $this->db->where('purchase_order_no.is_Delete', 'no');
        $this->db->where('purchase_order_no.status', 'To be Received');
        $query = $this->db->get()->result();
        return $query;
    }
    function get_all_gr1()
    {
        $this->db->select('*');
        $this->db->from('purchase_order_no');
        $this->db->join('suppliers', 'purchase_order_no.supplier_id = suppliers.supplier_id');
        $this->db->where('purchase_order_no.is_delete', 'no');
        $this->db->where('purchase_order_no.status', 'Back Order');
        $query = $this->db->get()->result();
        return $query;
    }
    function code($id)
    {
        $this->db->select('*');
        $this->db->from('purchase_order_no');
        $this->db->where('purchase_order_no_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    function Select_one($id)
    {
        $this->db->select('*');
        $this->db->from('suppliers AS supplier');
        $this->db->join('purchase_order_no AS purc', 'purc.supplier_id = supplier.supplier_id');
        $this->db->join('purchase_order AS PO', 'purc.purchase_order_no_id= PO.purchase_order_no');
        $this->db->where('purchase_order_no_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }
    function view_all_PO($id)
    {
        $this->db->select('PO.*, P.*, purc.*');
        $this->db->from('purchase_order_no AS purc');
        $this->db->join('purchase_order AS PO', 'purc.purchase_order_no_id = PO.purchase_order_no');
        $this->db->join('product AS P', 'PO.product_name = P.product_name');
        $this->db->where('purchase_order_no_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function net_product_cost($id)
    {
        $this->db->select('*');
        $this->db->from('purchase_order AS po');
        $this->db->join('purchase_order_no AS purc', 'po.purchase_order_no = purc.purchase_order_no_id');
        $this->db->where('purchase_order_no', $id);
    }
    public function update_po()
    {
        $purchase_id = (int) $this->input->post('purchase_id');
        $supplier_name = (string) $this->input->post('supplier_name');
        $payment_method = (string) $this->input->post('payment_method');
        $pr_code = $this->input->post('pr_code');
        $product_name = $this->input->post('product_name');
        $po_product_quantity = $this->input->post('po_product_quantity');
        $product_unit = $this->input->post('product_unit');
        $product_unitprice = $this->input->post('product_unitprice');

        // Calculate the new total cost
        $total_cost = 0;
        foreach ($product_unitprice as $index => $price) {
            $total_cost += $price * $po_product_quantity[$index];
        }

        // Update the purchase order with the new total cost and supplier id
        $purchase = [
            'supplier_id' => $supplier_name,
            'payment_method' => $payment_method,
            'total_cost' => $total_cost
        ];
        $this->db->where('purchase_order_no_id', $purchase_id);
        $this->db->update('purchase_order_no', $purchase);

        // Loop through the array of product data and update existing records or insert new ones
        foreach ($product_name as $index => $product_name) {
            $arr_product = $product_name;
            $arr_quant = $po_product_quantity[$index];
            $arr_unit = $product_unit[$index];

            $arr_price = $product_unitprice[$index];

            $data = [
                'purchase_order_no' => $purchase_id,
                'product_name' => $arr_product,
                'po_product_quantity' => $arr_quant,
                'product_unit' => $arr_unit,
                'product_unitprice' => $arr_price
            ];

            $this->db->where('purchase_order_no', $purchase_id);
            $this->db->where('product_name', $arr_product);
            $result = $this->db->get('purchase_order');

            if ($result->num_rows() > 0) {
                // If the product already exists, update the existing record
                $this->db->where('purchase_order_no', $purchase_id);
                $this->db->where('product_name', $arr_product);
                $this->db->update('purchase_order', $data);
            } else {
                // If the product does not exist, insert a new record
                $this->db->insert('purchase_order', $data);
            }
        }

        return $purchase_id;
    }

    public function approved_po($id)
    {


        $data = array(
            'status' => 'To Be Received'
        );
        $this->db->where('purchase_order_no_id', $id);
        var_dump($data);
        $response = $this->db->update('purchase_order_no', $data);


        if ($response) {

            $product_name = $this->input->post('product_name');
            $product_units = $this->input->post('product_unit');
            $net_product_costs = $this->input->post('net_product_cost');
            $vat_type = $this->input->post('product_vat');
            $vat_amount = $this->input->post('tax_amount');



            foreach ($product_name as $index => $name) {
                $current_product_name = $name;
                $product_unit = $product_units[$index];
                $net_product_cost = $net_product_costs[$index];

                //Check if the record with the given product_name and unit already exists in the barcode table
                $existingRecord = $this->db->get_where('barcode', array('product_name' => $current_product_name, 'unit' => $product_unit))->row();

                $data_barcode = [
                    'net_product_cost' => $net_product_cost,
                ];

                if ($existingRecord) {
                    // Update existing record
                    $this->db->where('barcode_id', $existingRecord->barcode_id);
                    $this->db->update('barcode', $data_barcode);
                } else {
                    // Insert a new record if it doesn't exist
                    $data_barcode['product_name'] = $current_product_name;
                    $data_barcode['unit'] = $product_unit;
                    $this->db->insert('barcode', $data_barcode);
                }
            }

            return $id;
        } else {
            return false;
        }
    }

    public function cancel_po($id)
    {
        $data = array(
            'status' => 'Cancelled'
        );
        $this->db->where('purchase_order_no_id', $id);
        $response = $this->db->update('purchase_order_no', $data);
        if ($response) {
            return $id;
        } else {
            return false;
        }
    }
    public function getPendingPurchaseOrdersCount()
    {
        $this->db->where('status', 'pending');
        $this->db->from('purchase_order_no');
        return $this->db->count_all_results();
    }
    public function getReceivedPurchaseOrderCount()
    {
        $this->db->where('status', 'received');
        $this->db->from('purchase_order_no');
        return $this->db->count_all_results();
    }
}
