<?php
    $pageTitle = "Orders Customers";
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
        <li class="breadcrumb-item active">Customers</li>
    </ol>
    <h2>Orders - Customers</h2>


    <div id="alertMessage" class="alert hide" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php
    if(!empty($_POST['action']) and $_POST['action'] ==1)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->createCustomer()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==2)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->updateCustomer()){

            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-success show");</script>';
        }
        else
        {
            echo '<script>document.getElementById("alertMessage").setAttribute("class","alert alert-dismissible alert-danger show");</script>';
        }
    }
    if(!empty($_POST['action']) and $_POST['action'] ==3)
    {
        echo '<script>document.getElementById("alertMessage").removeAttribute("class");</script>';
        if($printing->deleteCustomer()){

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
        <button class="btn btn-info" type="button" onclick="add()" data-toggle="modal" data-target="#modal1">Add Customer</button>
    </div>

    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h4>List of Customers</h4>
            </div>
            <div class="panel-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="active">
                                <th>Customer</th>
                                <th>Thickness</th>
                                <th>Reel Width</th>
                                <th>Repeat Length</th>
                                <th>Cylinder Size</th>
                                <th>Pifa direction</th>
                                <th>No of Ons</th>
                                <th>No of Colors</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $printing->giveCustomers();
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal-->
    <div class="modal fade" id="modal1" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-m">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">x</button>
                    <h4 class="modal-title" id="panelTitle"></h4>
                </div>
                <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="modal-body">
                        <input type="hidden" class="form-control" id="action" name="action" value=0>
                        <input type="hidden" class="form-control" id="customer" name="customer">
                        <div id="dropCustomer">
                            <h4 class="alert-heading">Are you sure you want to delete <strong id="deleteName"></strong> ?</h4>
                            Deleting a customer removes it permanently from the system. You cannot undo this action.<br><br>
                        </div>
                        <div class="form-group" id="nameDiv">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
						<div class="row" id="twe">
                            <div class="col-lg-6 form-group" >
                                <label for="name">Thickness</label>
                                <input type="text" class="form-control" id="thickness" name="thickness" value="45 µ">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">Reel Width</label>
                                <input type="text" class="form-control" id="reel" name="reel" value="330 mm">
                            </div>
                        </div>
                        <div class="row" id="two">
                            <div class="col-lg-6 form-group" >
                                <label for="name">Cylinder Size</label>
                                <input type="text" class="form-control" id="cylinder" name="cylinder" value="1100 x 472">
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="name">Repeat Length</label>
                                <input type="text" class="form-control" id="length" name="length" value="470">
                            </div>
                        </div>
                        
                        <div class="row" id="three">
                            <div class="col-lg-6 form-group">
                                    <label for="shift">Pifa Direction</label><br />
                                    <input type="hidden" class="form-control" id="pifa" name="pifa" value="0">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="btn_pifa" style="height:30px;">N/A&nbsp&nbsp<span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a onclick="selectPifa(0,'N/A')">N/A</a></li>
                                            <li><a onclick="selectPifa(1,'Pifa 1')">Pifa 1</a></li>
                                            <li><a onclick="selectPifa(2,'Pifa 2')">Pifa 2</a></li>
                                            <li><a onclick="selectPifa(3,'Pifa 3')">Pifa 3</a></li>
                                            <li><a onclick="selectPifa(4,'Pifa 4')">Pifa 4</a></li>
                                            <li><a onclick="selectPifa(5,'Pifa 5')">Pifa 5</a></li>
                                            <li><a onclick="selectPifa(6,'Pifa 6')">Pifa 6</a></li>
                                            <li><a onclick="selectPifa(7,'Pifa 7')">Pifa 7</a></li>
                                            <li><a onclick="selectPifa(8,'Pifa 8')">Pifa 8</a></li>
                                        </ul>
                                    </div>
                            </div>
                            <div class="col-lg-6 form-group" >
                                <label for="name">Number of Ons</label>
                                <input type="text" class="form-control" id="ons" name="ons" value="3">
                            </div>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="buttonForm" class="btn btn-info">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        function add() {
            document.getElementById("action").value = 1;
            document.getElementById("buttonForm").innerHTML = "Add";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Add customer";
            document.getElementById("dropCustomer").style.display = "none";
            
            document.getElementById("nameDiv").style.display = "";
            document.getElementById("two").style.display = "";
            document.getElementById("twe").style.display = "";
            document.getElementById("three").style.display = "";
        }

        function edit(id,name,cylinder,length,pifa,pifaname,ons,thickness,reel) {

            document.getElementById("action").value = 2;
            document.getElementById("buttonForm").innerHTML = "Update";
            document.getElementById("buttonForm").setAttribute("class","btn btn-info");  
            document.getElementById("panelTitle").innerHTML = "Update customer: "+name;
            
            document.getElementById("customer").value = id;
            document.getElementById("name").value = name;
            document.getElementById("cylinder").value = cylinder;
            document.getElementById("pifa").value = pifa;
            document.getElementById("ons").value = ons;
            document.getElementById("thickness").value = thickness;
            document.getElementById("reel").value = reel;
            document.getElementById("btn_pifa").innerHTML = pifaname+" &nbsp&nbsp<span class='caret'></span> ";
            
            document.getElementById("dropCustomer").style.display = "none";
            document.getElementById("nameDiv").style.display = "";
            document.getElementById("two").style.display = "";
            document.getElementById("twe").style.display = "";
            document.getElementById("three").style.display = "";
        }

        function delet(id,name) {
            document.getElementById("action").value = 3;
            document.getElementById("buttonForm").innerHTML = "Delete";
            document.getElementById("buttonForm").setAttribute("class","btn btn-danger");  
            document.getElementById("panelTitle").innerHTML = "Delete customer"; 
            document.getElementById("deleteName").innerHTML = name;
            
            document.getElementById("customer").value = id;
            
            document.getElementById("dropCustomer").style.display = "";
            document.getElementById("nameDiv").style.display = "none";
            document.getElementById("two").style.display = "none";
            document.getElementById("twe").style.display = "none";
            document.getElementById("three").style.display = "none";
        }
        
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
        function selectPifa(id, name) {
                document.getElementById("btn_pifa").innerHTML = name+" &nbsp&nbsp<span class='caret'></span> ";
                document.getElementById("pifa").value = id;
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