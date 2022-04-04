<?php


    $con = mysqli_connect("localhost","root","","demofinal");
    if (mysqli_connect_errno()) {
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $orderNumIdsArray = $_POST["orders"];

    $orderNumIdsCount = count($orderNumIdsArray);

    if($orderNumIdsCount > 0){
        $delimiter = ","; 
        $filename = "stamp-temp_" . date('Y-m-d') . ".csv"; 
        
        // Create a file pointer 
        $f = fopen('php://memory', 'w'); 

        // Set column headers
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

        // Move back to beginning of file 
        fseek($f, 0); 
        
        // Set headers to download file rather than displayed 
        header('Content-Type: text/csv'); 
        header('Content-Disposition: attachment; filename="' . $filename . '";'); 
        
        //output all remaining data on a file pointer 
        fpassthru($f);
    }else{
        echo "Please choose atleast one order to generate template. automatically close this tab after few seconds.";
        echo "<script>setTimeout(function(){
            window.top.close();
          }, 3000);</script>";
    }
    exit; 
?>