<h4>Product Management</h4>

<div class="col-sm-6">

</div>
<?php if (isset($_SESSION['UserLoginSession']['role']) && $_SESSION['UserLoginSession']['role'] == USER_ROLE_ADMIN) : ?>
  <h1 class="m-0 text-dark">
    <a href="<?php echo site_url('main/product'); ?>" class="btn btn-primary btn-sm btn-success"><i class="fas fa-boxes"></i> Products</a>
    <a href="<?php echo site_url('main/unit'); ?>" class="btn btn-primary btn-sm btn-success"><i class="fas fa-barcode"></i> Unit Management</a>
  </h1>
<?php endif; ?>
<div class="card" style="box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
  <?php if (isset($_SESSION['UserLoginSession']['role']) && $_SESSION['UserLoginSession']['role'] == USER_ROLE_ADMIN) : ?>
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <a href="<?php echo site_url('main/add_product'); ?>" class="btn btn-success btn-sm"><i class="fas fa-box"></i> Add Product</a>
          <a href="<?php echo site_url('main/batch_receiving'); ?>" class="btn btn-success btn-sm"><i class="fas fa-dolly-flatbed"></i> Receive Products</a> <!-- ADDED -->
          <a href="<?php echo site_url('main/printproduct'); ?>" class="btn btn-success btn-sm "><i class="fas fa-print"></i>
          Print Inventory Report</a> <!-- Added Nov 6, 2024-->
          <a href="<?php echo site_url('main/export_products_excel'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-file-excel"></i> Export to Excel</a>
        </div>
      </div>
    </div>
  <?php elseif (isset($_SESSION['UserLoginSession']['role']) && $_SESSION['UserLoginSession']['role'] == USER_ROLE_INBOUND_USER):  ?>
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <a href="<?php echo site_url('main/batch_receiving'); ?>" class="btn btn-success btn-sm"><i class="fas fa-dolly-flatbed"></i> Receive Products</a> <!-- ADDED -->
          <a href="<?php echo site_url('main/printproduct'); ?>" class="btn btn-success btn-sm "><i class="fas fa-print"></i> 
          Print Inventory Report</a> <!-- Added Nov 6, 2024-->
          <a href="<?php echo site_url('main/export_products_excel'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-file-excel"></i> Export to Excel</a>
        </div>
      </div>
    </div>
  <?php elseif (isset($_SESSION['UserLoginSession']['role']) && $_SESSION['UserLoginSession']['role'] == USER_ROLE_OUTBOUND_USER):  ?>
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-sm-6">
          <a href="<?php echo site_url('main/printproduct'); ?>" class="btn btn-success btn-sm "><i class="fas fa-print"></i> <!-- Added Nov 6, 2024-->
          Print Inventory Report</a>
          <a href="<?php echo site_url('main/export_products_excel'); ?>" class="btn btn-primary btn-sm"><i class="fas fa-file-excel"></i> Export to Excel</a>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="card-body">
    <div class="table-responsive">
      <table id="productTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <tr>
            <th>No.</th>
            <th>SAP Code</th>
            <th>Product Name</th>

            <th>Price</th>
            <th>Quantity</th>
            <th>UoM</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  <div id="modalContainer"></div>
</div>
<script>
  // Add Quantity Modal
  document.addEventListener('DOMContentLoaded', function() {
    function handleReceiveButtonClick(event) {
      event.preventDefault();
      var productId = this.getAttribute('data-productid');
      console.log("Clicked button product ID:", productId);
      loadModalContent('<?php echo base_url('Main/receive_quantity/'); ?>' + productId, productId);
    }
    var receiveButtons = document.querySelectorAll('.addReceivedQuantitiesBtn');
    receiveButtons.forEach(function(button) {
      button.addEventListener('click', handleReceiveButtonClick);
    });
  });

  function loadModalContent(url, productId) {
    console.log("loadModalContent function called with product ID:", productId);
    fetch(url)
      .then(response => response.text())
      .then(data => {
        document.getElementById('modalContainer').innerHTML = data;
        document.querySelector('#modalContainer input[name="product_id"]').value = productId;
        $('#staticBackdropReceiving').modal('show');
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  document.addEventListener("DOMContentLoaded", function() {
    const productTable = $('#productTable').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": {
        "url": "<?php echo site_url('main/get_products_ajax'); ?>",
        "type": "POST"
      },
      "columns": [
        { "data": 0, "orderable": false },
        { "data": 1 },
        { "data": 2 },
        { "data": 3 },
        { "data": 4 },
        { "data": 5 },
        { "data": 6, "orderable": false }
      ],
      "order": [[1, 'asc']],
      "pageLength": 10,
      "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Event listener for delete button
    $('.delete-product-btn').on('click', function() {
      const productId = $(this).data('product-id');
      const confirmation = confirm('Are you sure you want to delete this product?');
      if (confirmation) {
        window.location.href = "<?php echo site_url('main/delete_product/'); ?>" + productId;
      }
    });

    const searchInput = document.getElementById("searchInput");

    if (searchInput) {
      searchInput.addEventListener("input", function() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        productTable.search(searchTerm).draw();
      });
    }
  });
  $(document).ready(function() {
    <?php if ($this->session->flashdata('success')) { ?>
      toastr.success('<?php echo $this->session->flashdata('success'); ?>');
    <?php } elseif ($this->session->flashdata('error')) { ?>
      toastr.error('<?php echo $this->session->flashdata('error'); ?>');
    <?php } ?>
  });
</script>