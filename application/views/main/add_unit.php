<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add New Unit</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Unit</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<?php echo form_open_multipart('main/add_unit'); ?>

<div class="panel panel-default text-center">
    <div class="panel-body">
        <div class="col-6 mx-auto">
            <label>Unit Name</label></br>
            <input type="text" id="unit" placeholder="Product Unit" name="unit" value="<?php echo set_value('unit'); ?>" class="form-control form-control-sm d-inline-block col-6 text-center">
            <?php echo form_error('unit'); ?>
        </div></br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" class="form-submit"><i class="fas fa-save"></i> Submit</button>
            <button type="reset" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Clear</button>
            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/unit') ?>"><i class="fas fa-reply"></i> Back</a>
        </div>
    </div>
</div>