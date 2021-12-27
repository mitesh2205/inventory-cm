<?php 
include_once('database/connectdb.php');

session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'ordercreate';
function fill_product($inventory,$product_id){
  $output='';

  $select = $inventory->prepare("select * from products order by productName asc");
  $select->execute();

  $result = $select->fetchAll();

  foreach($result as $row){
    $output.='<option value="'.$row['id'].'"';
     if($product_id == $row['id']){ 
        $output.= 'selected';
     }$output.='>'.$row['productName'].'</option>';
  }

  return $output;

}

// GET ID
$id = $_GET['id'];
// FETCH INVOICE DETAIL FROM INVOICE TABLE
$select = $inventory->prepare("select * from invoice where id = $id"); 
$select->execute();
$row= $select->fetch(PDO::FETCH_ASSOC);

$clientName = $row['client_name'];
$clientContact = $row['client_contact'];
$clientAddress = $row['client_address'];
$orderdate = $row['order_date'];
$subTotal = $row['subtotal'];
$gst = $row['gst'];
$gstNo = $row['gst_no'];
$discount = $row['discount'];
$total = $row['total'];
$paid = $row['paid'];
$due = $row['due'];
$paymentStatus = $row['payment_status'];
$paymentType = $row['payment_type'];

// FETCH PRODUCTS ATTACHED WITH THE INVOICE ID AND INVOICE DETAILS 

$select = $inventory->prepare("select * from invoice_details where invoice_id = $id"); 
$select->execute();
$row_invoice_details = $select->fetchAll(PDO::FETCH_ASSOC);

// ONCLICK OF SAVE BUTTON

if(isset($_POST["btn_edit_order"])){
  
  $edit_clientName = $_POST['clientName'];
  $edit_clientContact = $_POST['clientContact'];
  $edit_clientAddress = $_POST['clientAddress'];
  $edit_orderdate = $_POST['orderdate'];
  $edit_subTotal = $_POST['subTotal'];
  $edit_gst = $_POST['gst'];
  $edit_gstNo = $_POST['gstNo'];
  $edit_discount = $_POST['discount'];
  $edit_total = $_POST['grandTotal'];
  $edit_paid = $_POST['paid'];
  $edit_due = $_POST['due'];
  $edit_paymentStatus = $_POST['paymentStatus'];
  $edit_paymentType = $_POST['rb'];

  $arr_productid = $_POST['productId'];
  $arr_productname = $_POST['productName'];
  $arr_stock = $_POST['stock'];
  $arr_qty = $_POST['qty'];
  $arr_price = $_POST['price'];
  $arr_total = $_POST['total'];

//  QUERY TO UPDATE THE STOCK AFTER EDITING.

  foreach ($row_invoice_details as $item) {
      $updateproduct = $inventory->prepare("update products set stock=stock+".$item['quantity']." where id = '".$item['product_id']."'");

      $updateproduct->execute();
  }

// DELETE THE ORIGINAL INVOICE DETAILS FROM INVOICE_DETAILS TABLE

  $delete_invoice_details = $inventory->prepare("delete from invoice_details where invoice_id = $id");
  $delete_invoice_details->execute();
//    write update query for invoice table
  $update = $inventory->prepare("update invoice set client_name=:name, client_address=:address, client_contact=:contact, order_date=:orderdate, subtotal=:subtotal, gst=:gst, discount=:discount, total=:total, paid=:paid, due=:due, payment_status=:payment_status, gst_no=:gst_no, payment_type=:payment_type where id = $id");

  $update->bindParam(':name', $edit_clientName);
  $update->bindParam(':address', $edit_clientAddress);
  $update->bindParam(':contact', $edit_clientContact);
  $update->bindParam(':orderdate', $edit_orderdate);
  $update->bindParam(':subtotal', $edit_subTotal);
  $update->bindParam(':gst', $edit_gst);
  $update->bindParam(':discount', $edit_discount);
  $update->bindParam(':total', $edit_total);
  $update->bindParam(':paid', $edit_paid);
  $update->bindParam(':due', $edit_due);
  $update->bindParam(':payment_status', $edit_paymentStatus);
  $update->bindParam(':gst_no', $edit_gstNo);
  $update->bindParam(':payment_type', $edit_paymentType);

  $update->execute();
   

  $invoice_id = $inventory->lastInsertId();
  if($invoice_id != null){
    for ($i=0; $i < count($arr_productid); $i++) {  

      $select_product_detail = $inventory->prepare("select * from products where id = '".$arr_productid[$i]."'");
      $select_product_detail->execute();

      while ($row_product_detail = $select_product_detail->fetch(PDO::FETCH_OBJ)) {

        $db_stock[$i] = $row_product_detail->stock;

        $remaining_quantity = $db_stock[$i] - $arr_qty[$i];

        if($remaining_quantity < 0 ){
            return "Order is not complete";
        }
        else{
            //  update query for product table
            $update = $inventory->prepare("update products set stock = '$remaining_quantity' where id = '".$arr_productid[$i]."'");
            $update->execute();
        }
      }

      
//  write insert query for table  // INSERT DATA IN INVOICE_DETAIL TABLE AFTER SUCCESSFULLY UPDATING IT.
      $insert_invoiced = $inventory->prepare("insert into invoice_details (invoice_id, product_id, product_name, quantity, price, order_date) values(:invoice_id, :pid, :pname, :qty, :price, :orderdate)");

      $insert_invoiced->bindParam(':invoice_id', $id);
      $insert_invoiced->bindParam(':pid', $arr_productid[$i]);
      $insert_invoiced->bindParam(':pname', $arr_productname[$i]);
      $insert_invoiced->bindParam(':qty', $arr_qty[$i]);
      $insert_invoiced->bindParam(':price', $arr_price[$i]);
      $insert_invoiced->bindParam(':orderdate', $edit_orderdate);

      $insert_invoiced->execute();

      
    }
    header('location: orderlist.php');  
  }
  
}

