<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Products</h1></br>
        <h1 class="m-0 text-dark">
            <a href="<?php echo site_url('main/product'); ?>" class="btn btn-primary btn-sm btn-dark"><i class="fas fa-boxes"></i> Products</a>
            <a href="<?php echo site_url('main/product_category'); ?>" class="btn btn-primary btn-sm btn-dark"><i class="fas fa-list"></i> Product Category</a>
        </h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/index') ?>">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<div class="card card-outline card-success" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-header  ">

        <a href="<?php echo site_url('main/add_product'); ?>" class="btn btn-primary btn-sm "><i class="fas fa-plus"></i> Add Product </a>

        <div class="float-right">
            <a href="<?php echo site_url('main/printproduct'); ?>" class="btn btn-info btn-sm"><i class="fas fa-print"></i> Print </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="user-datatables">
                <thead>
                    <tr class="text-center">
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Generic Name</th>
                        <th>Product Category</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Selling Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($product) && !empty($product)) {
                        foreach ($product as $key => $pro) {
                            $product_id = $pro->product_id;

                    ?>

                            <tr class="text-center">

                                <td><?php echo $pro->product_code; ?></td>
                                <td><?php echo $pro->product_brand; ?></td>
                                <?php $product_prescription = $pro->product_prescription;
                                if ($product_prescription == '1') { ?>
                                    <td><?php echo $pro->product_name; ?>
                                        <span class="badge badge-warning"><i class="fas fa-check"></i></span>
                                    </td>
                                <?php } else { ?>
                                    <td><?php echo $pro->product_name; ?></td>
                                <?php } ?>

                                <td><?php echo $pro->product_category; ?></td>
                                <td><?php echo $pro->product_quantity; ?></td>
                                <td><?php echo $pro->product_unit; ?></td>
                                <td>₱<?php echo $pro->product_sellingprice; ?></td>
                                <td>

                                    <a href="<?php echo site_url('main/view_product/' . $product_id); ?>" style="color: darkcyan; padding-left:6px;" title="Click here to view product details"><i class="fas fa-eye"></i></a>

                                    <a href="<?php echo site_url('main/edit_product/' . $product_id); ?>" style="color:gold; padding-left:6px;" title="Click here to edit product details"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo site_url('main/delete_product/' . $product_id); ?>" onclick="return confirm('Are you sure you want to delete product?')" style="color:red; padding-left:6px;" title="Click here to delete product"><i class="fas fa-trash"></i></a>


                                <?php
                            } ?>

                                </td>
                            </tr>
                        <?php
                    }


                        ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        <?php if ($this->session->flashdata('success')) { ?>
            toastr.success('<?php echo $this->session->flashdata('success'); ?>');
        <?php } elseif ($this->session->flashdata('error')) { ?>
            toastr.error('<?php echo $this->session->flashdata('error'); ?>');
        <?php } ?>
    });
</script>