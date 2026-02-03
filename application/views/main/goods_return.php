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

<h4>Goods Return</h4>
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
                        <th>Goods Received No.</th>
                        <th>Supplier</th>
                        <th>Date Issued</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grt as $grs) { ?>
                        <tr class="text-center">

                            <td><?= $grs->goods_received_no ?></td>
                            <td><?= $grs->supplier_name ?></td>
                            <td><?= $grs->date_received ?></td>
                            <td>
                                <span class="badge bg-success"><?= ucfirst($grs->status) ?></span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('main/post_goods_return/' . $grs->goods_received_no_id); ?>" style="color: darkcyan; padding-left:6px;" title="Click here to view purchase order"><i class="fas fa-inbox"></i></a>
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