<?php

$connect = mysqli_connect("localhost","root","","demofinal");

$sku = mysqli_real_escape_string($connect, $_POST["sku"]);

$channel = mysqli_real_escape_string($connect, $_POST["channel"]);

$qty = mysqli_real_escape_string($connect, $_POST["qty"]);

$mainimageorder="";

$mainimageresult = mysqli_query($connect, "SELECT * FROM comboproducts WHERE sku='" . $sku . "' OR originalsku='" . $sku . "'");

$mainimagerow= mysqli_fetch_array($mainimageresult);

$mainimageorder=$mainimagerow['image'];

if((empty($mainimageorder)&&(strpos($sku, '+') === false)))

{

    $mainimageresult = mysqli_query($connect, "SELECT * FROM products WHERE SKU='" . $sku . "'");

    $mainimagerow= mysqli_fetch_array($mainimageresult);

    $mainimageorder=$mainimagerow['Mainimage'];

}

$query = "UPDATE germantemporders SET sku='$sku', channel='$channel', quantity='$qty' WHERE id='".$_POST["order_id"]."'";

if(mysqli_query($connect, $query))
{

        echo json_encode(array("id" => $_POST["order_id"], "sku" => $sku, "image" => $mainimageorder));

}

 ?>
