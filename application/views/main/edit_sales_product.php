<style>
    .bold-label {
        font-weight: bold;
    }

    @media (max-width: 767px) {
        h1 {
            margin-left: 0;
        }
    }
</style>

<div class="container mt-3">
    <h4>Add Product Sales</h4>
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <?= form_open_multipart('main/edit_sales_product_submit/' . $product->product_id, array('onsubmit' => 'return confirm(\'Are you sure you want to update this product?\')')); ?>

                <div class="section">
                    <h5>Calculate Suggested Retail Price</h5>

                    <div class="form-group col-md-3 d-inline-block">
                        <label for="net_product_cost" class="bold-label">Net Product Cost</label>
                        <input type="number" id="net_product_cost" name="net_product_cost" value="" class="form-control">
                    </div>

                    <div class="form-group col-md-3 d-inline-block">
                        <label for="sales_margin" class="bold-label">Margin</label>
                        <input type="number" id="sales_margin" name="sales_margin" value="" class="form-control">
                    </div>

                    <div class="form-group col-md-3 d-inline-block">
                        <label class="bold-label">VAT</label>
                        <select class="form-control" name="product_vat" required>
                            <option class="text-info invisible"></option>
                            <option>12%</option>
                            <option>Non-VAT</option>
                        </select>
                    </div>

                    <div class="form-group col-md-3 d-inline-block">
                        <label for="sales_srp" class="bold-label">SRP</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">&#8369;</span>
                            </div>
                            <input type="number" id="sales_srp" name="sales_srp" value="" readonly class="form-control">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="product_id" value="<?= $product->product_id; ?>">

                <div class="card-footer bg-transparent text-end">
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Submit</button>
                        <a class="btn btn-secondary btn-sm" href="<?= base_url('main/sales') ?>"><i class="fas fa-reply"></i> Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get references to input fields
        var netProductCostInput = document.getElementById("net_product_cost");
        var salesMarginInput = document.getElementById("sales_margin");
        var vatSelect = document.querySelector("select[name='product_vat']");
        var srpInput = document.getElementById("sales_srp");

        // Add event listeners to trigger calculation on input change
        netProductCostInput.addEventListener("input", calculateSrp);
        salesMarginInput.addEventListener("input", calculateSrp);
        vatSelect.addEventListener("change", calculateSrp);

        // Initial calculation
        calculateSrp();

        function calculateSrp() {
            // Get input values
            var netProductCost = parseFloat(netProductCostInput.value) || 0;
            var salesMargin = parseFloat(salesMarginInput.value) || 0;
            var vatPercentage = vatSelect.value === "12%" ? 0.12 : 0;

            // Calculate SRP
            var srp = netProductCost + netProductCost * salesMargin / 100;

            // Apply VAT if applicable
            srp += srp * vatPercentage;

            // Update SRP input field
            srpInput.value = srp.toFixed(2);
        }
    });
</script>