<?php
session_start();
$connect = mysqli_connect("localhost","root","","demofinal");
$insertflag = "false";
if (!empty($_POST)) {
  $output = '';
  $message = '';
  $sku = mysqli_real_escape_string($connect, $_POST["sku"]);
  $channel = mysqli_real_escape_string($connect, $_POST["channel"]);

  if ($_POST["order_id"] != '') {
    $query = "
               UPDATE germantemporders
               SET sku='$sku',
               channel='$channel'
               WHERE id='" . $_POST["order_id"] . "'";
    $message = 'Data Updated';
  } elseif ($_POST["list_check"] != '') {
    $orderflags = implode(', ', $_POST["flags"]);
    $orderids = explode(',', $_POST["list_check"]);
    foreach ($orderids as $value) {
      $query = "
                    UPDATE germantemporders
                    SET flags='$orderflags'
                    WHERE id='" . $value . "'";
      mysqli_query($connect, $query);
    }
    $message = 'Flags Updated';
    $insertflag = "true";
  } elseif ($_POST["idlist"] != '') {
    $ids = explode(',', $_POST["idlist"]);
    foreach ($ids as $value) {
      $result = mysqli_query($connect, "SELECT * FROM germantemporders WHERE id='" . $value . "'");
      $row = mysqli_fetch_array($result);
      $status = "Pending";
      $phpdate = strtotime($row['date']);
      $date = date('Y-m-d', $phpdate);
      $flags = $row['flags'];
      if ($row['merge'] == "Merged") {
        $flags = $row['flags'] . ", Merged";
      }
      $firstname = $row['firstname'];
      $firstname = str_replace("'", "\'", $firstname);

      $sql = "INSERT into germanorders (orderID, status, date, channel, firstname, email, currency, ordertotal, name, sku, quantity, flags, shippingservice, shippingaddressline1, shippingaddressline2, shippingaddressline3, shippingaddressregion, shippingaddresscity, shippingaddresspostcode, shippingaddresscountry, shippingaddresscountrycode, booking, csvdate) values ('" . $row['orderID'] . "','" . $status . "','" . $date . "','" . $row['channel'] . "','" . $firstname . "','" . $row['email'] . "','" . $row['currency'] . "','" . $row['ordertotal'] . "','" . $row['name'] . "','" . $row['sku'] . "','" . $row['quantity'] . "','" . $flags . "','" . $row['shippingservice'] . "','" . $row['shippingaddressline1'] . "','" . $row['shippingaddressline2'] . "','" . $row['shippingaddressline3'] . "','" . $row['shippingaddressregion'] . "','" . $row['shippingaddresscity'] . "','" . $row['shippingaddresspostcode'] . "','" . $row['shippingaddresscountry'] . "','" . $row['shippingaddresscountrycode'] . "','" . $row['booking'] . "','" . $row['csvdate'] . "')";
      mysqli_query($connect, $sql);
    }
    $message = 'Orders Moved to Pending';
    $insertflag = "true";
?>
    <script type="text/javascript">
      window.open("../germanorders.php")
    </script>
<?php
  } elseif ($_POST["iddelete"] != '') {
    $ids = explode(',', $_POST["iddelete"]);
    foreach ($ids as $value) {
      $deletesql = "DELETE FROM germantemporders WHERE id = '" . $value . "'";
      mysqli_query($connect, $deletesql);
    }
    $message = 'Orders Deleted';
    $insertflag = "true";
  } else {
    $query = "INSERT INTO germantemporders(sku, channel) VALUES('$sku', '$channel');";
    $message = 'Data Inserted' . $_POST["order_id"];
  }

  if (($insertflag == "true") or (mysqli_query($connect, $query))) {
    $output .= '<label class="text-success">' . $message . '</label>';
    $select_query = "SELECT * FROM germantemporders WHERE 1";

    if (isset($_SESSION['category'])) {
      $category = $_SESSION['category'];
      $select_query .= " AND flags LIKE '%$category%' ";
    }

    if (isset($_SESSION['ordersCountry'])) {
      $ordersCountry = $_SESSION['ordersCountry'];
      if($ordersCountry=="Germany Orders"){
        $select_query .= " AND (shippingaddresscountry LIKE '%Germany%' OR shippingaddresscountry LIKE '%Deutschland%') ";
      }else if($ordersCountry=="Other Orders"){
        $select_query .= " AND (shippingaddresscountry NOT LIKE '%Germany%' AND shippingaddresscountry NOT LIKE '%Deutschland%') ";
      }
    }

    $select_query .= " ORDER BY total ASC, date ASC";

    $result = mysqli_query($connect, $select_query);

    if (isset($_SESSION['category'])) {
      echo "<p style='text-align:center;'><strong>" . $_SESSION['category'] . " Orders Only</strong></p>";
    }

    if (isset($_SESSION['ordersCountry'])) {
      echo "<p style='text-align:center;'><strong>" . $_SESSION['ordersCountry'] . " Only</strong></p>";
    }

    if (isset($_SESSION['ordersCountry']) OR isset($_SESSION['category'])) {
      echo "<p style='text-align:center; color:red;'><i>( Please select Select All in filter to see all orders )</i></p>";
    }

    $output .= '
                    <table class="table table-bordered" id="pendingts">
                         <tr>
                          <th width="5%">
                          <button type="button" id="pendingselectAll" class="main">
                      <span class="sub"></span>
                      Select All
                      </button>
                      </th>
                      <th width="15%">Image</th>
                      <th width="5%">Order ID</th>
                      <th width="15%">SKU</th>
                      <th width="5%">Qty</th>
                      <th width="20%">Address</th>
                          <th width="20%">Flags</th>
                          <th width="5%">Edit</th>
                          <th width="5%">View</th>
                          <th width="5%">Delete</th>
                         </tr>
               ';
    while ($row = mysqli_fetch_array($result)) {
      if ($row["merge"] != "" && $row["merge"] != "Merged") {
        continue;
      }
      $mainimageorder = "";
      $ordersku = $row["sku"];
      $mainimageresult = mysqli_query($connect, "SELECT * FROM comboproducts WHERE sku='" . $ordersku . "' OR originalsku='" . $ordersku . "'");
      $rowcount = mysqli_num_rows($mainimageresult);
      if ($rowcount > 0) {
        $mainimagerow["comboproducts"] = mysqli_fetch_array($mainimageresult);
        $mainimageorder = $mainimagerow['comboproducts']['image'];
      }

      if (empty($mainimageorder) && (strpos($ordersku, '+') === false)) {
        $mainimageresult = mysqli_query($connect, "SELECT * FROM products WHERE SKU='" . $ordersku . "'");

        $rowcount = mysqli_num_rows($mainimageresult);
        if ($rowcount > 0) {
          $mainimagerow["comboproducts"] = mysqli_fetch_array($mainimageresult);
          $mainimageorder = $mainimagerow['comboproducts']['Mainimage'];
        }
      }
      $clientname = "Name : " . $row["firstname"];
      $address = "";
      if (!empty($clientname)) {
        $address = $address . $clientname . "<br>";
      }
      if (!empty($row["shippingaddresscompany"])) {
        $address = $address . $row["shippingaddresscompany"] . "<br>";
      }
      if (!empty($row["shippingaddressline1"])) {
        $address = $address . $row["shippingaddressline1"] . "<br>";
      }
      if (!empty($row["shippingaddressline2"])) {
        $address = $address . $row["shippingaddressline2"] . "<br>";
      }
      if (!empty($row["shippingaddressline3"])) {
        $address = $address . $row["shippingaddressline3"] . "<br>";
      }
      if (!empty($row["shippingaddressregion"])) {
        $address = $address . $row["shippingaddressregion"] . "<br>";
      }
      if (!empty($row["shippingaddresscity"])) {
        $address = $address . $row["shippingaddresscity"] . "<br>";
      }
      if (!empty($row["shippingaddresspostcode"])) {
        $address = $address . $row["shippingaddresspostcode"] . "<br>";
      }
      if (!empty($row["shippingaddresscountry"])) {
        $address = $address . $row["shippingaddresscountry"] . "<br>";
      }
      if ($row["merge"] == "Merged") {
        $mergeid = $row["date"] . "-" . $row["orderID"];
        $mergequery = "SELECT * FROM germantemporders WHERE merge='" . $mergeid . "'";
        $mergeresult = mysqli_query($connect, $mergequery);
        $row_cnt = mysqli_num_rows($mergeresult);
        $rowspanno = ($row_cnt + 1);
      } else {
        $rowspanno = 1;
      }
      $output .= '
                      <tr>
                        <td style="text-align:center"><input type="checkbox" name="orders[]" value=' . $row["id"] . '></td>
                        <td><img style="width:100px; height:auto;" src=' . $mainimageorder . '></td>
                        <td>' . $row["orderID"];
      if ($row["merge"] == "Merged") {
        $output .= '<br>Merge';
      }
      $output .= '
                        </td>
                        <td>' . $row["sku"] . '<br>' . $row["date"] . '<br>' . $row["channel"] . '</td>
                        <td>' . $row["quantity"] . '</td>
                        <td rowspan=' . $rowspanno . '>' . $address . '</td>
                        <td>' . $row["flags"] . '</td>
                        <td><input type="button" name="edit" value="Edit" id="' . $row["id"] . '" class="btn btn-info btn-xs edit_data" /></td>
                        <td><input type="button" name="view" value="view" id="' . $row["id"] . '" class="btn btn-info btn-xs view_data" /></td>
                        <td><input type="button" name="delete" value="delete" id="' . $row["id"] . '" class="btn btn-info btn-xs delete_data" /></td>
                    </tr>
                    ';
      if ($row["merge"] == "Merged") {
        while ($mergerow = mysqli_fetch_array($mergeresult)) {
          $mainimageorder = "";
          $ordersku = $mergerow["sku"];
          $mainimageresult = mysqli_query($connect, "SELECT * FROM comboproducts WHERE sku='" . $ordersku . "' OR originalsku='" . $ordersku . "'");
          $rowcount = mysqli_num_rows($mainimageresult);
          if ($rowcount > 0) {
            $mainimagerow["comboproducts"] = mysqli_fetch_array($mainimageresult);
            $mainimageorder = $mainimagerow['comboproducts']['image'];
          }

          if (empty($mainimageorder) && (strpos($ordersku, '+') === false)) {
            $mainimageresult = mysqli_query($connect, "SELECT * FROM products WHERE SKU='" . $ordersku . "'");

            $rowcount = mysqli_num_rows($mainimageresult);
            if ($rowcount > 0) {
              $mainimagerow["comboproducts"] = mysqli_fetch_array($mainimageresult);
              $mainimageorder = $mainimagerow['comboproducts']['Mainimage'];
            }
          }
          $output .= '
                                   <tr>
                                   <td style="text-align:center"><input type="checkbox" name="orders[]" value=' . $mergerow["id"] . '></td>
                                   <td><img style="width:100px; height:auto;" src=' . $mainimageorder . '></td>
                                        <td>' . $mergerow["orderID"] . '</td>
                                        <td>' . $mergerow["sku"] . '<br>' . $mergerow["date"] . '<br>' . $mergerow["channel"] . '</td>
                                        <td>' . $mergerow["quantity"] . '</td>
                                        <td>' . $mergerow["flags"] . '</td>
                                        <td><input type="button" name="edit" value="Edit" id="' . $mergerow["id"] . '" class="btn btn-info btn-xs edit_data" /></td>
                                        <td><input type="button" name="view" value="view" id="' . $mergerow["id"] . '" class="btn btn-info btn-xs view_data" /></td>
                                        <td><input type="button" name="delete" value="delete" id="' . $mergerow["id"] . '" class="btn btn-info btn-xs delete_data" /></td>
                                   </tr>';
        }
      }
    }
    $output .= '</table>';
  }
  echo $output;
}
?>