<div class="row mb-2">
    <div class="col-sm-6">
        <h4>Add Branch</h4>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Branch</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<?php echo form_open_multipart('main/add_branch'); ?>

<div class="panel panel-default text-center">
    <div class="panel-body">
        <div>
            <label>Branch Name</label></br>
            <input type="text" placeholder="Branch Name" name="branch" value="<?php echo set_value('branch'); ?>" class="form-control form-control-sm d-inline-block col-5 text-center">
            <?php echo form_error('branch'); ?>
        </div></br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" class="form-submit"><i class="fas fa-save"></i> Submit</button>
            <button type="reset" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Clear</button>
            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/branch') ?>"><i class="fas fa-reply"></i> back</a>
        </div>
    </div>
</div>