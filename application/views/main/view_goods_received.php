<style>
    .container {
        padding-top: 5px;
        padding-bottom: 20px;
    }

    .container h1 {
        font-size: 70px;
        text-align: center;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 16px;
    }

    .form-control {
        font-size: 16px;
    }

    .table {
        font-size: 16px;
    }

    .table th,
    .table td {
        padding: 10px;
    }

    .card-body {
        overflow: auto;
        /* Add this line to make the card body scrollable */
    }

    .btn-sm {
        font-size: 16px;
    }

    .total-cost {
        font-weight: bold;
    }
</style>

<div class="container">
    <h4 class="text-white">View Goods Received</h4>
    <form action="" method="post" onsubmit="return confirm('Are you sure you want to add this purchase order?')">
        <div class="row mb-3">
            <div class="col-12 col-sm-3">
                <label for="goods_received_no" class="form-label text-white">Goods Received No</label>
                <input type="text" value=" <?= $code->goods_received_no ?>" name="goods_received_no" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="date_received" class="form-label text-white">Date Received</label>
                <input type="text" id="date_received" name="date_received" value="<?= $code->date_received ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="supplier_id" class="form-label text-white">Supplier</label>
                <input type="text" id="supplier_id" name="supplier_id" value="<?= $select->supplier_name ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="payment_method" class="form-label text-white">Payment Method</label>
                <input type="text" id="payment_method" name="payment_method" value="<?= $code->payment_method ?>" readonly class="form-control form-control-sm">
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>

                        <tr>
                            <th id="table_style">Product Name</th>
                            <th id="table_style">Quantity</th>

                            <th id="table_style">Received Quantity</th>

                            <th id="table_style">Unit</th>
                            <th id="table_style">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($view as $row) {
                        ?>
                            <tr>
                                <td><?= $row->product_name; ?></td>
                                <td><?= $row->gr_total_quantity; ?></td>
                                <td><?= $row->gr_received_quantity; ?></td>
                                <td><?= $row->product_unit; ?></td>
                                <td>₱<?= $row->gr_product_unitprice; ?></td>
                            </tr>

                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                            <td id="total_cost" class="total_cost">₱<?= $row->gr_total_cost; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer bg-transparent text-end">
                <span class="badge bg-warning float-start" style="font-size: 1.2em;"><?= $row->status; ?></span>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/print_purchase_order/' . $row->purchase_order_no_id); ?>"><i class="fas fa-print"></i> Print</a>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/goods_received_list') ?>"><i class="fas fa-reply"></i> back</a>
            </div>
        </div>
    </form>
</div>