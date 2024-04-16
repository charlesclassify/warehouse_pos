<style>
  .form-group {
    margin: 15px;
  }

  .bold-label {
    margin: 5px;
  }
</style>


<h4>Add Supplier</h4>
<?php echo form_open_multipart('', array('onsubmit' => 'return confirm(\'Are you sure you want to add this supplier?\')')); ?>
<div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_name">Contact Persons Name</label>
          <input type="text" id="supplier_name" name="supplier_name" placeholder="Supplier Name" class="form-control <?php echo form_error('supplier_name') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_name'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_name'); ?></span>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="company_name">Company</label>
          <input type="text" id="company_name" name="company_name" placeholder="Company" class="form-control <?php echo form_error('company_name') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('company_name'); ?>">
          <span class="text-danger"><?php echo form_error('company_name'); ?></span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_contact">Contact Number</label>
          <input type="tel" id="supplier_contact" name="supplier_contact" placeholder="Contact" maxlength="11" class="form-control <?php echo form_error('supplier_contact') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_contact'); ?>" pattern="[0-9]+">
          <span class="text-danger"><?php echo form_error('supplier_contact'); ?></span>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_email">Email</label>
          <input type="email" id="supplier_email" name="supplier_email" placeholder="Email" class="form-control <?php echo form_error('supplier_email') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_email'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_email'); ?></span>
        </div>
      </div>
    </div>


    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_street">Street</label>
          <input type="text" id="supplier_street" name="supplier_street" placeholder="Street" class="form-control <?php echo form_error('supplier_street') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_street'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_street'); ?></span>
        </div>
      </div>

      <div class="col-md-6 d-inline-block">
        <div class="form-group">
          <label for="supplier_barangay">Barangay</label>
          <input type="text" id="supplier_barangay" name="supplier_barangay" placeholder="Barangay" class="form-control <?php echo form_error('supplier_barangay') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_barangay'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_barangay'); ?></span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_city">City</label>
          <input type="text" id="supplier_city" name="supplier_city" placeholder="City" class="form-control <?php echo form_error('supplier_city') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_city'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_city'); ?></span>
        </div>
      </div>

      <div class="col-md-6">
        <div class="form-group">
          <label for="supplier_province">Province</label>
          <input type="text" id="supplier_province" name="supplier_province" placeholder="Province" class="form-control <?php echo form_error('supplier_province') ? 'is-invalid' : ''; ?>" value="<?php echo set_value('supplier_province'); ?>">
          <span class="text-danger"><?php echo form_error('supplier_province'); ?></span>
        </div>
      </div>

    </div>

    <div class="mt-4">
      <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save"></i> Submit</button>
      <button type="reset" class="btn btn-danger"><i class="fas fa-trash"></i> Clear</button>
      <a class="btn btn-secondary" href="<?= base_url('main/supplier') ?>"><i class="fas fa-reply"></i> Back</a>
    </div>
  </div>
</div>
</form>
</div>