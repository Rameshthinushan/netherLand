<?php


    $con = mysqli_connect("localhost","root","","demofinal");
    if (mysqli_connect_errno()) {
        die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $orderNumIdsArray = $_POST["orders"];

    $orderNumIdsCount = count($orderNumIdsArray);

    if($orderNumIdsCount > 0){
        $delimiter = ","; 
        $filename = "gls-temp_" . date('Y-m-d') . ".csv"; 
        
        // Create a file pointer 
        $f = fopen('php://memory', 'w');
        
        $i = 1;
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
                
                /*
                    var A = sheetCSV.getRange("A"+i).getValue();
                    var AString = A.toString().replace("Ä", "AE").replace("ä", "ae")
                    .replace("Ö", "OE").replace("ö", "oe").replace("Ü", "UE").replace("ü", "ue")
                    .replace("ß", "ss");
                */
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