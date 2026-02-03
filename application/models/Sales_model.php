<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales_model extends CI_Model
{
    public function generate_reference_number()
    {
        $prefix = date('Ymd');

        $query =  $this->db->query("SELECT max(reference_no) as max_product_code FROM sales_no where reference_no LIKE '{$prefix}%'");
        $result = $query->row();


        if ($result->max_product_code) {
            $next_product_code = ++$result->max_product_code;
        } else {
            $next_product_code = $prefix . '0001';
        }
        return $next_product_code;
    }

    function insert_sales()
    {
        $sales = [
            'reference_no' => $this->input->post('reference_no'),
            'date_created' => $this->input->post('date_created'),
            'customer_name' => $this->input->post('customer_name'),
            'remarks' => $this->input->post('remarks'),
        ];

        $this->db->insert('sales_no', $sales);

        $last_id = $this->db->insert_id();

        $remarks = $this->input->post('remarks');
        $product = $this->input->post('product');
        $quantity = $this->input->post('quantity');
        $product_price = $this->input->post('product_price');
        $product_uom = $this->input->post('product_uom');
        $product_code = $this->input->post('product_code');
        $reference_no = $this->input->post('reference_no');

        $total_cost = 0; // Initialize total cost variable

        foreach ($product as $index => $product_name) {
            $quantity_value = $quantity[$index];
            $product_price_value = $product_price[$index];
            $uom = $product_uom[$index];
            $code = $product_code[$index];

            // Check if the requested quantity exceeds the remaining quantity
            $remaining_quantity = $this->get_remaining_quantity($code);
            if ($quantity_value > $remaining_quantity) {
                // If quantity exceeds remaining, show toastr error and skip inserting
                $this->session->set_flashdata('error', 'Remaining Quantity: ' . $remaining_quantity . ', Product Name: ' . $product_name);
                redirect('main/pos'); // Redirect to your sales page or wherever appropriate
            }

            $data = [
                'reference_no' => $last_id,
                'product_name' => $product_name,
                'product_uom' => $uom,
                'product_code' => $code,
                'quantity' => $quantity_value,
                'product_price' => $product_price_value,
            ];

            // Calculate total cost for each product and add to the overall total cost
            $total_cost += ($quantity_value * $product_price_value);

            $this->db->insert('sales', $data);

            // Update product_quantity in the product table
            // First, get the current product_quantity
            $this->db->select('product_quantity');
            $this->db->from('product');
            $this->db->where('product_code', $code); // Changed from product_name to product_code kay naga error ang name kung tam'an ka laba kay for some reason waay nya ginapass ang full product name xd
            $query = $this->db->get();
            $current_quantity = $query->row()->product_quantity;

            // Calculate the new product quantity
            $new_quantity = $current_quantity - $quantity_value;

            // Update product_quantity in the product table
            $this->db->set('product_quantity', $new_quantity);
            $this->db->where('product_name', $product_name);
            $this->db->update('product');

            // Insert data into inventory_ledger table
            $data_inventory_ledger = [
                'reference_no' => $reference_no,
                'product_code' => $code,
                'product_name' => $product_name,
                'unit' => $uom, // Adjust based on your unit information
                'quantity' => $quantity_value, // Negative quantity for sales
                'price' => $product_price_value,
                'activity' => 'Outbound', // Adjust based on your activity types
                'date_posted' => date('Y-m-d H:i:s'), // Adjust based on your date format
                'remarks' => $remarks
            ];

            $this->db->insert('inventory_ledger', $data_inventory_ledger);
        }

        // Update total_cost in the sales_no table
        $this->db->where('sales_no_id', $last_id);
        $this->db->update('sales_no', ['total_cost' => $total_cost]);

        return $last_id;
    }

    // function get_remaining_quantity($product_name)
    // {
    //     $this->db->select('product_quantity');
    //     $this->db->from('product');
    //     $this->db->where('product_name', $product_name);
    //     $query = $this->db->get();
    //     $current_quantity = $query->row()->product_quantity;
    //     return $current_quantity;
    // }

    function get_remaining_quantity($product_code)
    {
        $this->db->select('product_quantity');
        $this->db->from('product');
        $this->db->where('product_code', $product_code); // Replace 'id' with the actual column name for the product ID in your table
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $current_quantity = $query->row()->product_quantity;
            return $current_quantity;
        } else {
            return null; // Return null if no product is found
        }
    }

    function get_all_sales($limit = null, $offset = null, $search = null, $order_column = null, $order_dir = 'desc')
    {
        $this->db->select('*');
        $this->db->from('sales_no');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('reference_no', $search);
            $this->db->or_like('customer_name', $search);
            $this->db->or_like('total_cost', $search);
            $this->db->or_like('date_created', $search);
            $this->db->group_end();
        }
        
        if ($order_column) {
            $this->db->order_by($order_column, $order_dir);
        } else {
            $this->db->order_by('sales_no_id', 'desc');
        }
        
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get()->result();
        return $query;
    }
    
    function count_all_sales($search = null)
    {
        $this->db->from('sales_no');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('reference_no', $search);
            $this->db->or_like('customer_name', $search);
            $this->db->or_like('total_cost', $search);
            $this->db->or_like('date_created', $search);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    function code($id)
    {
        $this->db->select('*');
        $this->db->from('sales_no');
        $this->db->where('sales_no_id', $id);
        $query = $this->db->get()->row();
        return $query;
    }

    public function view_all_sales($id)
    {
        $this->db->select('sa.*, P.*, sn.*');
        $this->db->from('sales_no AS sn');
        $this->db->join('sales AS sa', 'sn.sales_no_id = sa.reference_no');
        $this->db->join('product AS P', 'sa.product_code = P.product_code');
        $this->db->where('sales_no_id', $id);
        $query = $this->db->get();
        return $query->result();
    }
    public function getTotalSales()
    {
        $this->db->select_sum('total_cost', 'total_sales');
        $query = $this->db->get('sales_no');

        if ($query->num_rows() > 0) {
            return $query->row()->total_sales;
        } else {
            return 0;
        }
    }
    public function getTotalSalesForToday()
    {
        $this->db->select_sum('total_cost', 'total_sales');
        $this->db->where('date_created', date('Y-m-d'));
        $query = $this->db->get('sales_no');

        if ($query->num_rows() > 0) {
            return $query->row()->total_sales;
        } else {
            return 0; // No sales found for today
        }
    }
    public function getMonthlySales()
    {
        $this->db->select('MONTH(date_created) as month, SUM(total_cost) as monthly_sales');
        $this->db->group_by('MONTH(date_created)');
        $query = $this->db->get('sales_no');

        return $query->result();
    }
    public function update_added_sales()
    {
        $product_id = (int) $this->input->post('product_id');

        $sales_srp = (string) $this->input->post('sales_srp');

        $data = array(
            'product_price' => $sales_srp,
        );

        $this->db->where('product_id', $product_id);

        $response = $this->db->update('product', $data);

        if ($response) {
            return $product_id;
        } else {
            return FALSE;
        }
    }
}
