<style>
    .bold-label {
        font-weight: bold;
    }

    h4 {
        margin-left: 10px;
    }

    @media (max-width: 767px) {
        h1 {
            margin-left: 0;
        }
    }
</style>



<h4>Create Stock Requisition</h4>


<?php echo form_open_multipart('', array('onsubmit' => 'return confirm(\'Are you sure you want to add this stock requisition?\')')); ?>

<main class="content">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div class="container-fluid">
        <!--start select catergory-->
        <div class="row mb-3">
            <!--start purchase request no-->
            <div class="col-3">
                <label>Stock Requisition No</label>

                <input type="text" value="<?= $sr_no ?>" name="stock_requisition_no" readonly class="form-control form-control-sm">
            </div>

            <div class="col-3">
                <label>Date Created</label>
                <input type="text" value="<?= date('Y-m-d'); ?>" name="date_created" readonly class="form-control form-control-sm">
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

                            <th style="width: 50px;" id="table_style">
                                <button type="button" id="btn_sr" class="btn btn-sm btn-info"><i class=" fas fa-plus"></i></button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="row_content" id="row_product">
                        <tr>
                            <td>
                                <select class="form-control form-control-sm  product-select" data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Product" name="product[]" id="product" required>
                                    <option value="" selected hidden>Select Product</option>
                                    <?php foreach ($product as $pro) { ?>
                                        <option value="<?= $pro->product_name ?>" data-selling-price="" data-product-quantity="<?= $pro->product_quantity ?>"><?= $pro->product_name ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <input class="form-control form-control-sm" type="number" name="quantity[]" pattern="[0-9]+" id="quantity" min="0" max="" required>
                            </td>
                            <td>
                                <select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Unit" name="unit[]" id="unit" required>
                                    <option value="" selected hidden>Select Unit</option>
                                    <option>Pcs</option>
                                    <option>Tablet</option>
                                    <option>Capsule</option>
                                </select>
                            </td>
                            <td>
                                <input class="form-control form-control-sm product-selling-price" type="text" name="product_sellingprice[]" id="product_sellingprice" pattern="[0-9]+([\.|,][0-9]+)?">
                            </td>

                            <td>
                                <button class="btn btn-sm btn-danger remove-category"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
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
                <input type="submit" value="Create" name="btn_create_sr" id="submit_sr" class="btn btn-sm btn-primary">
                <a href="<?php echo site_url('main/stock_requisition'); ?>"><input type="button" value="Back" class=" btn btn-primary btn-sm  btn_save"></a>
            </div>
        </div>
        </form>

    </div>
</div>
</form>

<script>
    $(document).on('click', '#btn_sr', function() {

        var selectedProduct = $('select[name="product[]"]').last().val();
        var productAlreadyExists = false;
        $('select[name="product[]"]').not(':last').each(function() {
            if ($(this).val() == selectedProduct) {
                productAlreadyExists = true;
            }
        });
        if (productAlreadyExists) {
            toastr.error('This product has already been added.');
            return;
        }

        var newRow = '<tr>' +
            '<td> <select class="form-control form-control-sm  product-select" data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Product" name="product[]" id="product" required><option value="" selected hidden>Select Product</option><?php foreach ($product as $pro) { ?><option value="<?= $pro->product_name ?>" data-selling-price="<?= $pro->product_sellingprice ?>" data-product-quantity="<?= $pro->product_quantity ?>"><?= $pro->product_name ?></option><?php } ?></select></td>' +
            '<td> <input class="form-control form-control-sm" type="number" name="quantity[]" pattern="[0-9]+" id="quantity" min="0" max="" required></td>' +
            '<td><select class="form-control form-control-sm " data-live-search="true" data-style="btn-sm btn-outline-secondary" title="Select Unit" name="unit[]" required><option value="" selected hidden>Select Unit</option><option>Pcs</option><option>Tablet</option><option>Capsule</option></select></td>' +
            '<td><input class="form-control form-control-sm product-selling-price" type="text" name="product_sellingprice[]" id="product_sellingprice" pattern="[0-9]+([\.|,][0-9]+)?"></td>' +
            '<td><input class="form-control form-control-sm" type="number" name="total_price[]" id="total_price" readonly></td>' +
            '<td><button class="btn btn-sm btn-danger remove-category"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';

        $('#table_field tbody tr:last').after(newRow);
        $('.selectpicker').selectpicker('refresh');

        $('#table_field').on('change', '.product-select', function() {
            var quantityField = $(this).closest('tr').find('input[name="quantity[]"]');
            var maxQuantity = $(this).find(':selected').data('product-quantity');
            quantityField.attr('max', maxQuantity);
        });

        $('#table_field').on('input', 'input[name="quantity[]"]', function() {
            var maxQuantity = parseInt($(this).attr('max'));
            var quantityEntered = parseInt($(this).val());
            if (quantityEntered > maxQuantity) {
                var msg = "Only " + maxQuantity + " quantity of this product are available. Do you want to enter the remaining quantity?";
                if (confirm(msg)) {
                    $(this).val(maxQuantity);
                } else {
                    $(this).val(1);
                }
            }
        });


        // Get references to the new product select and quantity input fields
        var newProductSelect = $('#table_field tbody tr:last .product-select');
        var newQuantityInput = $('#table_field tbody tr:last input[name="quantity[]"]');

        // Add event listener to the new product select dropdown menu
        newProductSelect.on('change', function() {
            // Clear the value of the new quantity input field
            newQuantityInput.val('');
        });


        // Recalculate totals for the new row
        var quantity = $('#quantity').val();
        var price = $('#product_sellingprice').val();
        var totalPrice = parseFloat(quantity) * parseFloat(price);
        $(this).closest('tr').find('#total_price').val(totalPrice.toFixed(2));

        // Display product quantity on hover for the new row
        $('.product-select').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            var selectedOption = $(this).find('option').eq(clickedIndex);
            var productQuantity = selectedOption.data('product-quantity');
            $(this).attr('data-original-title', 'Available Quantity: ' + productQuantity).tooltip('dispose').tooltip();
        });

        $(document).ready(function() {
            $(".product-select").on('change', function() {
                var availableQuantity = $(this).find(':selected').data('product-quantity');
                $(this).closest('tr').find('#quantity').attr('max', availableQuantity);
            });
        });
    })

    $(document).on('input', '#quantity, #product_sellingprice', function() {
        var quantity = $(this).closest('tr').find('#quantity').val();
        var price = $(this).closest('tr').find('#product_sellingprice').val();
        var totalPrice = parseFloat(quantity) * parseFloat(price);
        $(this).closest('tr').find('#total_price').val(totalPrice.toFixed(2));
    });

    // remove row
    $('#table_field').on('click', '.remove-category', function(e) {
        e.preventDefault();

        if ($('#table_field tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            toastr.error('Cannot delete');
        }
    });

    // update selling price when product is selected
    $(document).on('change', '.product-select', function() {
        var sellingPrice = $(this).find('option:selected').data('selling-price');
        $(this).closest('tr').find('.product-selling-price').val(sellingPrice);
    });
</script>
<script>
    $(document).ready(function() {
        $('#submit_sr').click(function(event) {
            var pro = $('#sr_product_name');
            var bra = $('#sr_branch');
            var qua = $('#sr_quantity');
            var uni = $('#sr_unit');
            var pri = $('#sr_price');
            var selectpicker = $('.selectpicker');
            var lastRow = $('#table_field tbody tr:last'); // get the last row added to the table
            var lastRowFields = lastRow.find('input[type="text"], input[type="number"], select'); // get all fields in the last row
            var productNames = [];

            // Check if any of the fields are empty or if the selectpicker value is not selected
            if (pro.val() === "" || bra.val() === "" || qua.val() === "" || uni.val() === "" || pri.val() === "" || selectpicker.find(':selected').val() === "") {
                toastr.error('Please complete the fields first');
                event.preventDefault(); // Prevent form submission
            }
            // Check if the last row added to the table contains any empty fields
            else if (lastRowFields.filter(function() {
                    return $(this).val() == '';
                }).length > 0) {
                toastr.error('Please complete the fields in the last row');
                event.preventDefault(); // Prevent form submission
            } else {
                $('#table_field tbody tr').each(function() {
                    var productName = $(this).find('select[name="product[]"]').val();
                    if (productName !== "" && productNames.indexOf(productName) > -1) {
                        toastr.error('Product "' + productName + '" already exists in the table');
                        event.preventDefault(); // Prevent form submission
                        return false; // exit each loop
                    }
                    productNames.push(productName);
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#btn_clear').click(function(e) {
            e.preventDefault();
            $('.clear').val('');
        });
    });
</script>
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

        $('#total_cost').html(totalCost.toFixed(2));
    }
</script>
<script>
    $(document).ready(function() {
        $('.product-select').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            var selectedOption = $(this).find('option').eq(clickedIndex);
            var productQuantity = selectedOption.data('product-quantity');
            $(this).attr('data-original-title', 'Available Quantity: ' + productQuantity).tooltip('dispose').tooltip();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(".product-select").on('change', function() {
            var availableQuantity = $(this).find(':selected').data('product-quantity');
            $(this).closest('tr').find('#quantity').attr('max', availableQuantity);
        });
    });
</script>
<script>
    var productSelect = document.getElementById('product');
    var quantityInput = document.getElementById('quantity');

    productSelect.addEventListener('change', function() {
        quantityInput.value = '';
    });
</script>
<script>
    function checkQuantity(input) {
        var remainingQuantity = input.parentNode.previousElementSibling.querySelector('option:checked').dataset.productQuantity;
        var enteredQuantity = parseInt(input.value);

        if (enteredQuantity > remainingQuantity) {
            var confirmResult = confirm('Only ' + remainingQuantity + ' quantity of this product is available. Do you want to enter the remaining quantity?');

            if (confirmResult) {
                input.value = remainingQuantity;
            } else {
                input.value = 1;
            }
        }
    }
</script>