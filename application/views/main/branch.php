<h4>Branch </h4>

<div class="card card-outline card-success" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-header  ">

        <a href="<?php echo site_url('main/add_branch'); ?>" class="btn btn-success btn-sm "><i class="fas fa-plus"></i> Add Branch</a>

        <div class="float-right">
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="user-datatables">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Branch</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;

                    foreach ($branch as $pc) : ?>
                        <tr class="text-center">

                            <td><?= $no++ ?></td>
                            <td><?php echo $pc->branch; ?></td>
                            <td>

                                <a href="<?php echo site_url('main/edit_branch/' . $pc->branch_id); ?>" style="color:gold; padding-left:6px;" title="Click here to edit branch"><i class="fas fa-edit"></i></a>
                                <a href="<?php echo site_url('main/delete_branch/' . $pc->branch_id); ?>" onclick="return confirm('Are you sure you want to delete this branch?')" style="color:red; padding-left:6px;" title="Click here to delete this branch"><i class="fas fa-trash"></i></a>

                            </td>
                        </tr>
                    <?php endforeach ?>
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