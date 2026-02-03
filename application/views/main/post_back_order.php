<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Post Back Order</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('main/dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Goods Received</li>
        </ol>
    </div><!-- /.col -->
</div><!-- /.row -->

<?php echo form_open_multipart('main/post_back_order_submit', array('onsubmit' => 'return confirm(\'Are you sure you want to post this back order?\')')); ?>
<main class="content">
    <div class="container-fluid">
        <!--start select catergory-->
        <div class="row mb-3">
            <!--start purchase request no-->
            <input type="hidden" value="<?= $bo_no ?>" name="back_order_no" readonly class="form-control form-control-sm">

            <div class="col-3">
                <label>Purchase Order No</label>
                <input type="text" value="<?= $code->purchase_order_no ?>" readonly class="form-control form-control-sm">
                <input type="hidden" name="po_id" value="<?= $select->purchase_order_id ?>">
            </div>
            <div class="col-3">
                <label>Suplier</label>
                <input type="text" name="" id="" class="form-control form-control-sm" value="<?= $select->supplier_name ?>" readonly>
                <input type="hidden" value="<?= $select->supplier_id ?>" name="supplier_id">
            </div>
            <div class="col-3">
                <label>Date Received</label>
                <input type="text" value="<?= date('m-d-Y h:i A'); ?>" name="date_received" readonly class="form-control form-control-sm">
            </div>
            <!--start purchase request no-->
        </div>
        <!--Start Table-->

        <div class="card border-success mb-3" style="max-width:100%; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;" id="card">
            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>

                        <tr>
                            <th style="width: 215px;" id="table_style">Product Name</th>
                            <th id="table_style">Unit</th>
                            <th id="table_style">Back Order Quantity</th>
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
                                <input type="hidden" name="gr_code[]" value="<?= $row->purchase_order_id ?> ">
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_product_name ?>" name="product[]" readonly>

                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_unit ?>" name="unit[]" readonly>
                                    <?php foreach ($barcode as $bar) {
                                        if ($row->gr_product_name === $bar->product_name && $row->gr_unit === $bar->unit) { ?>
                                            <input type="hidden" name="product_barcode" value="<?= $bar->barcode; ?>">
                                    <?php }
                                    } ?>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_unserved_quantity ?>" name="quantity[]" min="0" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_unserved_quantity ?>" type="number" name="unserved_quantity[]" id="receive_quantity" min="0" max="<?= $row->gr_total_quantity - $row->gr_received_quantity ?>" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" value="<?= $row->gr_product_unitprice ?>" id="price" name="product_unitprice[]">
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="total_price[]" id="total_price_display" readonly>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="date" name="expiry_date[]">
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

            <div class="card-footer bg-transparent border-success text-end">
                <?php if ($row->status == "Received") { ?>


                <?php
                } else {
                ?>
                    <input type="submit" value="Post" name="btn_post_bo" id="submit_pr" class="btn btn-sm btn-primary">

                <?php
                } ?>
                <a href="<?php echo site_url('main/back_order'); ?>"><input type="button" value="Back" class=" btn btn-primary btn-sm  btn_save"></a>
            </div>
            </ddiv>
            </form>
        </div>
</main>
<script>
    // Function to update the grand total cost for all products
    function calculateTotalPrice(row) {
        const unservedQuantity = parseFloat(row.querySelector('input[name="unserved_quantity[]"]').value);
        const orderedQuantity = parseFloat(row.querySelector('input[name="quantity[]"]').value);
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