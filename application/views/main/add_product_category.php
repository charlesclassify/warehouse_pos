<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Add Product Category</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Product Category</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<?php echo form_open_multipart('main/add_product_category'); ?>

<div class="panel panel-default text-center">
    <div class="panel-body">
        <div>
            <label>Category Name</label></br>
            <input type="text" placeholder="Product Category Name" name="product_category" value="<?php echo set_value('product_category'); ?>" class="form-control form-control-sm d-inline-block col-5 text-center">
            <?php echo form_error('product_category'); ?>
        </div></br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" class="form-submit"><i class="fas fa-save"></i> Submit</button>
            <button type="reset" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Clear</button>
            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/product_category') ?>"><i class="fas fa-reply"></i> back</a>
        </div>
    </div>
</div>