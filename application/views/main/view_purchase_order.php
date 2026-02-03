<div class="container">
    <h4 class="text-white">View Purchase Request</h4>

    <style>
        .container {
            padding-top: 5px;
            padding-bottom: 20px;
            width: 2000px;
        }

        .container h1 {
            font-size: 70px;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 16px;
        }

        .form-control {
            font-size: 16px;
        }

        .table {
            font-size: 16px;
        }

        .table th,
        .table td {
            padding: 10px;
        }

        .card-body {
            overflow: auto;
            /* Add this line to make the card body scrollable */
        }

        .btn-sm {
            font-size: 16px;
        }

        .total-cost {
            font-weight: bold;
        }
    </style>

    <form action="" method="post" onsubmit="return confirm('Are you sure you want to add this purchase order?')">

        <div class="row mb-3">
            <div class="col-12 col-sm-3">
                <label for="purchase_order_no" class="form-label text-white">Purchase Request No</label>
                <input type="text" value=" <?= $code->purchase_order_no ?>" name="purchase_order_no" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="date_created" class="form-label text-white">Date Created</label>
                <input type="text" id="date_created" name="date_created" value="<?= $code->date_created ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="supplier_id" class="form-label text-white">Supplier</label>
                <input type="text" id="supplier_id" name="supplier_id" value="<?= $select->supplier_name ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="payment_method" class="form-label text-white">Payment Method</label>
                <input type="text" id="payment_method" name="payment_method" value="<?= $code->payment_method ?>" readonly class="form-control form-control-sm">
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>
                        <tr>
                            <th id="table_style">Product Name</th>
                            <th id="table_style">Quantity</th>
                            <th id="table_style">UoM</th>
                            <th id="table_style">Product Cost</th>
                            <th id="table_style">VAT Type</th>
                            <th id="table_style">VAT Amount (%)</th>
                            <th id="table_style">Net Product Cost</th>
                        </tr>
                    </thead>
                    <?php foreach ($view as $row) {
                    ?>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" id="product_name" name="product_name[]" value="<?= $row->product_name; ?>" readonly class="form-control form-control-sm">
                                </td>
                                <td> <input type="number" id="po_product_quantity" name="po_product_quantity" value="<?= $row->po_product_quantity; ?>" readonly class="form-control form-control-sm"></td>
                                <td> <input type="text" id="product_unit" name="product_unit[]" value="<?= $row->product_unit; ?>" readonly class="form-control form-control-sm">
                                <td> <input type="text" id="product_cost" value="<?= $row->product_unitprice; ?>" name="product_cost" readonly class="form-control form-control-sm"></td>
                                <td>
                                    <select class=" form-control " data-live-search=" true" data-style="btn-outline-secondary" title="Select VAT" name="product_vat[]" required>
                                        <option value="" selected hidden>Select Type</option>
                                        <option>VAT Exclusive</option>
                                        <option>VAT Inclusive</option>
                                        <option>VAT Exempt</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" placeholder="Enter VAT Amount" name="tax_amount[]" min="0" max="100" value="<?= set_value('tax_amount'); ?>" class="form-control" id="tax_amount" required>
                                </td>
                                <td>
                                    <input type="number" placeholder="Net Product Cost" name="net_product_cost[]" min="0" value="<?= set_value('net_product_cost'); ?>" class="form-control" id="net_product_cost" readonly>
                                </td>
                            </tr>
                        </tbody>
                    <?php } ?>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                            <td id="total_cost" class="total_cost">₱<?= $row->total_cost; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer bg-transparent text-end">
                <span class="badge bg-warning float-start" style="font-size: 1.2em;"><?= ucfirst($row->status) ?></span>
                <?php if ($row->status == "Received" || $row->status == "cancelled" || $row->status == "To Be Received" || $row->status == "Back Order") { ?>
                    <!-- Don't show the approve and cancel buttons if the PO is already received, cancelled, or on back order -->
                <?php } else { ?>
                    <a class="btn btn-success btn-sm" href="<?= site_url('main/approved_po/' . $row->purchase_order_no_id); ?>" onclick="return confirm('Are you sure you want to approve this PO?')" style="color:white; padding-left:6px;"><i class="fas  fa-check"></i> Approve</a>
                <?php } ?>

                <?php if ($row->status == "Received" || $row->status == "cancelled" || $row->status == "To Be Received" || $row->status == "Back Order") { ?>
                    <!-- Don't show the approve and cancel buttons if the PO is already received, cancelled, or on back order -->
                <?php } else { ?>
                    <a class="btn btn-danger btn-sm" href="<?= site_url('main/cancel_po/' . $row->purchase_order_no_id); ?>" onclick="return confirm('Are you sure you want to cancel this PO?')" style="color:white; padding-left:6px;"><i class="fas fa-ban"></i> Cancel</a>
                <?php } ?>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/print_purchase_order/' . $row->purchase_order_no_id); ?>"><i class="fas fa-print"></i> Print</a>
                <a class="btn btn-secondary btn-sm" href="<?= base_url('main/purchase_order') ?>"><i class="fas fa-reply"></i> back</a>
            </div>
        </div>
    </form>
</div>
<script>
    function calculateNetTotalCost(row) {
        const cost = parseFloat(row.querySelector('input[name="product_cost"]').value);
        const taxAmount = parseFloat(row.querySelector('input[name="tax_amount[]"]').value);
        const taxType = row.querySelector('select[name="product_vat[]"]').value;
        const netproductcost = row.querySelector('input[name="net_product_cost[]"]'); // Change this line

        if (taxType == "VAT Exclusive" && !isNaN(taxAmount)) {
            const percentage = taxAmount / 100;
            const result = cost + (cost * percentage);
            netproductcost.value = result.toFixed(2); // Change this line
        } else if (taxType == "VAT Inclusive" && !isNaN(taxAmount)) {
            const percentage = taxAmount / 100;
            const result = cost - (cost * percentage);
            netproductcost.value = result.toFixed(2); // Change this line
        } else if (taxType == "VAT Exempt" && !isNaN(taxAmount)) {
            netproductcost.value = cost.toFixed(2); // Change this line
        }
    }

    const taxAmountFields = document.querySelectorAll('input[name="tax_amount[]"]');
    const taxTypeFields = document.querySelectorAll('select[name="product_vat[]"]');

    taxAmountFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateNetTotalCost(row);
        });
    });

    taxTypeFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateNetTotalCost(row);
        });
    });
</script>