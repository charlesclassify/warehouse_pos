<?= $this->session->flashdata('success'); ?>
<?= $this->session->flashdata('error'); ?>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Unit</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Product Category</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->



<?php echo form_open_multipart('', array('onsubmit' => 'return confirm(\'Are you sure you want to update this data?\')')); ?>

<div class="panel panel-default text-center">
    <div class="panel-body">
        <div class="col-6 mx-auto">
            <label>Edit Unit</label></br>
            <input type="text" name="unit" class="form-control form-control-sm d-inline-block col-6 text-center" value="<?php echo set_value('unit', $unit->unit); ?>">
        </div></br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" class="form-submit"><i class="fas fa-save"></i> Submit</button>

            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/unit') ?>"><i class="fas fa-reply"></i> back</a>
        </div>
    </div>
    <input type="hidden" name="unit_id" value="<?php echo $unit->unit_id; ?>">
</div>
</form>