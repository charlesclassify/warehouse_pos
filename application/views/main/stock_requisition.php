<?= $this->session->flashdata('exceeds'); ?>

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

<h4>Stock Requisition</h4>
<div class="card card-outline" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-header  ">

        <a href="<?php echo site_url('main/add_stock_requisition'); ?>" class="btn btn-dark btn-sm "><i class="fas fa-plus"></i> Create Stock Request</a>

        <div class="float-right">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="user-datatables">
                <thead>
                    <tr class="text-center">
                        <th>Stock Requisition No.</th>

                        <th>Date Created</th>
                        <th>Total Cost</th>

                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($sr as $s) {    ?>
                        <tr class="text-center">
                            <td><?= $s->stock_requisition_no ?></td>
                            <td><?= $s->date_created ?></td>
                            <td>₱<?= $s->total_cost ?></td>
                            <td>
                                <?php if ($s->status == "Received") { ?>
                                    <span class="badge bg-success"><?= ucfirst($s->status) ?></span>
                                <?php } else if ($s->status == "cancelled") { ?>
                                    <span class="badge bg-danger"><?= ucfirst($s->status) ?></span>
                                <?php } else if ($s->status == "pending") { ?>
                                    <span class="badge bg-info"><?= ucfirst($s->status) ?></span>
                                <?php } else { ?>
                                    <span class="badge bg-warning"><?= ucfirst($s->status) ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="<?= site_url('main/view_stock_requisition/' . $s->stock_requisition_id); ?>" style="color: darkcyan; padding-left:6px;" title="Click here to view stock requisition"><i class="fas fa-eye"></i></a>
                                <?php if ($s->status == "To Be Delivered" || $s->status == "pending") { ?>
                                    <!-- Don't show the edit button if the SR is approved or cancelled -->
                                    <a href="<?= site_url('main/edit_stock_requisition/' . $s->stock_requisition_id); ?>" style="color:gold; padding-left:6px;" title="Click here to edit stock requisition"><i class="fas fa-edit"></i></a>
                                    <?php
                                    if ($s->status == "approved") { ?>
                                        <!-- Don't show the cancel button if the SR is approved or cancelled -->
                                    <?php } else { ?>
                                        <a href="<?= site_url('main/cancel_sr/' . $s->stock_requisition_id); ?>" onclick="return confirm('Are you sure you want to cancel this data?')" style="color:red; padding-left:6px;" title="Click here to cancel stock requisition"><i class="fas fa-ban"></i></a>
                                    <?php } ?>
                                    <a href="<?= site_url('main/delete_sr/' . $s->stock_requisition_id); ?>" onclick="return confirm('Are you sure you want to delete this data?')" style="color:red; padding-left:6px;" title="Click here to delete stock requisition"><i class="fas fa-trash"></i></a>
                                <?php } ?>
                            </td>
                        <?php } ?>
                        </tbodyo>

            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#sr-datatables").DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [5, 10, 25, 50, 100]
        });
    });
</script>
<script>
    $(document).ready(function() {
        <?php if ($this->session->flashdata('success')) { ?>
            toastr.success('<?php echo $this->session->flashdata('success'); ?>');
        <?php } elseif ($this->session->flashdata('error')) { ?>
            toastr.error('<?php echo $this->session->flashdata('error'); ?>');
        <?php } ?>
    });
</script>