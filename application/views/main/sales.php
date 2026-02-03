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
<h4>Products Sales</h4>
<div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-stripped table-sm" id="user-datatables">
                <thead>
                    <tr class="text-center">
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>SRP</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($result) && !empty($result)) {
                        foreach ($result as $key => $row) {
                    ?>
                            <tr class="text-center">
                                <td>
                                    <?php echo $row->product_code; ?>
                                </td>
                                <td>
                                    <?php echo $row->product_name; ?>
                                </td>
                                <td>
                                    ₱<?php echo $row->product_price; ?>
                                </td>

                                <td>
                                    <a href="<?php echo site_url('main/edit_sales_product/' . $row->product_id); ?>" class="btn btn-dark btn-sm"><i class="fas fa-edit"></i> Add SRP</a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
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