include_once('header.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="overflow-x:auto;">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Order</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Order / <a href="#">Edit Order</a></li>

          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-body">
          <form action="" role="form" method="post">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Client Name <sup>*</sup></label>
                  <div class="input-group">
                    <input type="text" name="clientName" value="<?php echo $clientName ?>" class="form-control" id="clientName"
                      required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Client Contact <sup>*</sup></label>
                  <div class="input-group">
                    <input type="tel" name="clientContact" value="<?php echo $clientContact ?>" class="form-control" id="clientContact"
                       required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Client Address</label>
                  <div class="input-group">
                    <input type="text" name="clientAddress" value="<?php echo $clientAddress ?>" class="form-control" id="clientAddress">
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Date:</label>
                  <div class="input-group date" data-date-format="yyyy-MM-DD" id="reservationdate"
                    data-target-input="nearest">
                    <input type="text" name="orderdate" value="<?php echo $orderdate ?>" class="form-control datetimepicker-input"
                       data-target="#reservationdate" data-toggle="datetimepicker" />
                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                      <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>


            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="product_table">
                      <thead>
                        <tr>
                          <th scope="col" width="10px">#</th>
                          <th scope="col">Search Product</th>
                          <th scope="col">Stock</th>
                          <th scope="col">Price</th>
                          <th scope="col">Enter Quantity</th>
                          <th scope="col">Total</th>
                          <th scope="col"><a class="btn btn-info btn_add"><i class="fa fa-plus"></i></a></th>
                        </tr>
                      </thead>
                      <?php
                        foreach ($row_invoice_details as $item) {

                             $select = $inventory->prepare("select * from products where id = '{$item['product_id']}'"); 
                            $select->execute();
                            $row_product = $select->fetch(PDO::FETCH_ASSOC);
                        
                      ?>
                      <tbody>
                            <tr>
                               <?php
                               echo'<td><input type="hidden" class="form-control productName" value="'.$row_product['productName'].'" name="productName[]"></td>';
                               echo
                                 '<td><select id="select2" class="form-control productIdEdit" name="productId[]" style="width:250px"><option value="">Select</option>' .fill_product($inventory,$item['product_id']). '</select></td>';
                               echo '<td><input type="text" class="form-control stock" name="stock[]" value="'.$row_product['stock'].'" readonly></td>';
                               echo '<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['salePrice'].'" readonly></td>';
                               echo '<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item['quantity'].'" ></td>';
                               echo '<td><input type="text" class="form-control total" id="total" name="total[]" value="'.$row_product['salePrice']* $item['quantity'].'" readonly></td>';
                               echo '<td><a class="btn btn-danger btn_remove"><i class="fa fa-remove"></i></a></td>';
                               ?> 
                            </tr>
                      </tbody>
                      <?php } ?>
                    </table>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
            </div>


            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Sub Total</label>
                  <div class="input-group">
                    <input type="text" name="subTotal" class="form-control" value="<?php echo $subTotal ?>" id="subTotal"
                      required readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">GST (18%)</label>
                  <div class="input-group">
                    <input type="text" name="gst" class="form-control" id="gst" value="<?php echo $gst ?>" readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">GST NO.</label>
                  <div class="input-group">
                    <input type="text" name="gstNo" class="form-control" id="gstNo" value="<?php echo $gstNo ?>"
                      oninput="this.value = this.value.toUpperCase()" required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Discount</label>
                  <div class="input-group">
                    <input type="number" name="discount" class="form-control" id="discount" value="<?php echo $discount ?>"
                      required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Total</label>
                  <div class="input-group">
                    <input type="text" name="grandTotal" class="form-control grandTotal" id="grandTotal"
                    value="<?php echo $total ?>" required readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Paid</label>
                  <div class="input-group">
                    <input type="number" name="paid" class="form-control" id="paid" value="<?php echo $paid ?>" required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Due</label>
                  <div class="input-group">
                    <input type="text" name="due" class="form-control" id="due" value="<?php echo $due ?>" required
                      readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Payment Status</label>
                  <div class="input-group">
                    <select class="form-control" name="paymentStatus" id="paymentStatus" required>
                      <option value="">Select</option>
                      <option value="full" <?php if($paymentStatus == 'full'){ echo "selected";} ?>>Full Payment</option>
                      <option value="advance" <?php if($paymentStatus == 'advance'){ echo "selected";} ?>>Advance Payment</option>
                      <option value="no" <?php if($paymentStatus == 'no'){ echo "selected";} ?>>No Payment</option>
                    </select>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group clearfix float-right">
              <label for="">Payment Method: &nbsp;</label>
              <div class="icheck-primary d-inline">
                <input type="radio" value="cash" id="radioPrimary1" name="rb" <?php if($paymentType == 'cash'){ echo "checked";} ?> >
                <label for="radioPrimary1">Cash
                </label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" value="card" id="radioPrimary2" name="rb" <?php if($paymentType == 'card'){ echo "checked";} ?> >
                <label for="radioPrimary2">Card
                </label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" value="cheque" id="radioPrimary3" name="rb" <?php if($paymentType == 'cheque'){ echo "checked";} ?> >
                <label for="radioPrimary3">Cheque
                </label>
              </div>
            </div>

            <br>
            <hr>
            <div class="row d-flex justify-content-center">
              <div class="text-center">
                <div class="col-md-12">
                  <button type="submit" name="btn_edit_order" class="btn btn-warning">Update Order</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include_once('footer.php'); ?>
<script>

    $('.productIdEdit').select2();

    $(".productIdEdit").on('change', function (e) {
    var productId = this.value;
    var tr = $(this).parent().parent();
    $.ajax({

        url: "getproduct.php",
        method: "get",
        data: {
        id: productId,
        },
        success: function (data) {
        // console.log(data);
        tr.find(".productName").val(data["productName"]);
        tr.find(".stock").val(data["stock"]);
        tr.find(".price").val(data["salePrice"]);
        tr.find(".qty").val(1);
        tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());
        calculate(0, 0);
        $("#paid").val("");
        }
    });
    });

  $('.btn_add').click(function () {
    var tableLength = $("#productTable tbody tr").length;

    var tableRow;
    var arrayNumber;
    var count;

    if (tableLength > 0) {
      tableRow = $("#product_table tbody tr:last").attr('id');
      arrayNumber = $("#product_table tbody tr:last").attr('class');
      count = tableRow.substring(3);
      count = Number(count) + 1;
      arrayNumber = Number(arrayNumber) + 1;
    } else {
      // no table row
      count = 1;
      arrayNumber = 0;
    }
    var html = '';
    html += '<tr id="row' + count + '" class="' + arrayNumber + '">';
    html += '<td><input type="hidden" class="form-control productName" name="productName[]"></td>'
    html +=
      '<td><select id="select2" class="form-control productId" name="productId[]" style="width:250px"><option value="">Select</option><?php echo fill_product($inventory,''); ?></select></td>'
    html += '<td><input type="text" class="form-control stock" name="stock[]" readonly></td>'
    html += '<td><input type="text" class="form-control price" name="price[]" readonly></td>'
    html += '<td><input type="number" min="1" class="form-control qty" name="qty[]"></td>'
    html += '<td><input type="text" class="form-control total" id="total" name="total[]" readonly></td>'
    html += '<td><a class="btn btn-danger btn_remove"><i class="fa fa-remove"></i></a></td>'
    html += '</tr>';
    $('#product_table').append(html);
    $('.productId').select2();

    $(".productId").on('change', function (e) {
      var productId = this.value;
      var tr = $(this).parent().parent();
      $.ajax({

        url: "getproduct.php",
        method: "get",
        data: {
          id: productId,
        },
        success: function (data) {
          // console.log(data);
          tr.find(".productName").val(data["productName"]);
          tr.find(".stock").val(data["stock"]);
          tr.find(".price").val(data["salePrice"]);
          tr.find(".qty").val(1);
          tr.find(".total").val(tr.find(".qty").val() * tr.find(".price").val());
          calculate(0, 0);
          $("#paid").val("");
        }
      });
    });

  });
