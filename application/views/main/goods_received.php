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

    h4 {
        margin-left: 40px;
    }
</style>

<h4>Waiting For Delivery</h4>
<div class="card card-outline card-success">
    <div class="card-header">
        <h2 class="text-dark">
            <a href="<?php echo site_url('main/goods_received'); ?>" class="btn btn-dark"><i class="fas fa-boxes"></i> To Be Received</a>
            <a href="<?php echo site_url('main/goods_received_list'); ?>" class="btn btn-dark"><i class="fas fa-list"></i> Goods Received List</a>
        </h2>
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
                                <span class="badge bg-warning"><?= ucfirst($pur->status) ?></span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('main/post_goods_received/' . $pur->purchase_order_no_id); ?>" style="color: darkcyan; padding-left:6px;">
                                    <i class="fas fa-inbox" aria-hidden="true"></i>
                                </a>
                            </td>
                        <?php } ?>
                        </tr>
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