<style>
    .card {
        width: 95%;
        /* Adjust the width as needed */
        margin: 20px auto;
        /* Center the card on the page horizontally */
    }

    .row {
        margin-top: 10px;
    }

    h1 {
        margin-left: 50px;
    }
</style>

<h1>Purchase Orders</h1>
<div class="card card-outline card-success" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-header  ">
        <a href="<?php echo site_url('main/add_purchase_order'); ?>" class="btn btn-primary btn-sm "><i class="fas fa-plus"></i> Create Purchase Order</a>
        <div class="float-right">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="user-datatables" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Purchase Order No.</th>
                        <th>Supplier</th>
                        <th>Date Created</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($po as $pur) { ?>
                        <tr class="text-center">

                            <td><?= $pur->purchase_order_no ?></td>
                            <td><?= $pur->supplier_name ?></td>
                            <td><?= $pur->date_created ?></td>
                            <td>₱<?= $pur->total_cost ?></td>
                            <td>
                                <?php if ($pur->status == "Received") { ?>
                                    <span class="badge bg-success"><?= ucfirst($pur->status) ?></span>
                                <?php } else if ($pur->status == "Cancelled") { ?>
                                    <span class="badge bg-danger"><?= ucfirst($pur->status) ?></span>
                                <?php } else if ($pur->status == "pending") { ?>
                                    <span class="badge bg-info"><?= ucfirst($pur->status) ?></span>
                                <?php } else if ($pur->status == "back Order") { ?>
                                    <span class="badge bg-secondary"><?= ucfirst($pur->status) ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-warning"><?= ucfirst($pur->status) ?></span>
                                <?php } ?>
                            </td>
                            <a href="<?php echo site_url('main/print_purchase_order/' . $pur->purchase_order_no_id); ?>" style="color: darkcyan; padding-left:6px;"><i class="fas fa-print"></i></a>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>