<?php
// Database Connect
include_once('database/connectdb.php');
// *** Category Update ***
if(isset($_POST['categoryName'])){
    $categoryName = $_POST['categoryName'];
    $id = $_POST['id'];

    $update = $inventory->prepare("update category set category='$categoryName' where id=$id");
    $update->execute();

}

// *** Product Update ***
if(isset($_POST['productId'])){
    $productId = $_POST['productId'];
?>
<input type="hidden" name="id" value="<?php echo $productId ?>">
<?php
    $select = $inventory->prepare("select * from products where id = $productId");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $catId = $row['categoryId'];
    $select_cat = $inventory->prepare("select * from category where id = $catId");
    $select_cat->execute();
    $row_cat = $select_cat->fetch(PDO::FETCH_OBJ);
?>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="exampleInputEmail1">Product Name</label>
            <input type="text" name="productName" class="form-control" id="productName" placeholder="Product Name"
                value="<?php echo $row['productName']; ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category" id="category" class="form-control">
                <!-- Select category from category table -->
                <?php
                    $select_all = $inventory->prepare("select * from category");
                    $select_all->execute();
                while($row_all = $select_all->fetch(PDO::FETCH_ASSOC)){
                extract($row_all);
                ?>
                <option value="<?php echo $row_all['id']; ?>"
                    <?php if($row_all['id'] == $row_cat->id){echo "selected";} else{echo "";} ?>>
                    <?php echo $row_all['category']; ?>
                </option>
                <?php
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Purchase Price</label>
            <input type="number" min="1" step="1" name="purchasePrice" class="form-control" id="purchasePrice"
                value="<?php echo $row['purchasePrice'] ?>" placeholder="Purchase Price">
        </div>
        <div class="form-group">
            <label>Sale Price</label>
            <input type="number" min="1" step="1" name="salePrice" class="form-control" id="salePrice"
                value="<?php echo $row['salePrice'] ?>" placeholder="Sale Price">
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Stock</label>
            <input type="number" min="1" step="1" name="stock" class="form-control" id="stock"
                value="<?php echo $row['stock'] ?>" placeholder="Enter..">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" placeholder="Enter..." name="description" id="description"
                rows="4"><?php echo $row['description'] ?></textarea>
        </div>
        <div class="form-group">
            <label for="exampleInputFile">Product Image</label>
            <div class="input-group">
                <div class="custom-file">
                    <input type="file" name="productImage" class="custom-file-input" id="productImage"
                        value="<?php echo $row['productImage'] ?>">
                    <label class="custom-file-label" for="exampleInputFile"><?php echo $row['productImage'] ?></label>
                </div>
            </div>
        </div>
        <input type="hidden" name="image" value="<?php echo $row['productImage'] ?>">
    </div>
</div>
<?php
}
// *** Product View ***
if(isset($_POST['productIdv'])){
    $productId = $_POST['productIdv'];
?>
<input type="hidden" name="id" value="<?php echo $productId ?>">
<?php
    $select = $inventory->prepare("select * from products where id = $productId");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);
    $catId = $row['categoryId'];
    $select_cat = $inventory->prepare("select * from category where id = $catId");
    $select_cat->execute();
    $row_cat = $select_cat->fetch(PDO::FETCH_OBJ);
?>
<div class="card card-solid">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-sm-6">
              <h3 class="d-inline-block d-sm-none"><?php echo $row['productName']; ?></h3>
              <div class="col-12">
                <img src="<?php echo $row['productImage']; ?>" class="product-image" alt="Product Image" style="max-height:900px">
              </div>
            </div>
            <div class="col-12 col-sm-6">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                   <b> Product Name</b>
                    <span class="badge badge-primary badge-pill"><?php echo $row['productName'] ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                   <b> Category</b>
                    <span class="badge badge-primary badge-pill"><?php echo $row_cat->category ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                   <b> Purchase Prise</b>
                    <span class="badge badge-warning badge-pill"><?php echo $row['purchasePrice'] ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Sale Prise</b>
                    <span class="badge badge-warning badge-pill"><?php echo $row['salePrice'] ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <b>Stock</b>
                    <span class="badge badge-danger badge-pill"><?php echo $row['stock'] ?></span>
                </li>
                <li class="list-group-item ">
                    <b>Description</b>: &nbsp; <?php echo $row['description'] ?>
                    <span class="float-left"></span>
                </li>
            </ul>
            </div>
          </div>
          
        </div>
        <!-- /.card-body -->
</div>
<?php
}

if(isset($_POST['orderIdd'])){
    $id = $_POST['orderIdd'];
    
    $delete = $inventory->prepare("delete invoice, invoice_details FROM invoice INNER JOIN invoice_details ON invoice.id = invoice_details.invoice_id where invoice.id = $id");
    if($delete->execute()){
     
    }
    else{
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
    }

?>
<!-- bs-custom-file-input -->
<script src="plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>