</script>
<script>
  $(document).on('click', 'td .btn_remove', function () {
    $(this).closest('tr').remove();
    calculate(0, 0);
    $("#paid").val("");
    return false;
  });

  $('#product_table').delegate(".qty", "keyup change", function () {
    var quantity = $(this);
    var tr = $(this).parent().parent();
    $("#paid").val("");
    if ((quantity.val() - 0) > (tr.find(".stock").val() - 0)) {
      swal("Warning!", "SORRY! This much of quantity is not available", "warning");

      quantity.val(1);
      tr.find(".total").val(quantity.val() * tr.find(".price").val());
    } else {
      tr.find(".total").val(quantity.val() * tr.find(".price").val());
      calculate(0, 0);
    }
  })

  function calculate(dis, paid) {
    var subtotal = 0;
    var gst = 0;
    var discount = dis;
    var grandTotal = 0;
    var paid_amt = paid;
    var due = 0;

    $(".total").each(function () {
      subtotal = subtotal + ($(this).val() * 1);
    })

    gst = 0.18 * subtotal;
    // discount = $("#discount").val();
    grandTotal = subtotal + gst - discount;
    due = grandTotal - paid_amt;
    $("#grandTotal").val(grandTotal.toFixed(2));
    $("#gst").val(gst.toFixed(2));
    $("#subTotal").val(subtotal.toFixed(2));
    // $("#discount").val(discount.toFixed(2));
    $("#due").val(due.toFixed(2));

  }

  $("#discount").keyup(function () {
    var discount = $(this).val();
    calculate(discount, 0);
  })
  $("#paid").keyup(function () {
    var paid = $(this).val();
    var discount = $("#discount").val();
    calculate(discount, paid);
  })
</script>
<script>
  //Date picker
  $('#reservationdate').datetimepicker({
    format: 'L',
    autoclose: 'true'
  });
</script>