<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Sales Return</h1></br>
        <h1 class="text-dark">
            <a href="<?php echo site_url('main/record_sales'); ?>" class="btn btn-primary btn-sm btn-dark"><i class="fas fa-boxes"></i> Record Sales</a>
            <a href="<?php echo site_url('main/sales_return'); ?>" class="btn btn-primary btn-sm btn-dark"><i class="fas fa-shopping-basket"></i> Sales Return</a>
            <a href="<?php echo site_url('main/sales_return_list'); ?>" class="btn btn-primary btn-sm btn-dark"><i class="fas fa-list"></i> Sales Return List</a>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/index') ?>">Home</a></li>
            <li class="breadcrumb-item active">Sales Return</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<div class="card card-outline card-success" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-header  ">
        <div class="float-right">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="po-datatables">
                <thead>
                    <tr class="text-center">
                        <th>Reference No.</th>
                        <th>Date Posted</th>
                        <th>Prepared By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sales as $s) { ?>
                        <tr class="text-center">

                            <td><?= $s->reference_no ?></td>
                            <td><?= $s->date_created ?></td>
                            <td><?= $s->prepared_by ?></td>
                            <td>
                                <?php if ($s->status == "sold") { ?>
                                    <a href="<?php echo site_url('main/post_sales_return/' . $s->sales_id); ?>" style="color: darkcyan; padding-left:6px;" title="Click here to post sales return">
                                        <i class="fas fa-inbox"></i>
                                    </a>
                                <?php } else { ?>
                                    <span class="badge bg-warning"><?= ucfirst($s->status) ?></span>
                                <?php } ?>
                            </td>

                        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#po-datatables").DataTable({
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