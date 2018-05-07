<?php
    $pageTitle = "Orders SalesOrders";
    include_once "../../base.php";
    include_once "../../header.php";
    include_once "../sidebar1.php";
    include_once "../../content.php";


    include_once "../../inc/class.printing.inc.php";
    $printing = new Printing($db);

?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="../index.php">United Production System</a>
        </li>
        <li class="breadcrumb-item">
            <a href="../process.php">Sachet Rolls</a>
        </li>
        <li class="breadcrumb-item">
            <a>Orders</a>
        </li>
        <li class="breadcrumb-item active">Sales Orders</li>
    </ol>
    <h2>Orders - Sales Orders</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['salesno']) )
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createSalesOrder()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
      
       
?>
    </div>

    <div class="row text-right" style="padding-bottom:15px;">
        <button class="btn btn-info" type="button" onclick="add()" data-toggle="modal" data-target="#modal1">Add Sales Order</button>
    </div>

    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>List of Sales Orders</h4>
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Sales Order No</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Customer LPO / Accepted PI</th>
                                <th>Product name</th>
                                <th>Order Quantity (kgs)</th>
                                <th>Delivery Date</th>
                                <th>Price (GHC per kg)</th>
                                <th>Terms of Payment</th>
                                <th>Status</th>
                                <th>Submitted by</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $printing->giveSalesOrders();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
	<div class="modal fade" id="modal1" role="dialog" tabindex="-1">
		<div class="modal-dialog" style="width:600px;">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal">x</button>
					<h4 class="modal-title">Add Sales Order</h4>
				</div>
				<form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="modal-body">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Sales Order No. <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="salesno" name="salesno" value="" required/>
							</div>

							<div class="form-group col-md-6">
								<label for="date">Sales Order Date <span class="text-danger">*</span></label>
								<div class='input-group date' id='datetimepicker'>
									<input type='text' class="form-control" id="date" name="date" required/>
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>
							
							<div class="form-group col-md-6">
								<label for="material">Customer <span class="text-danger">*</span></label><br/>
								<input type="hidden" class="form-control" id="customer" name="customer" required>
								<div class="btn-group">
									<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown" id="btn_customer">&nbsp&nbsp<span class="caret"></span></button>
									<ul class="dropdown-menu" role="menu" id="dropdown_customer">
										<li><input type="text" placeholder="Search customer.." class="searchDropdown" id="searchCustomer" onkeyup="filterCustomers()" width="100%"></li>
										<?php
        $printing->customersDropdown();
    ?>
									</ul>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Product Name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="product" name="product" required/>
							</div>
						</div>
						<div class="form-group">
							<label>Customer LPO / Accepted PI</label>
							<textarea type="text" class="form-control" rows="2" id="remarks" name="remarks"></textarea>
						</div>

						<div class="row">
							<div class="form-group col-md-6">
								<label for="date">Order Quantity <span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="1" id="qty" name="qty" required>
							</div>
							<div class="form-group col-md-6">
								<label for="date">Delivery Date</label>
								<div class='input-group date' id='datetimepicker'>
									<input type='text' class="form-control" id="deliveryDate" name="deliveryDate" />
									<span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
									</span>
								</div>
							</div>

							<div class="form-group col-md-6">
								<label for="total">Price (GHC per kg)<span class="text-danger">*</span></label>
								<input type="number" class="form-control" min="1" step="0.01" id="price" name="price" required>
							</div>
							
							<div class="form-group col-md-6">
								<label for="date">Terms</label>
								<input type="text" class="form-control" id="terms" name="terms" />
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<button type="submit" id="buttonForm" class="btn btn-info">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>


    <script>
        function selectCustomer(id, name) {
            document.getElementById("btn_customer").innerHTML = name + " &nbsp&nbsp<span class='caret'></span> ";
            document.getElementById("customer").value = id;
        }

        function filterCustomers() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("searchCustomer");
            filter = input.value.toUpperCase();
            div = document.getElementById("dropdown_customer");
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].id.toUpperCase().startsWith(filter)) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }

    </script>
   <script>
        $(document).ready(function() {
            $('#dataTable').DataTable(  );
        } );
    </script>

    <?php
    include_once '../../footer.php';
?>