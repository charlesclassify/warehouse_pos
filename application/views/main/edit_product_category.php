<?= $this->session->flashdata('success'); ?>
<?= $this->session->flashdata('error'); ?>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Edit Product Category</h1>
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
        <div>
            <label>Product Category</label></br>
            <input type="text" name="product_category" class="form-control form-control-sm d-inline-block col-5" value="<?php echo set_value('product_category', $procat->product_category); ?>">
        </div></br>
        <div>
            <button type="submit" name="submit" class="btn btn-primary btn-sm" class="form-submit"><i class="fas fa-save"></i> Submit</button>

            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/product_category') ?>"><i class="fas fa-reply"></i> back</a>
        </div>
    </div>
    <input type="hidden" name="procat_id" value="<?php echo $procat->procat_id; ?>">
</div>
</form>