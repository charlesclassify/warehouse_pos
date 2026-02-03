
<?php

class Product_model extends CI_Model
{
    function edit_product_submit($product_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->form_validation->set_rules('product_code', 'Product Code', 'trim|required');
            $this->form_validation->set_rules('product_name', 'Product Name', 'trim|required');
            $this->form_validation->set_rules('supplier_id', 'Supplier', 'trim|required');
            $this->form_validation->set_rules('product_barcode', 'Product Barcode', 'trim|required');
            $this->form_validation->set_rules('product_category', 'Product Category', 'trim|required');
            $this->form_validation->set_rules('product_margin', 'Product Margin', 'trim|required');
            $this->form_validation->set_rules('product_vat', 'Product VAT', 'trim|required');
            $this->form_validation->set_rules('product_inbound_threshold', 'Product Inbound Threshold', 'trim|required');
            $this->form_validation->set_rules('product_shelf_life', 'Product Shelf Life', 'trim|required');
            $this->form_validation->set_rules('product_recall_threshold', 'Product Recall Threshold', 'trim|required');
            $this->form_validation->set_rules('product_minimum_quantity', 'Product Minimum Quantity', 'trim|required');
            $this->form_validation->set_rules('product_required_quantity', 'Product Required Quantity', 'trim|required');
            $this->form_validation->set_rules('product_maximum_quantity', 'Product Maximum Quantityr', 'trim|required');
            $this->form_validation->set_rules('product_minimum_order_quantity', 'Product Minimum Order Quantity', 'trim|required');


            if ($this->form_validation->run() != FALSE) {
                $this->load->model('product_model');

                // Check if a new image is being uploaded
                $update_image = false;

                if ($_FILES['product_image']['name']) {
                    $update_image = true;
                    $config['upload_path'] = './assets/images/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048;
                    $config['encrypt_name'] = TRUE;

                    $this->load->library('upload', $config);

                    if ($this->upload->do_upload('product_image')) {
                        $image_data = $this->upload->data();
                        $product = $this->product_model->get_product($product_id);

                        // Delete the old image file if it exists
                        if ($product && !empty($product->product_image)) {
                            unlink('./assets/images/' . $product->product_image);
                        }

                        // Generate a unique filename based on the product name
                        $product_name = $this->input->post('product_name');
                        $unique_filename = strtolower(str_replace(' ', '', $product_name)) . '' . $product_id . '_' . time() . $image_data['file_ext'];

                        // Rename the uploaded file to the unique filename
                        $new_path = './assets/images/' . $unique_filename;
                        rename($image_data['full_path'], $new_path);

                        // Update the product with the new image filename
                        $this->input->post('product_image', $unique_filename);
                    } else {
                        $error_message = 'Image upload failed: ' . $this->upload->display_errors();
                        echo $error_message; // Debugging: Output the error message
                        $this->session->set_flashdata('error', $error_message);
                        redirect('main/product'); // Stop further processing if image upload fails
                    }
                }

                // Update the product data
                $response = $this->product_model->update_product($product_id, $update_image);

                if ($response) {
                    $success_message = 'Product updated successfully.';
                    $this->session->set_flashdata('success', $success_message);
                    echo $success_message; // Debugging: Output the success message

                } else {
                    $error_message = 'Product was not updated successfully.';
                    echo $error_message; // Debugging: Output the error message
                    $this->session->set_flashdata('error', $error_message);
                }


                // Redirect to the product listing page
                redirect('main/product');
            }
        }
    }
}
