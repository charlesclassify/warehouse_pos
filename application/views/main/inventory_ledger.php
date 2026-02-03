<h4>Inventory Ledger</h4>

<div class="container">
    <form method="POST" action="" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="date_from" class="form-label text-black">Date From:</label>
            <input type="date" id="date_from" name="date_from" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="date_to" class="form-label text-black">Date To:</label>
            <input type="date" id="date_to" name="date_to" class="form-control" required>
        </div>
        <div class="col-md-5">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-success">Search</button>
        </div>
    </form>

    <?php if (isset($_POST['date_from']) && isset($_POST['date_to'])) : ?>
        <?php
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $ledger = $this->inventory_ledger_model->get_ledger_by_date_range($date_from, $date_to);
        ?>

        <?php if (!empty($ledger)) : ?>
            <hr>
            <div class="card">
                <!--img src="<?= base_url('assets/images/GFI.jpg'); ?>" alt="Company Logo" style="display: block; margin: 0 auto; width:400px;height:80px;"-->
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="ledger-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th>Date Posted</th>
                                    <th>Reference No.</th>
                                    <th>SAP Code</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Remarks</th>
                                    <th>Activity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ledger as $row) : ?>
                                    <tr class="text-center">
                                        <td><?= $row->date_posted ?></td>
                                        <td><?= $row->reference_no ?></td>
                                        <td><?= $row->product_code ?></td>
                                        <td><?= $row->product_name ?></td>
                                        <td><?= $row->quantity ?></td>
                                        <td><?= $row->unit ?></td>
                                        <td><?= $row->remarks ?></td>
                                        <td>
                                            <?php
                                            $activityBadgeClass = '';
                                            switch ($row->activity) {
                                                case 'Purchase':
                                                    $activityBadgeClass = 'badge bg-primary';
                                                    break;
                                                case 'Inbound':
                                                    $activityBadgeClass = 'badge bg-success';
                                                    break;
                                                case 'Outbound':
                                                    $activityBadgeClass = 'badge bg-danger';
                                                    break;
                                                case 'Sold':
                                                    $activityBadgeClass = 'badge bg-info';
                                                    break;
                                                case 'Sales Returned':
                                                    $activityBadgeClass = 'badge bg-danger';
                                                    break;
                                                default:
                                                    $activityBadgeClass = 'badge bg-secondary';
                                                    break;
                                            }
                                            ?>
                                            <span class="<?= $activityBadgeClass ?>"><?= ucfirst($row->activity) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="text-end">
                            <button class="btn btn-success" onclick="printData()">Print Data</button>
                        </div>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <p>No data found for the selected date range.</p>

        <?php endif; ?>
    <?php endif; ?>
</div>
<script>
    function printData() {
        // Get all table data, including data from other pages
        var table = $('#ledger-table').DataTable();
        table.page.len(-1).draw();

        // Get the image and title
        var image = '<h1 style="text-align: center;">Gensan FeedMill, Inc.</h1>';
        var title = '<h3 style="text-align: center;">MOVEMENT REPORT</h3>';

        var printContents = image + title + '<br> <table>' +
            '<tr>' +
            '<td colspan="6"></td>' +
            '</tr>' +
            '<tr>' +
            '</tr>' +
            document.getElementById("ledger-table").innerHTML +
            '</table>';

        // Reset table to original page length
        table.page.len(10).draw();

        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>