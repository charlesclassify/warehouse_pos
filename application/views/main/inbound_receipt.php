<style>
    @import url('https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Source Sans Pro', sans-serif;
    }

    .container {
        display: block;
        width: 280px;
        /* Adjusted width for receipt-like appearance */
        background: #fff;
        padding: 10px;
        margin: 0 auto;
        border: 1px solid #000;
        margin-top: 50px;
    }

    .receipt_header {
        text-align: center;
        margin-bottom: 10px;
    }

    .receipt_header h1 {
        font-size: 16px;
        margin-bottom: 3px;
        color: #000;
        text-transform: uppercase;
    }

    .receipt_header h3 {
        font-size: 10px;
        color: #727070;
        font-weight: 300;
        margin-bottom: 5px;
    }

    h2 {
        font-size: 10px;
        color: #727070;
        font-weight: 300;
        margin-bottom: 0;
    }

    .receipt_header h2 {
        font-size: 10px;
        color: #727070;
        font-weight: 300;
        margin-bottom: 0;

    }

    .receipt_body {
        margin-top: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: none;
        /* Remove border for a cleaner look */
        padding: 3px 0;
        /* Adjusted padding for a more compact layout */
        font-size: 10px;
        /* Reduced font size for better fit */
    }

    .items th,
    .items td {
        padding: 3px 0;
        /* Adjusted padding for a more compact layout */
    }

    .customer_cont {
        font-size: 10px;
        /* Reduced font size for better fit */
        margin-top: 5px;
    }

    .recepit_cont,
    .change_cont {
        font-size: 10px;
        /* Reduced font size for better fit */
        margin-top: 5px;
    }

    h3 {
        color: #000;
        border-top: 1px dashed #000;
        padding-top: 5px;
        margin-top: 8px;
        text-align: center;
        text-transform: uppercase;
        font-size: 12px;
        /* Reduced font size for better fit */
    }

    .print-button {
        color: #000;
        display: none;
        text-align: center;
        /* Hide print button in print view */
    }

    /* Hide sidebar and navbar in print view */
    @media print {

        .navbar,
        #layoutSidenav_nav {
            display: none;
        }
    }

    @media print {
        .container {
            border: none;
            /* Remove border in print view */
            width: 100%;
            /* Use full width in print view */
            max-width: none;
            /* Remove max-width in print view */
            padding: 5px;
            /* Adjusted padding for print view */
            margin: 0;
        }

        .receipt_header h1 {
            font-size: 14px;
            /* Adjusted font size for print view */
        }

        .receipt_header h3,
        .receipt_header h2 {
            font-size: 12px;
            /* Adjusted font size for print view */
        }

        .items th,
        .items td {
            font-size: 12px;
            /* Adjusted font size for print view */
        }

        .customer_cont,
        .recepit_cont,
        .change_cont {
            font-size: 12px;
            /* Adjusted font size for print view */
        }

        h3 {
            font-size: 12px;
            /* Adjusted font size for print view */
        }
    }

    .total {
        text-align: right;
    }

    .itemTableBody {
        font-size: 12px;
    }

    .comment {
        font-size: 12px;
    }

    .total {
        font-size: 12px;
    }
</style>

<div class="container">
    <div class="receipt_header">
        <h1>GENSAN FEEDMILL, INC.</h1>
        <h2>WAREHOUSE</h2>
        <h2>Prepared By: 
            <?php
                if (!empty($receipt_details['receiving_no_data'])) {
                    $receipt_no = $receipt_details['receiving_no_data'][0];
                    echo ucfirst($receipt_no->username);
                } else {
                    echo "";
                }
            ?> </h2>
        <h2><strong>INBOUND RECEIPT</strong></h2>
        <h3></h3>
    </div>
    <div class="receipt_body">
      
            <table>
                <thead>
                    <th>SAP CODE</th>
                    <th>ITEM NAME</th>
                    <th>UOM</th>
                    <th>QTY</th>

                </thead>
                <?php foreach ($receipt_details['receiving_data'] as $receipt) { ?>
                    <tbody id="itemTableBody">
                        <tr>
                            <td><?= $receipt->product_code ?></td>
                            <td><?= $receipt->product_name ?></td>
                            <td><?= $receipt->product_uom ?></td>
                            <td>x<?= $receipt->inbound_quantity ?></td>

                        </tr>
                    </tbody>
                <?php } ?>
            </table>

    </div>
    <div class="total" style="border-top: 1px dashed #000; text-align:right">
        Total: 0.00
    </div>
    
    
    <div class="supplier">
        <?php
        // Check if receiving_no data exists
        if (!empty($receipt_details['receiving_no_data'])) {
            // Assuming there's only one entry in receiving_no_data array, you can directly access it
            $receipt_no = $receipt_details['receiving_no_data'][0];
        ?>
            <h2>Supplier: <?= $receipt_no->supplier ?></h2>
        <?php } else {
            // No receiving_no data found
            echo "<p>No supplier entered.</p>";
        }
        ?>
    </div>

    <div class="comment">
        <?php
        // Check if receiving_no data exists
        if (!empty($receipt_details['receiving_no_data'])) {
            // Assuming there's only one entry in receiving_no_data array, you can directly access it
            $receipt_no = $receipt_details['receiving_no_data'][0];
        ?>
            <h2>Comments: <?= $receipt_no->comments ?></h2>
        <?php } else {
            // No receiving_no data found
            echo "<p>No comment entered.</p>";
        }
        ?>
    </div>

    <div class="date">
        <?php
        // Check if receiving_no data exists
        if (!empty($receipt_details['receiving_no_data'])) {
            // Assuming there's only one entry in receiving_no_data array, you can directly access it
            $receipt_no = $receipt_details['receiving_no_data'][0];
        ?>
            <h3 style="text-align: center; border-top: 1px dashed #000;">
                Reference No.: <?= $receipt_no->receiving_no ?> <br>
                Date: <?= (new DateTime($receipt_no->date_created))->format('Y-m-d H:i:s') ?>
            </h3>
        <?php } else {
            // No receiving_no data found
            echo "<p>No receiving_no data found.</p>";
        }
        ?>
    </div>

</div>
<script>
    // Handle printing
    var printButton = document.getElementById('printButton');
    if (printButton) {
        printButton.addEventListener('click', function() {
            printReceipt();
        });
    }

    function printReceipt() {
        // Hide the print button before printing
        var printButton = document.getElementById('printButton');
        if (printButton) {
            printButton.style.display = 'none';
        }

        // Trigger the print dialog
        window.print();

        // Revert the display property after a short delay
        setTimeout(function() {
            if (printButton) {
                printButton.style.display = 'block';
            }
        }, 1000); // Adjust the delay as needed
    }
    // Automatically trigger the print dialog
    printReceipt();
</script>