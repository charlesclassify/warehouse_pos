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

<h4>Goods Return List</h4>
<div class="card card-outline card-success">
    <div class="card-header">
        <h2 class="text-dark">
            <a href="<?php echo site_url('main/goods_return'); ?>" class="btn btn-dark"><i class="fas fa-boxes"></i> Goods Return</a>
            <a href="<?php echo site_url('main/goods_return_list'); ?>" class="btn btn-dark"><i class="fas fa-list"></i> Goods Return List</a>
        </h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="user-datatables" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Goods Return No.</th>
                        <th>Supplier</th>
                        <th>Date Returned</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($gr1 as $pur) { ?>
                        <tr class="text-center">

                            <td><?= $pur->goods_return_no ?></td>
                            <td><?= $pur->supplier_name ?></td>
                            <td><?= $pur->date_returned ?></td>
                            <td>₱<?= $pur->grt_total_cost ?></td>
                            <td>
                                <?php if ($pur->status == "returned") { ?>

                                    <span class="badge bg-danger">
                                        <?= ucfirst($pur->status) ?>
                                    </span>

                                <?php
                                } else {
                                ?>
                                    <span class="badge bg-warning">
                                        <?= ucfirst($pur->status) ?>
                                    </span>

                                <?php
                                } ?>
                            </td>
                            <td>
                                <a href="<?php echo site_url('main/view_goods_return/' . $pur->goods_return_no_id); ?>" style="color: darkcyan; padding-left:6px;" title="View goods return">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
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