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

    .select2-container .select2-selection {
        height: 34px;

    }
</style>

<div class="container">

    <h4>Receiving</h4>
    <form action="" method="post" onsubmit="return confirm('Are you sure you want to receive this batch?')">
        <div class="row mb-3">
            <div class="col-12 col-sm-4">
                <label for="receiving_no" class="form-label text-black">Receiving No.</label>
                <input type="text" value="<?= $rn ?>" name="receiving_no" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-4">
                <label for="date_created" class="form-label text-black">Date Added</label>
                <input type="text" id="date_created" name="date_created" value="<?= date('m-d-Y h:i A'); ?>" readonly class="form-control form-control-sm">
            </div>
            <div class="col-12 col-sm-4">
                <label class="form-label text-black">Supplier</label>
                <select class="form-control form-control-sm supplier-select" data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Supplier" name="supplier" id="supplier" required>
                    <option value="" selected hidden>Select Supplier</option>
                    <?php foreach ($suppliers as $supp) { ?>
                        <?php if ($supp->vendor_code == null) { ?>
                            <option value="<?= $supp->company_name ?>">
                                <?= $supp->company_name ?>
                            </option>
                        <?php } else { ?>
                            <option value="<?= $supp->company_name ?>">
                                <?= $supp->vendor_code ?> - <?= $supp->company_name ?>
                            </option>
                        <?php } ?>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered text-left" id="table_field">
                    <thead>
                        <tr>
                            <th style="width: 65%;">Product Name</th>
                            <th style="width: 25%;">Quantity</th>
                            <th style="width: 10%;">
                                <button type="button" class="btn btn-info" id="btn_add"><i class="fas fa-plus"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="row_content" id="row_product">
                        <tr>
                            <td>
                                <select class="form-control form-control-sm product-select" style="width:100%" data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Product" name="product_name[]" id="product_name" required>
                                    <option value="" selected hidden>Select Product</option>
                                    <?php foreach ($product as $pro) { ?>
                                        <option value="<?= $pro->product_name ?>" data-uom="<?= $pro->product_uom ?>" data-code="<?= $pro->product_code ?>" data-category="<?= $pro->product_category ?>" data-id="<?= $pro->product_id ?>">
                                            <?= $pro->product_code ?> - <?= $pro->product_name ?>
                                        </option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" name="product_uom[]" class="product-uom">
                                <input type="hidden" name="product_code[]" class="product-code">
                                <input type="hidden" name="product_category[]" class="product-category">
                                <input type="hidden" name="product_id[]" class="product-id">
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="number" name="inbound_quantity[]" id="inbound_quantity" required min="0.01" step="0.01">
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger remove-category" onclick="removeProductRow(this)"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <input type="hidden" name="username" class="form-control form-control text" value="<?= ucfirst($this->session->userdata('UserLoginSession')['username']) ?>" required>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Grand Total Cost:</strong></td>
                            <td id="total_cost" class="total-cost">₱0</td>
                        </tr>
                    </tfoot>
                </table>
                <div class="form-group col-12 d-inline-block">
                    <label for="comments" class="bold-label">Comments</label>
                    <input type="text" id="comments" name="comments" class="form-control form-control text" placeholder="Enter Comments" required>
                </div>
            </div>
            <div class="card-footer bg-transparent text-end">
                <input type="submit" value="Create" name="btn_batch_receiving" id="submit_batch_receiving" class="btn btn-success btn-sm">
                <a href="<?php echo site_url('main/product'); ?>"><input type="button" value="Back" class="btn btn-outline-secondary btn-sm btn_save"></a>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        console.log('Initializing Select2 for supplier-select');
        $('.supplier-select').select2({
            placeholder: 'Select a Supplier'
        }).on('select2:open', function() {
            console.log('Select2 opened for supplier-select');
        });

        console.log('Initializing Select2 for product-select');
        $('.product-select').select2({
            placeholder: 'Select a Product'
        }).on('select2:open', function() {
            console.log('Select2 opened for product-select');
        });

        $(document).on('click', '#btn_add', function() {
            var selectedProducts = $('select[name="product_name[]"]').map(function() {
                return $(this).val();
            }).get();

            // Assuming $product is passed from your PHP code
            var products = <?= json_encode($product) ?>;

            var newRow = `<tr>
                <td>
                    <select class="form-control form-control-sm product-select" style="width:100%" data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Product" name="product_name[]" required>
                        <option value="" selected hidden>Select Product</option>
                        ${products.map(pro => 
                            `<option value="${pro.product_name}" 
                                     data-uom="${pro.product_uom}" 
                                     data-code="${pro.product_code}" 
                                     data-category="${pro.product_category}"
                                     data-id="${pro.product_id}">
                                ${pro.product_code} - ${pro.product_name}
                            </option>`
                        ).join('')}
                    </select>
                    <input type="hidden" name="product_uom[]" class="product-uom">
                    <input type="hidden" name="product_code[]" class="product-code">
                    <input type="hidden" name="product_category[]" class="product-category">
                    <input type="hidden" name="product_id[]" class="product-id">
                </td>
                <td><input class="form-control form-control-sm po_quantity" type="number" name="inbound_quantity[]" required min="0" min="0.01" step="0.01"></td>
                <td><button class="btn btn-danger remove-category" onclick="removeProductRow(this)"><i class="fas fa-trash"></i></button></td>
              </tr>`;

            $('#table_field tbody tr:last').after(newRow);

            var newSelectField = $('#table_field tbody tr:last').find('.product-select');
            newSelectField.select2({
                placeholder: 'Select a Product'
            });

            var options = newSelectField.find('option');
            options.each(function() {
                if ($.inArray($(this).val(), selectedProducts) !== -1) {
                    $(this).prop('disabled', true);
                }
            });

            var newQuantityField = $('#table_field tbody tr:last').find('.po_quantity');
            newSelectField.on('change', function() {
                newQuantityField.val('');
            });
        });

        $(document).on('change', '.product-select', function() {
            var selectedOption = $(this).find('option:selected');
            var uom = selectedOption.data('uom');
            var code = selectedOption.data('code');
            var category = selectedOption.data('category');
            var productId = selectedOption.data('id'); // Added Line

            // Find the hidden input fields within the same row
            var row = $(this).closest('tr');
            row.find('.product-uom').val(uom);
            row.find('.product-code').val(code);
            row.find('.product-category').val(category);
            row.find('.product-id').val(productId); // Added Line
        });
    });

    function removeProductRow(button) {
        // Get the parent row (the <tr> element) of the clicked button
        var row = button.closest('tr');

        // Check if there's only one row left, don't remove it
        var rowCount = document.querySelectorAll('.row_content tr').length;
        if (rowCount > 1) {
            // Remove the row from the table
            if (row) {
                row.remove();
            }
        } else {
            alert("You can't delete the last row.");
        }
    }
</script>