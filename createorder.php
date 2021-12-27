<?php 
include_once('database/connectdb.php');

session_start();
if($_SESSION['email'] == ""){
    header("location:index.php");
}
$page = 'ordercreate';
function fill_product($inventory){
  $output='';

  $select = $inventory->prepare("select * from products order by productName asc");
  $select->execute();

  $result = $select->fetchAll();

  foreach($result as $row){
    $output.='<option value="'.$row['id'].'" >'.$row['productName'].'</option>';
  }

  return $output;

}

if(isset($_POST["btn_create_order"])){
  $clientName = $_POST['clientName'];
  $clientContact = $_POST['clientContact'];
  $clientAddress = $_POST['clientAddress'];
  $orderdate = $_POST['orderdate'];
  $subTotal = $_POST['subTotal'];
  $gst = $_POST['gst'];
  $gstNo = $_POST['gstNo'];
  $discount = $_POST['discount'];
  $total = $_POST['grandTotal'];
  $paid = $_POST['paid'];
  $due = $_POST['due'];
  $paymentStatus = $_POST['paymentStatus'];
  $paymentType = $_POST['rb'];

  $arr_productid = $_POST['productId'];
  $arr_productname = $_POST['productName'];
  $arr_stock = $_POST['stock'];
  $arr_qty = $_POST['qty'];
  $arr_price = $_POST['price'];
  $arr_total = $_POST['total'];

 

  $insert = $inventory->prepare("insert into invoice (client_name, client_address, client_contact, order_date, subtotal, gst, discount, total, paid, due, payment_status, gst_no, payment_type) values(:name, :address, :contact, :orderdate, :subtotal, :gst, :discount, :total, :paid, :due, :payment_status, :gst_no, :payment_type)");

  $insert->bindParam(':name', $clientName);
  $insert->bindParam(':address', $clientAddress);
  $insert->bindParam(':contact', $clientContact);
  $insert->bindParam(':orderdate', $orderdate);
  $insert->bindParam(':subtotal', $subTotal);
  $insert->bindParam(':gst', $gst);
  $insert->bindParam(':discount', $discount);
  $insert->bindParam(':total', $total);
  $insert->bindParam(':paid', $paid);
  $insert->bindParam(':due', $due);
  $insert->bindParam(':payment_status', $paymentStatus);
  $insert->bindParam(':gst_no', $gstNo);
  $insert->bindParam(':payment_type', $paymentType);

  

  if($insert->execute()){
    echo '<script> 
    jQuery(function validation(){
        swal({
            title: "Good Job!",
            text: "Registered User!",
            icon: "success",
            button: "Ok"
          });
        }); </script>';
  }else{
    echo '<script> 
    jQuery(function validation(){
        swal({
            title: "Oopss something went wrong!",
            text: "Please try again later!",
            icon: "error",
            button: "Ok"
          });
        }); </script>';
  }

  $invoice_id = $inventory->lastInsertId();
  if($invoice_id != null){
    for ($i=0; $i < count($arr_productid); $i++) { 
      $remaining_quantity = $arr_stock[$i] - $arr_qty[$i];

      if($remaining_quantity < 0 ){
        return "Order is not complete";
      }
      else{
        $update = $inventory->prepare("update products set stock = '$remaining_quantity' where id = '".$arr_productid[$i]."'");
        $update->execute();
      }

      $insert_invoiced = $inventory->prepare("insert into invoice_details (invoice_id, product_id, product_name, quantity, price, order_date) values(:invoice_id, :pid, :pname, :qty, :price, :orderdate)");

      $insert_invoiced->bindParam(':invoice_id', $invoice_id);
      $insert_invoiced->bindParam(':pid', $arr_productid[$i]);
      $insert_invoiced->bindParam(':pname', $arr_productname[$i]);
      $insert_invoiced->bindParam(':qty', $arr_qty[$i]);
      $insert_invoiced->bindParam(':price', $arr_price[$i]);
      $insert_invoiced->bindParam(':orderdate', $orderdate);

      $insert_invoiced->execute();

      header('location: orderlist.php');
    }  
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
          <h1 class="m-0">Create Order</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><a href="#">Create Order</a></li>

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
                    <input type="text" name="clientName" class="form-control" id="clientName" placeholder="Name"
                      required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Client Contact <sup>*</sup></label>
                  <div class="input-group">
                    <input type="tel" name="clientContact" class="form-control" id="clientContact"
                      placeholder="Contact Number" required>
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
                    <input type="text" name="clientAddress" class="form-control" id="clientAddress"
                      placeholder="Address (City)">
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-user"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label>Date:</label>
                  <div class="input-group date" data-date-format="yyyy-MM-DD" id="reservationdate"
                    data-target-input="nearest">
                    <input type="text" name="orderdate" value="<?php echo date("Y-mm-d"); ?>" class="form-control datetimepicker-input"
                       data-target="#reservationdate" placeholder="Date" data-target="#reservationdate"
                      data-toggle="datetimepicker" />
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
                          <th scope="col"><a class="btn btn-success btn_add"><i class="fa fa-plus"></i></a></th>
                        </tr>
                      </thead>
                      <tbody>

                      </tbody>
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
                    <input type="text" name="subTotal" class="form-control" id="subTotal" placeholder="Sub Total"
                      required readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">GST (18%)</label>
                  <div class="input-group">
                    <input type="text" name="gst" class="form-control" id="gst" placeholder="Tax Amount" readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">GST NO.</label>
                  <div class="input-group">
                    <input type="text" name="gstNo" class="form-control" id="gstNo" placeholder="GST Number"
                      oninput="this.value = this.value.toUpperCase()" required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Discount</label>
                  <div class="input-group">
                    <input type="number" name="discount" class="form-control" id="discount" placeholder="Discount"
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
                      placeholder="Total" required readonly>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Paid</label>
                  <div class="input-group">
                    <input type="number" name="paid" class="form-control" id="paid" placeholder="Paid Amount" required>
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-usd"></i></div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="exampleInputEmail1">Due</label>
                  <div class="input-group">
                    <input type="text" name="due" class="form-control" id="due" placeholder="Due Amount" required
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
                      <option value="full">Full Payment</option>
                      <option value="advance">Advance Payment</option>
                      <option value="no">No Payment</option>
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
                <input type="radio" value="cash" id="radioPrimary1" name="rb" checked>
                <label for="radioPrimary1">Cash
                </label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" value="card" id="radioPrimary2" name="rb">
                <label for="radioPrimary2">Card
                </label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" value="cheque" id="radioPrimary3" name="rb">
                <label for="radioPrimary3">Cheque
                </label>
              </div>
            </div>

            <br>
            <hr>
            <div class="row d-flex justify-content-center">
              <div class="text-center">
                <div class="col-md-12">
                  <button type="submit" name="btn_create_order" class="btn btn-primary">Save Order</button>
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
<!-- Modal Add Account-->
<div class="modal fade" id="exampleModaladd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Fill Order Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      </div>
    </div>
  </div>
</div>
<?php include_once('footer.php'); ?>
<script>
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
      '<td><select id="select2" class="form-control productId" name="productId[]" style="width:250px"><option value="">Select</option><?php echo fill_product($inventory); ?></select></td>'
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
        }
      });
    });

  });
</script>
<script>
  $(document).on('click', 'td .btn_remove', function () {
    $(this).closest('tr').remove();
    calculate(0, 0);
    $("#paid").val(0);
    return false;
  });

  $('#product_table').delegate(".qty", "keyup change", function () {
    var quantity = $(this);
    var tr = $(this).parent().parent();
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