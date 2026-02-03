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
<?php echo form_open_multipart('main/post_goods_return_submit', array('onsubmit' => 'return confirm(\'Are you sure you want to post this goods received?\')')); ?>
<div class="container">
    <h4 class="text-white">Post Goods Return</h4>
    <form action="" method="post" onsubmit="return confirm('Are you sure you want to add this purchase order?')">
        <div class="row mb-3">

            <input type="hidden" value="<?= $grt_no ?>" name="goods_return_no" readonly class="form-control form-control-sm">
            <input type="hidden" name="grt_id" value="<?= $select->goods_received_no_id ?>">

            <div class="col-12 col-sm-3">
                <label for="purchase_order_no" class="form-label text-white">Purchase Order No</label>
                <input type="text" value="<?= $select1->purchase_order_no ?>" name="purchase_order_no" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="Supplier" class="form-label text-white">Supplier</label>
                <input type="text" name="" id="" class="form-control form-control-sm" value="<?= $select->supplier_name ?>" readonly>
                <input type="hidden" value="<?= $select->supplier_id ?>" name="supplier_id">
            </div>
            <div class="col-12 col-sm-3">
                <label for="payment_method" class="form-label text-white">Date Returned</label>
                <input type="text" value="<?= date('m-d-Y h:i A'); ?>" name="date_returned" readonly class="form-control form-control-sm">
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>
                        <tr>
                            <th style="width: 250px;" id="table_style">Product Name</th>
                            <th id="table_style">Unit</th>
                            <th id="table_style">Received Quantity</th>
                            <th id="table_style">Returned Quantity</th>
                            <th id="table_style">Price</th>
                            <th id="table_style">Total Cost</th>
                            <th id="table_style">Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody class="row_content" id="row_product">
                        <?php foreach ($view as $row) {
                        ?>
                            <tr>
                                <input type="hidden" name="grt_code[]" value="<?= $row->goods_received_id ?> ">
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_product_name ?>" name="grt_product_name[]" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_unit ?>" name="grt_product_unit[]" readonly>
                                    <?php foreach ($barcode as $bar) {
                                        if ($row->gr_product_name === $bar->product_name && $row->gr_unit === $bar->unit) { ?>
                                            <input type="hidden" name="product_barcode" value="<?= $bar->barcode; ?>">
                                    <?php }
                                    } ?>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_received_quantity ?>" name="grt_received_quantity[]" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="grt_returned_quantity[]" id="grt_returned_quantity" value="0" min="0" max="<?= $row->gr_received_quantity ?>" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_product_unitprice ?>" id="price" name="grt_product_unitprice[]">
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="grt_total_price[]" id="grt_total_price" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_expiry_date ?>" name="grt_expiry_date[]" readonly>
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
                <input type="submit" value="Post" name="btn_post_grt" id="btn_post_grt" class="btn btn-sm btn-primary">
                <a href="<?php echo site_url('main/goods_return'); ?>"><input type="button" value="Back" class=" btn btn-primary btn-sm  btn_save"></a>
            </div>
        </div>
    </form>
</div>
<script>
    // Function to calculate and update the total price for a specific row
    function calculateTotalPrice(row) {
        const receivedQuantity = parseFloat(row.querySelector('input[name="grt_returned_quantity[]"]').value);
        const unitPrice = parseFloat(row.querySelector('input[name="grt_product_unitprice[]"]').value);
        const totalPriceField = row.querySelector('input[name="grt_total_price[]"]');

        if (!isNaN(receivedQuantity) && !isNaN(unitPrice)) {
            const total = receivedQuantity * unitPrice;
            totalPriceField.value = total.toFixed(2);
        } else {
            totalPriceField.value = '';
        }
    }

    // Function to update the grand total cost for all products
    function updateGrandTotal() {
        let grandTotal = 0;
        const totalCostElements = document.querySelectorAll('input[name="grt_total_price[]"]');

        totalCostElements.forEach(function(element) {
            if (!isNaN(parseFloat(element.value))) {
                grandTotal += parseFloat(element.value);
            }
        });

        document.getElementById('total_cost').textContent = grandTotal.toFixed(2);
    }

    // Attach event listeners to input fields for real-time calculations
    const receivedQuantityFields = document.querySelectorAll('input[name="grt_returned_quantity[]"]');
    const unitPriceFields = document.querySelectorAll('input[name="grt_product_unitprice[]"]');

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
        const returnedQuantities = document.getElementsByName('grt_returned_quantity[]');
        const receivedQuantities = document.getElementsByName('grt_received_quantity[]');

        for (let i = 0; i < productBarcodes.length; i++) {
            // Check if the scanned barcode matches any of the product barcodes
            if (scannedBarcode === productBarcodes[i].value) {
                // If match found, deduct unserved quantity by 1 for the corresponding product
                const currentReturnedQuantity = parseFloat(returnedQuantities[i].value);
                const receivedQuantity = parseFloat(receivedQuantities[i].value);

                if (
                    !isNaN(currentReturnedQuantity) &&
                    currentReturnedQuantity >= 0 &&
                    currentReturnedQuantity < receivedQuantity
                ) {
                    // Increase returned quantity only if it's less than the received quantity
                    returnedQuantities[i].value = (currentReturnedQuantity + 1).toFixed();
                    calculateTotalPrice(returnedQuantities[i].parentNode.parentNode);
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