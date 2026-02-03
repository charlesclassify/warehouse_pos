<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{

  function get_all_suppliers()
  {
    $this->db->where('isDelete', 'no');
    $query = $this->db->get('suppliers');
    $supplier = $query->result();

    return $supplier;
  }

  public function insertsupplier()
  {
    $vendor_code = (string) $this->input->post('vendor_code');
    $company_name = (string) $this->input->post('company_name');
    $supplier_contact = (string) $this->input->post('supplier_contact');
    $supplier_email = (string) $this->input->post('supplier_email');
    $supplier_street = (string) $this->input->post('supplier_street');
    $supplier_barangay = (string) $this->input->post('supplier_barangay');
    $supplier_city = (string) $this->input->post('supplier_city');
    $supplier_province = (string) $this->input->post('supplier_province');

    $data = array(
      'vendor_code' => $vendor_code,
      'company_name' => $company_name,
      'supplier_contact' => $supplier_contact,
      'supplier_email' => $supplier_email,
      'supplier_street' => $supplier_street,
      'supplier_barangay' => $supplier_barangay,
      'supplier_city' => $supplier_city,
      'supplier_province' => $supplier_province,
    );

    $response = $this->db->insert('suppliers', $data);

    if ($response) {
      return $this->db->insert_id();
    } else {
      return FALSE;
    }
  }
  public function update_added_supplier()
  {
    $supplier_id = (int) $this->input->post('supplier_id');

    $vendor_code = (string) $this->input->post('vendor_code');
    $company_name = (string) $this->input->post('company_name');
    $supplier_contact = (string) $this->input->post('supplier_contact');
    $supplier_email = (string) $this->input->post('supplier_email');
    $supplier_street = (string) $this->input->post('supplier_street');
    $supplier_barangay = (string) $this->input->post('supplier_barangay');
    $supplier_city = (string) $this->input->post('supplier_city');
    $supplier_province = (string) $this->input->post('supplier_province');

    $data = array(
      'vendor_code' => $vendor_code,
      'company_name' => $company_name,
      'supplier_contact' => $supplier_contact,
      'supplier_email' => $supplier_email,
      'supplier_street' => $supplier_street,
      'supplier_barangay' => $supplier_barangay,
      'supplier_city' => $supplier_city,
      'supplier_province' => $supplier_province,
    );

    $this->db->where('supplier_id', $supplier_id);

    $response = $this->db->update('suppliers', $data);

    if ($response) {
      return $supplier_id;
    } else {
      return FALSE;
    }
  }

  public function get_supplier($supplier_id)
  {
    $this->db->where('supplier_id', $supplier_id);
    $query = $this->db->get('suppliers');
    $row = $query->row();

    return $row;
  }

  public function deactivate_supplier($supplier_id)
  {
    $data = array(
      'status_supplier' => 'deactivated',
    );

    $this->db->where('supplier_id', $supplier_id);

    $response = $this->db->update('suppliers', $data);

    if ($response) {
      return $supplier_id;
    } else {
      return FALSE;
    }
  }

  public function reactivate_supplier($supplier_id)
  {
    $data = array(
      'status_supplier' => 'active',
    );

    $this->db->where('supplier_id', $supplier_id);

    $response = $this->db->update('suppliers', $data);

    if ($response) {
      return $supplier_id;
    } else {
      return FALSE;
    }
  }
}
