<?php
  

    $con = mysqli_connect("localhost","root","","demofinal");
    if (mysqli_connect_errno()) {
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $downloadcsvtype = $_POST["downloadcsvtype"];
    $orderNumIdsArray = explode(',', $_POST["list_check"]);

    $orderNumIdsCount = count($orderNumIdsArray);

    if($orderNumIdsCount >= 0){
        $delimiter = ",";
        $filename = $downloadcsvtype."_" . date('Y-m-d') . ".csv";
        $f = fopen('php://memory', 'w');
        $i = 1;

        if($downloadcsvtype == "GLS" && $orderNumIdsCount > 0){
              foreach ($orderNumIdsArray as $key => $orderNumId) {
            $orderResult = mysqli_query($con, "SELECT * FROM germantemporders WHERE id='" . $orderNumId . "'");
            $orderRow = mysqli_fetch_array($orderResult);

            $postCode = $orderRow['shippingaddresspostcode'];
            if(strlen($postCode) < 5){
                $postCode = "0".$postCode;
            }

            if($orderRow['merge'] == "" or $orderRow['merge'] == "Merged"){
                $name = $orderRow['firstname'];
                $shippingaddressline1 = $orderRow['shippingaddressline1'];

                $shippingaddressline2 = $orderRow['shippingaddressline2'];
                if (strpos($shippingaddressline2, 'Packstation') !== false AND $shippingaddressline2 != "") {
                    $shippingaddressline1 .= " ".$shippingaddressline2;
                }

                if ((strpos($shippingaddressline2, 'Packstation') === false OR $shippingaddressline2 != "") AND strpos($shippingaddressline2, 'DE') === false AND $shippingaddressline2 != "") {
                    $name .= " - ".$shippingaddressline2;
                }
                
              

                $fullData = $i.";".$name.";".$orderRow['shippingaddressline1'].";;".$postCode.";".$orderRow['shippingaddresscity'].";".$orderRow['shippingaddresscountrycode'].";".$orderRow['email'].";1.9";

                $fullData = str_replace("Ä","AE",$fullData);
                $fullData = str_replace("ä","ae",$fullData);
                $fullData = str_replace("Ö","OE",$fullData);
                $fullData = str_replace("ö","oe",$fullData);
                $fullData = str_replace("Ü","UE",$fullData);
                $fullData = str_replace("ü","ue",$fullData);
                $fullData = str_replace("ß","ss",$fullData);

                $lineData = array($fullData); 
                fputcsv($f, $lineData, $delimiter); 

                $i++;
            }
        }

        }

        
        else if($downloadcsvtype == "International" && $orderNumIdsCount > 0){

           foreach ($orderNumIdsArray as $key => $orderNumId) {
            $orderResult = mysqli_query($con, "SELECT * FROM germantemporders WHERE id='" . $orderNumId . "'");
            $orderRow = mysqli_fetch_array($orderResult);

            $postCode = $orderRow['shippingaddresspostcode'];
            

            if($orderRow['merge'] == "" or $orderRow['merge'] == "Merged"){
                $name = $orderRow['firstname'];
                $shippingaddressline1 = $orderRow['shippingaddressline1'];

                $shippingaddressline2 = $orderRow['shippingaddressline2'];
                if (strpos($shippingaddressline2, 'Packstation') !== false AND $shippingaddressline2 != "") {
                    $shippingaddressline1 .= " ".$shippingaddressline2;
                }

                if ((strpos($shippingaddressline2, 'Packstation') === false OR $shippingaddressline2 != "") AND strpos($shippingaddressline2, 'DE') === false AND $shippingaddressline2 != "") {
                    $name .= " - ".$shippingaddressline2;
                }
                $lineData = array($i.";".$name.";".$orderRow['shippingaddressline2'].";".$orderRow['shippingaddressline1'].";".$postCode.";".$orderRow['shippingaddresscity'].";".$orderRow['shippingaddresscountrycode'].";".$orderRow['telephone'].";".$orderRow['email'].";1.9"); 
                fputcsv($f, $lineData, $delimiter); 

                $i++;
            }
        }

        }
        else if($downloadcsvtype == "Stamp" && $orderNumIdsCount > 0){
                  $fields = array('An:', 'First name', 'Last name', 'Shipping address line1', 'Shipping address post code', 'Shipping address city', 'Shipping address country');
        fputcsv($f, $fields, $delimiter); 
        
        // Output each row of the data, format line as csv and write to file pointer 
        foreach ($orderNumIdsArray as $key => $orderNumId) {
            $orderResult = mysqli_query($con, "SELECT * FROM germantemporders WHERE id='" . $orderNumId . "'");
            $orderRow = mysqli_fetch_array($orderResult);

            $postCode = $orderRow['shippingaddresspostcode'];
            if(strlen($postCode) < 5){
                $postCode = "0".$postCode;
            }

            if($orderRow['merge'] == "" or $orderRow['merge'] == "Merged"){
                $name = $orderRow['firstname'];
                $shippingaddressline1 = $orderRow['shippingaddressline1'];

                $shippingaddressline2 = $orderRow['shippingaddressline2'];
                if (strpos($shippingaddressline2, 'Packstation') !== false AND $shippingaddressline2 != "") {
                    $shippingaddressline1 .= " ".$shippingaddressline2;
                }

                if ((strpos($shippingaddressline2, 'Packstation') === false OR $shippingaddressline2 != "") AND strpos($shippingaddressline2, 'DE') === false AND $shippingaddressline2 != "") {
                    $name .= " - ".$shippingaddressline2;
                }

                $lineData = array("An:", $name, $orderRow['lastname'], $shippingaddressline1, $postCode, $orderRow['shippingaddresscity'], $orderRow['shippingaddresscountry']); 
                fputcsv($f, $lineData, $delimiter); 
            }
        }

        }
        
    }
    fseek($f, 0);
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
    fpassthru($f);
    exit;
?>