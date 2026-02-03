<h4>Inventory Adjustment</h4>
<div class="card card-outline card-success">
    <div class="card-header text-end">
        <a href="<?php echo site_url('main/printproduct'); ?>" class="btn btn-success btn-sm "><i class="fas fa-print"></i>
            Print Inventory Report</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="user-datatables">
                <thead>
                    <tr class="text-center">
                        <th>Code</th>
                        <th>Product Name</th>
                        <th>Brand</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Critical Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Initialize DataTable with server-side processing
        if ($.fn.DataTable.isDataTable('#user-datatables')) {
            $('#user-datatables').DataTable().destroy();
        }
        
        $('#user-datatables').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('main/get_inventory_adjustment_ajax'); ?>",
                "type": "POST"
            },
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { "data": 3 },
                { "data": 4 },
                { "data": 5, "orderable": false },
                { "data": 6, "orderable": false }
            ],
            "order": [[0, 'asc']],
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
        });

        <?php if ($this->session->flashdata('success')) { ?>
            toastr.success('<?php echo $this->session->flashdata('success'); ?>');
        <?php } elseif ($this->session->flashdata('error')) { ?>
            toastr.error('<?php echo $this->session->flashdata('error'); ?>');
        <?php } ?>
    });
</script>