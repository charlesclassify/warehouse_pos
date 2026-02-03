<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order</title>
    <style>
        /* Table styles */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Other styles */
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .logo img {
            max-width: 50%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .row {
            margin-bottom: 20px;
            overflow: hidden;
        }

        .col-6 {
            float: left;
            width: 50%;
        }

        .title {
            font-size: 18px;
            margin-bottom: 5px;
            display: block;
        }

        .details {
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        hr {
            margin-top: 20px;
        }

        .signature {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="<?php echo base_url('assets/images/company.png'); ?>" alt="Company Logo">
        </div>
        <div class="row">
            <div class="col-6">
                <div class="details">
                    <span class="title">Purchase Order No:</span> <?= $code->purchase_order_no ?><br>
                    <span class="title">Date Created:</span> <?= $code->date_created ?><br>
                </div>
            </div>
            <div class="col-6">
                <div class="details">
                    <span class="title">Supplier:</span> <?= $select->supplier_name ?><br>
                    <span class="title">Address:</span> <?= ucwords($select->supplier_street) . ', &nbspBrgy. ' . ucwords($select->supplier_barangay) . ',&nbsp' . ucwords($select->supplier_city) . ' City, ' . ucwords($select->supplier_province) ?><br>
                    <span class="title">Contact No.:</span> +63<?= $select->supplier_contact ?><br>
                </div>
            </div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($view as $row) { ?>
                    <tr>
                        <td><?= $row->product_name ?></td>
                        <td><?= $row->po_product_quantity ?></td>
                        <td><?= $row->product_unit ?></td>
                        <td>₱<?= $row->product_unitprice ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total Cost:</strong></td>
                    <td id="total_cost" class="total_cost">₱<?= $row->total_cost ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="footer">
            <p>Date Generated: <span><?= date('Y-m-d h:i:s A'); ?></span></p>
            <p>Prepared By: <span><?= ucfirst($this->session->userdata('UserLoginSession')['username']) ?></span></p>
        </div>
        <hr>
        <p class="signature">Authorized Signature: __________________________</p>
    </div>
    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>