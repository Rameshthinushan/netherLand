<?php
include 'functions.php';
include 'db_config.php';

if (isset($_POST["csvbutton"])) {

  $url = file_get_contents('http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');

  $xml =  new SimpleXMLElement($url);

  file_put_contents(dirname(__FILE__) . "/loc.xml", $xml->asXML());

  foreach ($xml->Cube->Cube->Cube as $rate) {
    if ($rate["currency"] == "GBP") {
      $rate = $rate["rate"];
      break;
    }
  }

  $fileName = $_FILES["file"]["tmp_name"];
  $booking = $_POST["booking"];
  $csvtype = $_POST["csvtype"];
  $csvdate = $_POST["csvdate"];

  if ($_FILES["file"]["size"] > 0) {
    $ordercsvrows = 0;
    $orderaddedrows = 0;
    $internationalOrders = 0;

    $file = fopen($fileName, "r");
    $g = 0;
    $flag = true;
    while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
      if ($flag) {
        $flag = false;
        continue;
      }
      
      $ordercsvrows = $ordercsvrows + 1;
      $zenstoresOrderTotal = 0;

      if($csvtype=="linnworks"){
        $orderID = $column[0];
        $date = date_create($column[1]);
        $date = date_format($date, "Y-m-d H:i:s");
        $channel = $column[9] . "-" . $column[10];
        $firstname = $column[3];
        $firstname = str_replace("'", "\'", $firstname);
        //$lastname=$column[5];
        //$telephone=$column[6];
        $email = $column[5];
        $currency = $column[6];
        $ordertotal = $column[7] + $column[24];
        $total = $ordertotal;
        $name = str_replace("'", "", $column[8]);
        $sku = $column[11];
        if ($column[9] == "AMAZON") {
          $sku = $column[21];
          if ($sku == "") {
            $sku = $column[11];
          }
        }
        if ((substr($sku, -4)) == "-IDE") {
          $sku = substr($sku, 0, -4);
        }
        $quantity = $column[12];
        //$orderquantity=$column[13];
        //$lineitemtotal=$column[14];
        //$flags = $column[15];
        $shippingservice = $column[22];
        $shippingservice = str_replace("'", "", $shippingservice);
        //$shippingaddresscompany=$column[20];
        $shippingaddressline1 = $column[13];
        $shippingaddressline1 = str_replace("'", "", $shippingaddressline1);
        $shippingaddressline2 = $column[14];
        $shippingaddressline2 = str_replace("'", "", $shippingaddressline2);
        $shippingaddressline3 = $column[15];
        $shippingaddressline3 = str_replace("'", "", $shippingaddressline3);
        $shippingaddressregion = $column[17];
        $shippingaddresscity = $column[16];
        $shippingaddresscity = str_replace("'", "", $shippingaddresscity);
        $shippingaddresspostcode = $column[18];
        $shippingaddresscountry = $column[19];
        $shippingaddresscountrycode = $column[20];
      }else if($csvtype=="zenstores"){
        $orderID = $column[0];
        $date = date_create($column[2]);
        $date = date_format($date, "Y-m-d H:i:s");
        $channel = str_replace(":", "-", $column[3]);
        $firstname = $column[4]." ".$column[5];
        $firstname = str_replace("'", "\'", $firstname);
        //$lastname=$column[5];
        //$telephone=$column[6];
        $email = $column[7];
        $currency = $column[8];

        $ordertotal = $column[14];
        $total = $column[14];

        $zenstoresOrderTotal = $column[9];

        $name = str_replace("'", "", $column[10]);
        $sku = $column[11];
        if ((substr($sku, -4)) == "-IDE") {
          $sku = substr($sku, 0, -4);
        }
        $quantity = $column[13];
        //$orderquantity=$column[13];
        //$lineitemtotal=$column[14];
        //$flags = $column[15];
        $shippingservice = $column[18];
        $shippingservice = str_replace("'", "", $shippingservice);
        //$shippingaddresscompany=$column[20];
        $shippingaddressline1 = $column[21];
        $shippingaddressline1 = str_replace("'", "", $shippingaddressline1);
        $shippingaddressline2 = $column[22];
        $shippingaddressline2 = str_replace("'", "", $shippingaddressline2);
        $shippingaddressline3 = $column[23];
        $shippingaddressline3 = str_replace("'", "", $shippingaddressline3);
        $shippingaddressregion = $column[24];
        $shippingaddresscity = $column[25];
        $shippingaddresscity = str_replace("'", "", $shippingaddresscity);
        $shippingaddresspostcode = $column[26];
        $shippingaddresscountry = $column[27];
        $shippingaddresscountrycode = $column[28];

        if(trim($channel) == "" && trim($firstname) == ""){
          $lastInsertOrderResult = mysqli_query($connect, "SELECT * FROM germantemporders ORDER BY id DESC LIMIT 1");
          $lastInsertOrderResultRow = mysqli_fetch_array($lastInsertOrderResult);

          if(trim($date) == ""){
            $date = $lastInsertOrderResultRow['date'];
          }

          if(trim($channel) == ""){
            $channel = $lastInsertOrderResultRow['channel'];
          }

          if(trim($firstname) == ""){
            $firstname = $lastInsertOrderResultRow['firstname'];
          }

          if(trim($email) == ""){
            $email = $lastInsertOrderResultRow['email'];
          }

          if(trim($currency) == ""){
            $currency = $lastInsertOrderResultRow['currency'];
          }

          if(trim($total) == ""){
            $total = $lastInsertOrderResultRow['total'];
          }

          if(trim($shippingaddressline1) == ""){
            $shippingaddressline1 = $lastInsertOrderResultRow['shippingaddressline1'];
          }

          if(trim($shippingaddressline2) == ""){
            $shippingaddressline2 = $lastInsertOrderResultRow['shippingaddressline2'];
          }

          if(trim($shippingaddressline3) == ""){
            $shippingaddressline3 = $lastInsertOrderResultRow['shippingaddressline3'];
          }

          if(trim($shippingaddressregion) == ""){
            $shippingaddressregion = $lastInsertOrderResultRow['shippingaddressregion'];
          }
          
          if(trim($shippingaddresscity) == ""){
            $shippingaddresscity = $lastInsertOrderResultRow['shippingaddresscity'];
          }

          if(trim($shippingaddresspostcode) == ""){
            $shippingaddresspostcode = $lastInsertOrderResultRow['shippingaddresspostcode'];
          }

          if(trim($shippingaddresscountry) == ""){
            $shippingaddresscountry = $lastInsertOrderResultRow['shippingaddresscountry'];
          }

          if(trim($shippingaddresscountrycode) == ""){
            $shippingaddresscountrycode = $lastInsertOrderResultRow['shippingaddresscountrycode'];
          }

          if(trim($zenstoresOrderTotal) == ""){
            $zenstoresOrderTotal = $lastInsertOrderResultRow['zenstoresOrderTotal'];
          }
        }
      }else if($csvtype=="bol"){

        $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            if(in_array($_FILES['file']['type'],$mimes)){
               if(is_uploaded_file($_FILES['file']['tmp_name'])){
                   $csvFile = fopen($_FILES['file']['tmp_name'],'r');
                    fgetcsv($csvFile);
                    fgetcsv($csvFile);
                    fgetcsv($csvFile);
                  while (($line = fgetcsv($csvFile)) != false ){
                        $orderID = $line[0];
                        $date = $line[28];
                        $firstname = $line[2];
                        $lastname = $line[3];
                        $telephone = $line[34];
                        $email = $line[33];
                        $ordertotal = $line[27];
                        $name = $line[25];
                        $sku = $line[24];
                        $quantity = $line[26];
                        $shippingaddresscompany = $line[4];
                        $shippingaddressline1 = $line[5].$line[6] ;
                        $shippingaddressline2 = $line[7];
                        $shippingaddressline3 = $line[8];
                        $shippingaddresscity = $line[10];
                        $shippingaddresspostcode = $line[9];
                        $shippingaddresscountry = $line[12] ;

                        $sql = "INSERT INTO netherlandtemporders(orderID,date,firstname,lastname,telephone,email,ordertotal,name,sku,quantity,shippingaddresscompany,shippingaddressline1,shippingaddressline2,shippingaddressline3,shippingaddresscity,shippingaddresspostcode,shippingaddresscountry) 
                        VALUES ('$orderID','$date','$firstname','$lastname','$telephone','$email','$ordertotal','$name','$sku','$quantity','$shippingaddresscompany','$shippingaddressline1','$shippingaddressline2','$shippingaddressline3','$shippingaddresscity','$shippingaddresspostcode','$shippingaddresscountry')";
                        mysqli_query($connect,$sql);

                  }
                  fclose($csvFile);
                  
               }else{
                echo "<script>
                        alert('something wrong')
                      </script>";
               } 
                
            } else {
                echo "<script>
                        alert('invilid file formet')
                      </script>";
            }

      }
      
      $status = 'Pending';
      $unit = 'unit2';

      if ($currency == "GBP") {

        $currency = "EUR";

        if ($ordertotal > 0) {

          $ordertotal = $ordertotal / $rate;

          $ordertotal = number_format($ordertotal, 2, '.', '');
        }

        if ($total > 0) {

          $total = $total / $rate;

          $total = number_format($total, 2, '.', '');
        }

        if($csvtype=="zenstores"){
          if ($zenstoresOrderTotal > 0) {

            $zenstoresOrderTotal = $zenstoresOrderTotal / $rate;
  
            $zenstoresOrderTotal = number_format($zenstoresOrderTotal, 2, '.', '');
          }
        }
      }


      //flags start
      $flags = getFlagsDE($sku, $quantity, $shippingaddressline2, $shippingaddresscountry, $connect);
      //Flags end

      if ($shippingaddresscountry != "Germany" && $shippingaddresscountry != "Deutschland") {
        $internationalOrders = $internationalOrders + 1;
      }
      
      // filtering disabled for international orders - 
      //if ($shippingaddresscountry == "Germany"  || $shippingaddresscountry == "Deutschland") {
      if ($shippingaddresscountry != "United Kingdom") {
        $sql = "INSERT into germantemporders (orderID, status, date, channel, firstname, email, currency, ordertotal, name, sku, quantity, flags, shippingservice, shippingaddressline1, shippingaddressline2, shippingaddressline3, shippingaddressregion, shippingaddresscity, shippingaddresspostcode, shippingaddresscountry, shippingaddresscountrycode, booking, csvdate, unit, total, zenstoresOrderTotal) values ('" . $orderID . "','" . $status . "','" . $date . "','" . $channel . "','" . $firstname . "','" . $email . "','" . $currency . "','" . $ordertotal . "','" . $name . "','" . $sku . "','" . $quantity . "','" . $flags . "','" . $shippingservice . "','" . $shippingaddressline1 . "','" . $shippingaddressline2 . "','" . $shippingaddressline3 . "','" . $shippingaddressregion . "','" . $shippingaddresscity . "','" . $shippingaddresspostcode . "','" . $shippingaddresscountry . "','" . $shippingaddresscountrycode . "','" . $booking . "','" . $csvdate . "','" . $unit . "', '" . $total ."', '". $zenstoresOrderTotal ."')";

        if (mysqli_query($connect, $sql)) {
          $orderaddedrows = $orderaddedrows + 1;
        } else {
          $missedorder[$g] = $orderID;
          $g = $g + 1;
        }
      }
      //}
    }

    //merge start
    $ids_array = array();
    $idquery = "SELECT id FROM germantemporders";
    $idresult = mysqli_query($connect, $idquery);
    while ($idrow = mysqli_fetch_array($idresult)) {
      $ids_array[] = $idrow['id'];
    }
    $rowCount = count($ids_array);
    $merge = array();
    for ($i = 0; $i < $rowCount; $i++) {
      $orderresult = mysqli_query($connect, "SELECT * FROM germantemporders WHERE id='" . $ids_array[$i] . "'");
      $orderrow = mysqli_fetch_array($orderresult);
      if (in_array($orderrow['id'], $merge)) {
        continue;
      }
      $clientname = "Name : " . $orderrow["firstname"];
      $address = "";
      if (!empty($clientname)) {
        $address = $address . $clientname . "<br>";
      }
      if (!empty($orderrow["shippingaddresscompany"])) {
        $address = $address . $orderrow["shippingaddresscompany"] . "<br>";
      }
      if (!empty($orderrow["shippingaddressline1"])) {
        $address = $address . $orderrow["shippingaddressline1"] . "<br>";
      }
      if (!empty($orderrow["shippingaddressline2"])) {
        $address = $address . $orderrow["shippingaddressline2"] . "<br>";
      }
      if (!empty($orderrow["shippingaddressline3"])) {
        $address = $address . $orderrow["shippingaddressline3"] . "<br>";
      }
      if (!empty($orderrow["shippingaddressregion"])) {
        $address = $address . $orderrow["shippingaddressregion"] . "<br>";
      }
      if (!empty($orderrow["shippingaddresscity"])) {
        $address = $address . $orderrow["shippingaddresscity"] . "<br>";
      }
      if (!empty($orderrow["shippingaddresspostcode"])) {
        $address = $address . $orderrow["shippingaddresspostcode"] . "<br>";
      }
      if (!empty($orderrow["shippingaddresscountry"])) {
        $address = $address . $orderrow["shippingaddresscountry"] . "<br>";
      }
      for ($j = $i + 1; $j < $rowCount; $j++) {
        $mergeresult = mysqli_query($connect, "SELECT * FROM germantemporders WHERE id='" . $ids_array[$j] . "'");
        $mergerow = mysqli_fetch_array($mergeresult);
        $clientname = "Name : " . $mergerow["firstname"];
        $addressnew = "";
        if (!empty($clientname)) {
          $addressnew = $addressnew . $clientname . "<br>";
        }
        if (!empty($mergerow["shippingaddresscompany"])) {
          $addressnew = $addressnew . $mergerow["shippingaddresscompany"] . "<br>";
        }
        if (!empty($mergerow["shippingaddressline1"])) {
          $addressnew = $addressnew . $mergerow["shippingaddressline1"] . "<br>";
        }
        if (!empty($mergerow["shippingaddressline2"])) {
          $addressnew = $addressnew . $mergerow["shippingaddressline2"] . "<br>";
        }
        if (!empty($mergerow["shippingaddressline3"])) {
          $addressnew = $addressnew . $mergerow["shippingaddressline3"] . "<br>";
        }
        if (!empty($mergerow["shippingaddressregion"])) {
          $addressnew = $addressnew . $mergerow["shippingaddressregion"] . "<br>";
        }
        if (!empty($mergerow["shippingaddresscity"])) {
          $addressnew = $addressnew . $mergerow["shippingaddresscity"] . "<br>";
        }
        if (!empty($mergerow["shippingaddresspostcode"])) {
          $addressnew = $addressnew . $mergerow["shippingaddresspostcode"] . "<br>";
        }
        if (!empty($mergerow["shippingaddresscountry"])) {
          $addressnew = $addressnew . $mergerow["shippingaddresscountry"] . "<br>";
        }
        if ($addressnew == $address) {
          $mergeid = $mergerow['id'];
          $mergefrom = $orderrow["id"];
          $mergefromid = $orderrow["date"] . "-" . $orderrow["orderID"];
          if ($orderrow["flags"] == "Lampshade" || $mergerow["flags"] == "Lampshade") {
            $mergefromquery = "
                UPDATE germantemporders
                SET merge='Merged',
                flags= 'Lampshade'
                WHERE id='" . $mergefrom . "'";
            mysqli_query($connect, $mergefromquery);

            $mergequery = "
                UPDATE germantemporders
                SET merge='$mergefromid'
                WHERE id='" . $mergeid . "'";
            mysqli_query($connect, $mergequery);
          } else {
            $mergefromquery = "
                UPDATE germantemporders
                SET merge='Merged'
                WHERE id='" . $mergefrom . "'";
            mysqli_query($connect, $mergefromquery);

            $mergequery = "
                UPDATE germantemporders
                SET merge='$mergefromid'
                WHERE id='" . $mergeid . "'";
            mysqli_query($connect, $mergequery);
          }
          $mergeafterresult = mysqli_query($connect, "SELECT * FROM germantemporders WHERE id='" . $ids_array[$i] . "'");
          $mergeafterrow = mysqli_fetch_array($mergeafterresult);
          $mergeflag = $mergeafterrow["flags"];
          $mergeflagquery = "
                UPDATE germantemporders
                SET flags='$mergeflag'
                WHERE merge='" . $mergefromid . "'";
          mysqli_query($connect, $mergeflagquery);
          if (empty($merge)) {
            $merge = array($mergeid);
          } else {
            $v = count($merge);
            $merge[$v] = $mergeid;
          }
        }
      }
    }
    //merge end

    // check shipping included or not for zenstores orders - start
    if($csvtype=="zenstores"){
      $gettotalquery = "SELECT * FROM germantemporders WHERE merge='' ORDER BY ordertotal ASC, date ASC"; 
      $totalResult = mysqli_query($connect, $gettotalquery);
      $rowcount=mysqli_num_rows($totalResult);
      while($totalRow = mysqli_fetch_array($totalResult))
      {
        if($totalRow["zenstoresOrderTotal"] > $totalRow["ordertotal"]){
          $newOrdTotal = $totalRow["zenstoresOrderTotal"];
          $newOrdTotalQuery = "UPDATE germantemporders SET ordertotal='$newOrdTotal', total='$newOrdTotal' WHERE id='".$totalRow["id"]."'";
          mysqli_query($connect, $newOrdTotalQuery);
        }
      }
    }
    // check shipping included or not for zenstores orders - end

    //mergetotal update
    $totalmergequery = "SELECT * FROM germantemporders WHERE merge='Merged' ORDER BY ordertotal ASC, date ASC";
    $totalresult = mysqli_query($connect, $totalmergequery);
    while ($totalrow = mysqli_fetch_array($totalresult)) {
      $mergeid = $totalrow["date"] . "-" . $totalrow["orderID"];
      $mergequery = "SELECT * FROM germantemporders WHERE merge='" . $mergeid . "' ORDER BY ordertotal ASC, date ASC";
      $mergeresult = mysqli_query($connect, $mergequery);
      $mergetotal = $totalrow["ordertotal"];
      while ($mergerow = mysqli_fetch_array($mergeresult)) {
        $mergetotal = $mergetotal + $mergerow["ordertotal"];
      }

      if($csvtype=="zenstores"){
        if($totalrow["zenstoresOrderTotal"] > $mergetotal){
          $shippingCost = $totalrow["zenstoresOrderTotal"] - $mergetotal;

          $mergetotal = $totalrow["zenstoresOrderTotal"];

          $newOrderTotal = $totalrow["ordertotal"] + $shippingCost;
          $newOrderTotalQuery = "  
              UPDATE germantemporders   
              SET ordertotal='$newOrderTotal'
              WHERE id='".$totalrow["id"]."'";
          mysqli_query($con, $newOrderTotalQuery);
        }
      }

      $mergetotalquery = "
                UPDATE germantemporders
                SET total='$mergetotal'
                WHERE merge='" . $mergeid . "'";
      mysqli_query($connect, $mergetotalquery);
      $totalid = $totalrow["id"];
      $totalquery = "
                UPDATE germantemporders
                SET total='$mergetotal'
                WHERE id='" . $totalid . "'";
      mysqli_query($connect, $totalquery);
    }
  }
  $message = $orderaddedrows . " out of " . $ordercsvrows . " orders are added successfully";
  if ($internationalOrders > 0) {
    $message .= '\nInternational Orders - ' . $internationalOrders;
  }
  if (!empty($missedorder)) {
    $message .= '\nPlease Check the below missing orders';
    for ($i = 0; $i < count($missedorder); $i++) {
      $message .= '\n' . $missedorder[$i];
    }
  }
  echo "<script type='text/javascript'> alert('$message'); document.location.href='index.php'; </script>";
}
