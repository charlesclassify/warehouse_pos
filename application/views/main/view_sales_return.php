<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">View Sales Return</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('inventory/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Sales Return</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-5">
                <strong>Reference No.: </strong><span> <?= $code->reference_no ?></span></br>
                <strong>Date Created:</strong><span> <?= $code->date_created ?></span></br>
                <strong>Date Returned:</strong><span> <?= $code1->date_returned ?></span></br>
                <strong>Prepared By:</strong><span> <?= $code1->return_prepared_by ?></span></br>
            </div>
            <a class="brand-link float-right">
                <img src="<?php echo base_url(); ?>/assets/template/dist/img/logo.jpg" alt="AdminLTE Logo" style="width:400px;height:80px;">
            </a>
        </div></br>

        <!--start table-->
        <div class="card border-success mb-3" style="max-width: 75rem; border-radius:15px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">

            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>

                        <tr>
                            <th id="table_style">Product Brand</th>
                            <th id="table_style">Product Name</th>
                            <th id="table_style">Unit</th>
                            <th id="table_style">Sold Quantity</th>
                            <th id="table_style">Returned Quantity</th>
                            <th id="table_style">Selling Price</th>
                            <th id="table_style">Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($view1 as $row) {
                        ?>
                            <tr>
                                <td><?= $row->product_brand; ?></td>
                                <td><?= $row->product_name; ?></td>
                                <td><?= $row->product_unit; ?></td>
                                <td><?= $row->quantity; ?></td>
                                <td><?= $row->returned_quantity; ?></td>
                                <td>₱<?= $row->product_sellingprice; ?></td>
                                <td><?= $row->reason; ?></td>
                            </tr>

                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                            <td id="total_cost" class="total_cost">₱<?= $row->sr_total_cost; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div>

            <a class="btn btn-secondary btn-sm" href="<?= base_url('main/sales_return_list') ?>"><i class="fas fa-reply"></i> back</a>
        </div>
        <!--START TABLE-->

    </div>
</div>