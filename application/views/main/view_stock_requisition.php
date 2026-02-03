<?= $this->session->flashdata('success'); ?>
<?= $this->session->flashdata('error'); ?>
<?= $this->session->flashdata('exceeds'); ?>
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">View Stock Requisition</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('inventory/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Stock Requisition</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->


<?php echo form_open_multipart('', array('onsubmit' => 'return confirm(\'Are you sure you want to post this data?\')')); ?>
<main class="content">

    <div class="container-fluid">
        <!--start select catergory-->
        <div class="row mb-3">
            <!--start purchase request no-->
            <div class="col-3">
                <label>Stock Requisition No.</label>
                <input type="text" name="stock_requisition_no" value="<?= $code->stock_requisition_no ?>" readonly class="form-control form-control-sm">
                <input type="hidden" name="sr_id" value="<?= $code->stock_requisition_id ?>">
            </div>

            <div class="col-3">
                <label>Date Created</label>
                <input type="text" name="date_created" value="<?= $code->date_created ?>" readonly class="form-control form-control-sm">
            </div>
            <input type="hidden" name="date_posted" value="<?= date('m-d-Y h:i A'); ?>" readonly class="form-control form-control-sm">
            <div class="col-3">
                <label>Branch</label>
                <input type="text" name="branch" value="<?= $select->branch ?>" readonly class="form-control form-control-sm"><br>
            </div>
            <div class="col-3">
                <label>Prepared By</label>
                <input type="text" name="branch" value="<?= $select->prepared_by ?>" readonly class="form-control form-control-sm"><br>
            </div>
            <!--start purchase request no-->
        </div>
        <!--Start Table-->

        <div class="card border-success mb-3" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" id="card">
            <div class="card-body" id="card-body">
                <table class="table table-sm table-bordered text-center" id="table_field">
                    <thead>

                        <tr>
                            <th style="width: 250px;" id="table_style">Product Name</th>
                            <th style="width: 160px;" id="table_style">Quantity</th>
                            <th style="width: 160px;" id="table_style">Unit</th>
                            <th style="width: 160px;" id="table_style">Price</th>
                        </tr>
                    </thead>
                    <?php foreach ($view as $row) { ?>
                        <input type="hidden" name="sr_code[]" value="<?= $row->sr_id ?> ">
                        <tbody class="row_content" id="row_product">
                            <tr>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->product_name ?>" name="product[]" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" name="quantity[]" value="<?= $row->quantity ?>" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" name="unit[]" value="<?= $row->unit ?>" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" name="product_sellingprice[]" value="<?= $row->product_sellingprice ?>" readonly>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                                <td id="total_cost" class="total_cost">0</td>
                            </tr>
                        </tfoot>
                </table>
            </div>
            <div class="card-footer bg-transparent border-success text-end">
                <?php

                if ($row->status == "To Be Delivered" || $row->status == "cancelled" || $row->status == "Received") {
                } else {
                ?>
                    <button type="submit" name="submit" class="btn btn-success btn-sm"><i class="fas fa-inbox"></i> Approve</button>
                    <a class="btn btn-danger btn-sm" href="<?= site_url('main/cancel_sr/' . $row->stock_requisition_id); ?>" onclick="return confirm('Are you sure you want to cancel this data?')" style="color:white; padding-left:6px;"><i class="fas fa-ban"></i> Cancel</a>
                <?php
                }

                ?>

                <?php

                if ($row->status != "Received" && $row->status != "pending" && $row->status != "cancelled") {
                ?>
                    <a class="btn btn-success btn-sm" href="<?= site_url('main/received_sr/' . $row->stock_requisition_id); ?>" style="color:white; padding-left:6px;"><i class="fas  fa-check"></i> Received</a>
                <?php

                } else {
                ?>


                <?php
                }
                ?>

                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/print_sr/' . $row->stock_requisition_id); ?>"><i class="fas fa-print"></i> Print</a>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/stock_requisition') ?>"><i class="fas fa-reply"></i> back</a>
            </div>
        </div>
        <!--START TABLE-->
    </div>
    </div>
    <script>
        $(document).ready(function() {
            calculateTotalCost();

            $(document).on('input', '#table_field input, #table_field select', function() {
                calculateTotalCost();
            });
        });

        function calculateTotalCost() {
            var totalCost = 0;

            $('.row_content tr').each(function() {
                var quantity = $(this).find('input[name="quantity[]"]').val();
                var price = $(this).find('input[name="product_sellingprice[]"]').val();

                if (quantity != '' && price != '') {
                    var subtotal = parseFloat(quantity) * parseFloat(price);
                    totalCost += subtotal;
                    $(this).find('#total_cost').html(subtotal.toFixed(2));
                }
            });
            console.log('Total Cost:', totalCost);
            $('#total_cost').html(totalCost.toFixed(2));
        }
    </script>