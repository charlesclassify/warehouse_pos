<div class="container">
    <h4>Report Dashboard</h4>

    <!-- Navigation for Modules -->
    <ul class="nav nav-tabs" id="moduleTabs">
        <li class="nav-item">

            <a class="nav-link active" data-bs-toggle="tab" href="#module1" role="tab" aria-controls="module1" aria-selected="true">Sales Report</a>

        </li>
        <li class="nav-item">

            <a class="nav-link" data-bs-toggle="tab" href="#module2" role="tab" aria-controls="module2" aria-selected="false">Receiving Report</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#module3" role="tab" aria-controls="module3" aria-selected="false">Inventory Report</a>

        </li>
        <!-- Add more modules as needed -->
    </ul>

    <!-- Purchase Order Report -->

    <div class="tab-content" id="moduleTabContent">
        <!-- Module 1 Content -->
        <div class="tab-pane fade show active" id="module1" role="tabpanel" aria-labelledby="module1-tab">
            <table class="table" id="user-datatables-module1">
                <thead>
                    <tr>
                        <th>Reference No.</th>
                        <th>Date Created</th>
                        <th>Customer</th>
                        <th>Total Cost</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Receiving Report -->
        <div class="tab-pane fade" id="module2" role="tabpanel" aria-labelledby="module2-tab">
            <table class="table" id="user-datatables-module2">
                <thead>
                    <tr>
                        <th>Receiving No</th>
                        <th>Supplier</th>
                        <th>Comments</th>
                        <th>Date</th>
                        <th>Incharge</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Inventory Adjustment Report -->
        <div class="tab-pane fade" id="module3" role="tabpanel" aria-labelledby="module3-tab">

            <table class="table" id="user-datatables-module3">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product</th>
                        <th>Old Quantity</th>
                        <th>New Quantity</th>
                        <th>Date Adjusted</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    function initDataTable(tableId, ajaxUrl, columns, orderColumn) {
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            return;
        }

        $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: ajaxUrl,
                type: "POST"
            },
            columns: columns,
            order: orderColumn,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });
    }

    // Initialize active tab immediately
    initDataTable(
        'user-datatables-module1',
        '<?= site_url('main/get_sales_report_ajax'); ?>',
        [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4, orderable: false }
        ],
        [[1, 'desc']]
    );

    // Initialize on tab show
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");

        if (target === '#module2') {
            initDataTable(
                'user-datatables-module2',
                '<?= site_url('main/get_receiving_report_ajax'); ?>',
                [
                    { data: 0 },
                    { data: 1 },
                    { data: 2 },
                    { data: 3 },
                    { data: 4 },
                    { data: 5, orderable: false }
                ],
                [[3, 'desc']]
            );
        }

        if (target === '#module3') {
            initDataTable(
                'user-datatables-module3',
                '<?= site_url('main/get_inventory_report_ajax'); ?>',
                [
                    { data: 0 },
                    { data: 1 },
                    { data: 2 },
                    { data: 3 },
                    { data: 4 },
                    { data: 5 }
                ],
                [[0, 'desc']]
            );
        }
    });

});

</script>