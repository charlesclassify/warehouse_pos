<style>
    .container {
        padding-top: 5px;
        padding-bottom: 20px;
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
<?php echo form_open_multipart('main/post_goods_received_submit', array('onsubmit' => 'return confirm(\'Are you sure you want to post this goods received?\')')); ?>
<div class="container">
    <h4 class="text-white">Post Goods Received</h4>
    <form action="" method="post" onsubmit="return confirm('Are you sure you want to add this purchase order?')">
        <div class="row mb-3">
            <div class="col-12 col-sm-3">
                <label for="purchase_order_no" class="form-label text-white">Purchase Order No</label>
                <input type="text" value=" <?= $code->purchase_order_no ?>" name="purchase_order_no" readonly class="form-control form-control-sm">
                <input type="hidden" value="<?= $gr_no ?>" name="goods_received_no" readonly class="form-control form-control-sm">
                <input type="hidden" name="gr_id" value="<?= $select->purchase_order_no_id ?>">
            </div>
            <div class="col-12 col-sm-3">
                <label for="date_created" class="form-label text-white">Date Received</label>
                <input type="text" value="<?= date('m-d-Y h:i A'); ?>" name="date_received" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="supplier_id" class="form-label text-white">Supplier</label>
                <input type="text" id="" name="" value="<?= $select->supplier_name ?>" readonly class="form-control form-control-sm">
                <input type="hidden" value="<?= $select->supplier_id ?>" name="supplier_id">
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
                            <th style="width: 200px;" id="table_style">Product Name</th>
                            <th id="table_style">Unit</th>
                            <th id="table_style">Ordered Quantity</th>
                            <th id="table_style">Unserved Quantity</th>
                            <th id="table_style">Price</th>
                            <th id="table_style">Total Cost</th>
                            <th id="table_style">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="row_content" id="row_product">
                        <?php foreach ($view as $row) {
                        ?>
                            <tr>
                                <input type="hidden" name="gr_code" value="<?= $row->purchase_order_id ?> ">
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->product_name ?>" name="product_name[]" readonly>



                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->product_unit ?>" name="product_unit[]" readonly>
                                    <?php foreach ($barcode as $bar) {
                                        if ($row->product_name === $bar->product_name && $row->product_unit === $bar->unit) { ?>
                                            <input type="hidden" name="product_barcode" value="<?= $bar->barcode; ?>">
                                    <?php }
                                    } ?>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->po_product_quantity ?>" name="po_product_quantity[]" min="0" readonly>
                                </td>
                                <td>

                                    <input class="form-control form-control-sm" type="number" name="unserved_quantity[]" id="unserved_quantity" min="0" max="<?= $row->po_product_quantity ?>" value="<?= $row->po_product_quantity ?>" readonly>

                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->product_unitprice ?>" id="product_unitprice" name="product_unitprice[]" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="total_price[]" id="total_price_display" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="date" name="expiry_date[]" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Grand Total Cost:</strong></td>
                            <td id="total_cost" class="total_cost">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer bg-transparent text-end">
                <?php if ($row->status == "Received") { ?>
                <?php
                } else {
                ?>
                    <input type="submit" value="Post" name="btn_post_gr" id="btn_post_gr" class="btn btn-sm btn-primary">
                <?php
                } ?>
                <a href="<?php echo site_url('main/goods_received'); ?>"><input type="button" value="Back" class=" btn btn-primary btn-sm  btn_save"></a>
            </div>
        </div>
    </form>
</div>
<script>
    // Function to calculate and update the total price for a specific row
    function calculateTotalPrice(row) {
        const unservedQuantity = parseFloat(row.querySelector('input[name="unserved_quantity[]"]').value);
        const orderedQuantity = parseFloat(row.querySelector('input[name="po_product_quantity[]"]').value);
        const unitPrice = parseFloat(row.querySelector('input[name="product_unitprice[]"]').value);
        const totalPriceField = row.querySelector('input[name="total_price[]"]');

        if (!isNaN(unservedQuantity) && !isNaN(unitPrice) && !isNaN(orderedQuantity)) {
            const difference = orderedQuantity - unservedQuantity;
            const total = difference * unitPrice;
            totalPriceField.value = total.toFixed(2);
        } else {
            totalPriceField.value = '';
        }
    }

    // Function to update the grand total cost for all products
    function updateGrandTotal() {
        let grandTotal = 0;
        const totalCostElements = document.querySelectorAll('input[name="total_price[]"]');

        totalCostElements.forEach(function(element) {
            if (!isNaN(parseFloat(element.value))) {
                grandTotal += parseFloat(element.value);
            }
        });

        document.getElementById('total_cost').textContent = grandTotal.toFixed(2);
    }

    // Attach event listeners to input fields for real-time calculations
    const receivedQuantityFields = document.querySelectorAll('input[name="unserved_quantity[]"]');
    const unitPriceFields = document.querySelectorAll('input[name="product_unitprice[]"]');

    receivedQuantityFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateTotalPrice(row);
            updateGrandTotal();
        });
    });

    unitPriceFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateTotalPrice(row);
            updateGrandTotal();
        });
    });

    // Calculate totals for existing data when the page loads
    const tableRows = document.querySelectorAll('tbody.row_content tr');
    tableRows.forEach(function(row) {
        calculateTotalPrice(row);
    });

    // Update grand total on page load
    updateGrandTotal();

    let scannedBarcode = ''; // Initialize scanned barcode variable

    // Function to display the scanned barcode
    function displayBarcode() {
        const barcodeDisplay = document.getElementById('barcodeDisplay');


        // Loop through the product barcodes
        const productBarcodes = document.getElementsByName('product_barcode');

        const receivedQuantities = document.getElementsByName('unserved_quantity[]');


        for (let i = 0; i < productBarcodes.length; i++) {
            // Check if the scanned barcode matches any of the product barcodes
            if (scannedBarcode === productBarcodes[i].value) {
                // If match found, deduct unserved quantity by 1 for the corresponding product
                const currentQuantity = parseFloat(receivedQuantities[i].value);
                if (!isNaN(currentQuantity) && currentQuantity > 0) {
                    receivedQuantities[i].value = (currentQuantity - 1).toFixed();
                    calculateTotalPrice(receivedQuantities[i].parentNode.parentNode);
                    updateGrandTotal();
                }
                // Clear the scanned barcode after deduction
                scannedBarcode = '';
                displayBarcode(); // Update display to clear the scanned barcode message
                break; // Exit loop after deduction
            }
        }
    }

    // Listen for keydown events on the document
    document.addEventListener('keypress', function(event) {
        const char = String.fromCharCode(event.keyCode);
        scannedBarcode += char;
        displayBarcode();
    });
</script>