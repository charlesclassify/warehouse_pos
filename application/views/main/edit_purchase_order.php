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

<div class="container">
    <h4 class="text-white">Edit Purchase Request</h4>
    <form action="" method="post" onsubmit="return confirm('Are you sure you want to add this purchase order?')">
        <div class="row mb-3">
            <div class="col-12 col-sm-3">
                <label for="purchase_order_no" class="form-label text-white">Purchase Request No</label>
                <input type="text" value="<?= $code->purchase_order_no ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">
                <label for="date_created" class="form-label text-white">Date Created</label>
                <input type="text" value="<?= $code->date_created ?>" name="date_created" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-3">

                <label for="supplier_id" class="form-label">Supplier</label>
                <select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" name="supplier_name" id="supplier_name" required>

                    <option class="text-info invisible" value="<?= $select->supplier_id ?>"><?= $select->supplier_name ?> - <?= $select->company_name ?></option>
                    <?php foreach ($supplier as $supp) { ?>
                        <option value="<?= $supp->supplier_id ?>"><?= $supp->supplier_name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-12 col-sm-3">

                <label for="payment_method" class="form-label">Payment Method</label>
                <select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" name="payment_method" id="payment_method" required>

                    <option class="text-info invisible" value="<?= $code->payment_method ?>"><?= $code->payment_method ?></option>
                    <option>Cash</option>
                    <option>Check</option>
                    <option>Credit Card</option>
                </select>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered" id="table_field">
                    <thead>
                        <tr>
                            <th style="width: 250;" id="table_style">Product Name</th>
                            <th style="width: 160px;" id="table_style">Quantity</th>
                            <th style="width: 160px;" id="table_style">Unit of Measure</th>
                            <th style="width: 160px;" id="table_style">Price</th>
                            <th style="width: 160px;" id="table_style">Total Cost</th>

                            <th style="width: 50px;" id="table_style">
                                <button type="button" class="btn btn-sm btn-info" id="btn_po"><i class="fas fa-plus">
                            </th>
                        </tr>
                    </thead>
                    <?php foreach ($view as $row) { ?>
                        <input type="hidden" name="po_code[]" value="<?= $row->purchase_order_id ?> ">
                        <tbody class="row_content" id="row_product">
                            <tr>
                                <td>

                                    <select class="form-control form-control-sm  product-select" data-live-search="true" data-style="btn-sm btn-outline-secondary" name="product_name[]" id="product_name" required>
                                        <option class="text-info invisible" value="<?= $row->product_name ?>"><?= $row->product_name ?></option>

                                        <?php foreach ($product as $pro) { ?>
                                            <option value="<?= $pro->product_name ?>"> <?= $pro->product_name ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="po_product_quantity[]" id="po_product_quantity" value="<?= $row->po_product_quantity ?>" pattern="[0-9]+" required>
                                </td>
                                <td>

                                    <select class="form-control form-control-sm  unit-select" data-live-search="true" data-style="btn-sm btn-outline-secondary" name="product_unit[]" id="unit" required>

                                        <option class="text-info invisible" value="<?= $row->product_unit ?>"><?= $row->product_unit ?></option>
                                        <?php foreach ($barcode as $bar) {
                                            if ($row->product_name === $bar->product_name) { ?>
                                                <option value="<?= $row->product_unit ?>"> <?= $bar->unit ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>

                                    <input class="form-control form-control-sm" type="number" name="product_unitprice[]" id="product_unitprice" value="<?= $row->product_unitprice ?>" pattern="[0-9]+" required>

                                </td>
                                <td>
                                    <input class="form-control form-control-sm" type="number" name="total_price[]" id="total_price_display" readonly>
                                </td>
                            </tr>

                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                                <td id="Grand total_cost" class="grand_total_cost">₱0</td>
                            </tr>
                        </tfoot>
                </table>
            </div>
            <div class="card-footer bg-transparent text-end">
                <input type="submit" value="Update" name="update_po" id="submit_po" class="btn btn-sm btn-primary">
                <a href="<?php echo site_url('main/purchase_order'); ?>"><input type="button" value="Back" class=" btn btn-primary btn-sm  btn_save"></a>
            </div>
            <input type="hidden" name="purchase_id" value="<?php echo $select->purchase_order_no; ?>">
        </div>
    </form>
</div>
<script>
    // Function to calculate the total price for a specific row
    function calculateTotalPrice(row) {
        // Get the quantity and price input fields for the row
        var quantityField = row.querySelector("input[name='po_product_quantity[]']");
        var priceField = row.querySelector("input[name='product_unitprice[]']");
        var totalField = row.querySelector("input[name='total_price[]']");

        // Parse the quantity and price values to numbers
        var quantity = parseFloat(quantityField.value);
        var price = parseFloat(priceField.value);

        // Calculate the total price for this row
        var total = quantity * price;

        // Update the total price field for this row
        totalField.value = total.toFixed(2); // Ensure two decimal places

        return total;
    }

    // Function to calculate the grand total price for all rows
    function calculateGrandTotal() {
        var rows = document.querySelectorAll(".row_content");
        var grandTotal = 0;

        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            grandTotal += calculateTotalPrice(row);
        }

        // Update the grand total display element
        var grandTotalElement = document.getElementById("Grand total_cost");
        grandTotalElement.textContent = "₱" + grandTotal.toFixed(2); // Ensure two decimal places
    }

    const quantityFields = document.querySelectorAll('input[name="po_product_quantity[]"]');
    const priceFields = document.querySelectorAll('input[name="product_unitprice[]"]');

    quantityFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateTotalPrice(row);
            calculateGrandTotal();
        });
    });

    priceFields.forEach(function(element) {
        element.addEventListener('input', function() {
            const row = element.closest('tr');
            calculateTotalPrice(row);
            calculateGrandTotal();
        });
    });

    // Function to add a new empty row
    function addEmptyRow() {
        var table = document.getElementById("table_field");
        var newRow = document.createElement("tr");
        newRow.className = "row_content"; // Assign the 'row_content' class to the new row

        newRow.innerHTML = `<td>
            <select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" name="product_name[]" required>
                <option value=""></option>
                <?php foreach ($product as $pro) { ?>
                    <option value="<?= $pro->product_name ?>" data-product-price="<?= $pro->product_price ?>"><?= $pro->product_name ?></option>
                <?php } ?>
            </select>
        </td>
        <td><input class="form-control form-control-sm" type="number" name="po_product_quantity[]" value="" pattern="[0-9]+" required></td>
        <td>
            <select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" name="product_unit[]" required>

                <option value=" ></option>
              <?php foreach ($barcode as $bar) {
                    if ($row->product_name === $bar->product_name) { ?>
                                                <option value="<?= $row->product_unit ?>"> <?= $bar->unit ?></option>
                                            <?php } ?>
                                        <?php } ?>

            </select>
        </td>
        <td><input class="form-control form-control-sm" type="number" name="product_unitprice[]" value="" required></td>
        <td><input class="form-control form-control-sm" type="number" name="total_price[]" readonly></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)"><i class="fas fa-trash"></i></button></td>`;

        // Append the new row to the table
        $('#table_field tbody tr:last').after(newRow);

        // Add event listeners to the new quantity and price inputs
        newRow.querySelector("input[name='po_product_quantity[]']").addEventListener("input", function() {
            calculateTotalPrice(newRow);
            calculateGrandTotal();
        });

        newRow.querySelector("input[name='product_unitprice[]']").addEventListener("input", function() {
            calculateTotalPrice(newRow);
            calculateGrandTotal();
        });

        // Add a change event listener to the product select field in the new row
        newRow.querySelector("select[name='product_name[]']").addEventListener("change", function() {
            updatePriceField(this);
        });
    }

    // Function to delete a row
    function deleteRow(button) {
        var row = button.closest("tr");
        row.remove();
        calculateGrandTotal();
    }

    // Add a click event listener to the "Add Row" button
    var addRowButton = document.getElementById("btn_po");
    addRowButton.addEventListener("click", addEmptyRow);

    // Initial calculation when the page loads
    calculateGrandTotal();

    // Function to update the price field when a product is selected
    function updatePriceField(selectedProduct) {
        const productPrice = $(selectedProduct).find(':selected').data('product-price');
        const priceField = $(selectedProduct).closest('tr').find('input[name="product_unitprice[]"]');

        // Set the price field's value to the selected product's price
        priceField.val(productPrice);

        // Calculate the total price for the row after updating the price
        calculateTotalPrice(priceField.closest('tr'));
        calculateGrandTotal();
    }

    document.addEventListener('change', function(event) {
        if (event.target.matches('select[name="product_name[]"]')) {
            var selectedProduct = event.target.value;
            var unitField = event.target.closest('tr').querySelector('select[name="product_unit[]"]');
            var units = <?php echo json_encode($barcode); ?>; // Assuming $barcode contains the units data

            // Clear previous options
            unitField.innerHTML = '<option value="" selected hidden>Select Unit</option>';

            // Filter and populate units based on selected product
            units.forEach(function(unit) {
                if (unit.product_name === selectedProduct) {
                    var option = document.createElement('option');
                    option.value = unit.unit;
                    option.textContent = unit.unit;
                    unitField.appendChild(option);
                }
            });
        }
    });
</script>