<?php
    function getFlagsDE($childSKUs, $childQty, $childShipAddre, $childShipAddreCountry, $con)
    {
        // here $childShipAddre determine customer shipping address line 2
        // here $childShipAddreCountry determine customer shipping address country
        //firtsly remove IDE in sku then check
        // childSKUs = childSKUs.split("-")[0];
        
        $childSKUFirst = substr($childSKUs, 0, 1);
        $childSKUSecond = substr($childSKUs, 0, 2);
        $childSKUThird = substr($childSKUs, 0, 3);
        $childSKUFour = substr($childSKUs, 0, 4);
        $childSKUFive = substr($childSKUs, 0, 5);
        $childSKUSeven = substr($childSKUs, 0, 7);
        $childSKUEight = substr($childSKUs, 0, 8);
        $childSKUNine = substr($childSKUs, 0, 9);

        // for pack into qty
        $total = strlen($childSKUs);
        $lastTwo = substr($childSKUs, $total - 2, 2);
        
        if ($lastTwo == "PK") {
        	$pkCount = substr($childSKUs, $total - 3, 1);
        
        	if ($pkCount == "A") {
                	$pkCount = 10;
        	} else if ($pkCount == "C") {
                	$pkCount = 20;
        	} else if ($pkCount == "E") {
                	$pkCount = 50;
        	} else if ($pkCount == "F") {
                	$pkCount = 100;
        	}
        } else if ($lastTwo != "PK") {
            	$pkCount = 1;
        }
        // total length is qty into pk count
        $totalLength = $childQty * $pkCount;
        
        $whichShipping = "";
        
        if ($childSKUFive == "12SIP") {
            $whichShipping = "Duisburg GLS";
        }
        
        if ($childSKUFive == "12RPI") {
            $whichShipping = "Trossingen GLS";
        }
        
        if ($childSKUFive == "12SAN") {
            if ($childQty >= 5) {
            $whichShipping = "Trossingen GLS";
            } else if ($childQty < 5) {
            $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUFour == "12IP") {
            if (substr($childSKUs, 4, 2) == "67") {
            	if ((int)substr($childSKUs, 6, 3) == 36) {
                	if ($childQty >= 2) {
                		$whichShipping = "Trossingen GLS";
                	} else if ($childQty < 2) {
               	 		$whichShipping = "Trossingen Stamps";
                	}
            	} else if ((int)substr($childSKUs, 6, 3) >= 50) {
                	$whichShipping = "Trossingen GLS";
            	} else if ((int)substr($childSKUs, 6, 3) < 50) {
                	$whichShipping = "Trossingen Stamps";
            	}
            } else if (substr($childSKUs, 4, 2) == "20") {
            	if ((int)substr($childSKUs, 6, 3) >= 80) {
                	$whichShipping = "Trossingen GLS";
            	} else if ((int)substr($childSKUs, 6, 3) < 80) {
                	if ($childQty >= 2) {
                		$whichShipping = "Trossingen GLS";
                	} else if ($childQty < 2) {
               	 		$whichShipping = "Trossingen Stamps";
                	}
           	}
            }
        }
        
        if ($childSKUFour == "24IP") {
            if (substr($childSKUs, 4, 2) == "67") {
            if ((int)substr($childSKUs, 6, 3) >= 50) {
                $whichShipping = "Trossingen GLS";
            } else if ((int)substr($childSKUs, 6, 3) < 50) {
                $whichShipping = "Trossingen Stamps";
            }
            } else if (substr($childSKUs, 4, 2) == "20") {
            if ((int)substr($childSKUs, 6, 3) >= 100) {
                $whichShipping = "Trossingen GLS";
            } else if ((int)substr($childSKUs, 6, 3) < 100) {
                $whichShipping = "Trossingen Stamps";
        
                if(substr($childSKUs, 6, 2) == "72"){
                if ($childQty == 1) {
                    $whichShipping = "Trossingen Stamps";
                } else if ($childQty > 1) {
                    $whichShipping = "Trossingen GLS";
                }
                }
            }
            }
        }
        
        if ($childSKUThird == "5IP") {
            if (substr($childSKUs, 3, 2) == "20") {
            if ((int)substr($childSKUs, 5, 2) == 50) {
                if ($childQty >= 2) {
                $whichShipping = "Trossingen GLS";
                } else if ($childQty < 2) {
                $whichShipping = "Trossingen Stamps";
                }
            }else if ((int)substr($childSKUs, 5, 3) >= 100) {
                $whichShipping = "Trossingen GLS";
            } else if ((int)substr($childSKUs, 5, 3) < 100) {
                if ($childQty >= 3) {
                $whichShipping = "Trossingen GLS";
                } else if ($childQty < 3) {
                $whichShipping = "Trossingen Stamps";
                }
            }
            }
        }
        
        if ($childSKUThird == "RPM") {
            $whichShipping = "Trossingen Stamps";
        }
        
        if ($childSKUThird == "SMD") {
            $whichShipping = "Trossingen Stamps";
        }
        
        if ($childSKUFour == "CCBC" || $childSKUFour == "CCBK" || $childSKUFour == "CCGN" || $childSKUFour == "12WC") {
            if ($childQty >= 10) {
                $whichShipping = "Trossingen GLS";
            } else if ($childQty < 10) {
                $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUSecond == "CL") {
            if ($totalLength >= 10) {
            $whichShipping = "Trossingen GLS";
            } else if ($totalLength < 10) {
            $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUFour == "CBSF") {
            if ($childQty >= 10) {
            $whichShipping = "Trossingen GLS";
            } else if ($childQty < 10) {
            $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUFour == "CRFF") {
            if ($childSKUSeven == "CRFF105") {
            if ($childQty == 1) {
                $whichShipping = "Duisburg Stamp";
            } else if ($childQty > 1) {
                $whichShipping = "Duisburg GLS";
            } else if ($childQty < 1) {
                $whichShipping = "Trossingen GLS";
            }
            } else if ($childSKUSeven == "CRFF115") {
            if ($childQty > 2) {
                $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 2) {
                $whichShipping = "Trossingen Stamp";
            }
            } else if ($childSKUSeven == "CRFF500") {
            $whichShipping = "Trossingen GLS";
            } else if ($childSKUNine == "CRFF140BM") {
            if ($childQty > 3) {
                $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 3) {
                $whichShipping = "Duisburg Stamp";
            }
            }
        }
        
        if ($childSKUSeven == "HLCH128") {
            if(substr($childSKUs, 7, 5) == "BM5PK" or substr($childSKUs, 7, 5) == "BMAPK"){
            $whichShipping = "Duisburg GLS";
            }else if ($childQty < 3) {
            $whichShipping = "Duisburg Stamp";
            } else if ($childQty >= 3) {
            $whichShipping = "Duisburg GLS";
            }
        }
        
        if ($childSKUFour == "CRSF") {
            if (strpos($childSKUs, '+') !== false) {
            if (substr($childSKUs, 4, 5)  == "100BM") {
                $whichShipping = "Trossingen GLS";
            } else if (substr($childSKUs, 4, 3)  == "100") {
                if ($childQty > 2) {
                $whichShipping = "Duisburg GLS";
                } else if ($childQty <= 2) {
                $whichShipping = "Duisburg Stamp";
                }
            } else if (substr($childSKUs, 4, 3)  == "200") {
                $whichShipping = "Trossingen GLS";
            } else if (substr($childSKUs, 4, 3)  == "120") {
                $whichShipping = "Duisburg GLS";
            }
            }else if (strpos($childSKUs, '+') === false) {
            if ($childQty > 2) {
                $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 2) {
                $whichShipping = "Trossingen Stamp";
            }
            }
        }
        
        if ($childSKUSecond == "LD" || $childSKUSecond == "IC") {
            if ($totalLength >= 10) {
                $whichShipping = "Trossingen GLS";
            } else if ($totalLength < 10) {
                $whichShipping = "Duisburg GLS";
            }
        
            if ($childSKUFour == "LDCW") {
                $whichShipping = "Trossingen GLS";
            }
        }

        if ($childSKUFour == "12DE" || $childSKUFour == "LSCY" || $childSKUFour == "LSSS" || $childSKUFour == "LSHM" || $childSKUFour == "LSFT" || $childSKUFour == "LSDO" || $childSKUFour == "LSRP" || $childSKUFour == "LDWW" || $childSKUFour == "LDCW" || $childSKUThird == "ENC"  || $childSKUThird == "PLW"  || $childSKUThird == "PLT" || $childSKUSecond == "LH" || $childSKUSecond == "WC") {
            $whichShipping = "Trossingen GLS";
        }
        
        if ($childSKUSecond == "LS" || $childSKUSecond == "WS" || $childSKUSecond == "PH" || $childSKUSecond == "PH" || $childSKUSecond == "PL") {
            $whichShipping = "Trossingen GLS";
        }
        
        if ($childSKUSecond == "IM") {
            $whichShipping = "Trossingen Stamps";
        }
        
        if ($childSKUSecond == "CG") {
            $whichShipping = "Duisburg Stamp";
        }
        
        if ($childSKUSecond == "WJ") {
            if ($childQty > 4) {
            $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 6) {
            $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUFour == "12BO") {
            if ($childQty > 6) {
            $whichShipping = "Duisburg GLS";
            } else if ($childQty <= 6) {
            $whichShipping = "Duisburg Stamp";
            }
        }
        
        if ($childSKUSeven == "12MIP20" || $childSKUSeven == "CRSF120") {
            $whichShipping = "Duisburg GLS";
        }
        
        if ($childSKUFour == "HLCH" || $childSKUFour == "HLLK" || $childSKUFour == "HLRK") {
            if ($childQty > 6) {
            $whichShipping = "Duisburg GLS";
            } else if ($childQty <= 6) {
            $whichShipping = "Duisburg Stamp";
            }
        }
        
        if ($childSKUFour == "HLSC" || $childSKUThird == "HLT") {
            $whichShipping = "Duisburg GLS";
        }
        
        if ($childSKUSecond == "CO" || $childSKUSecond == "HK") {
            $whichShipping = "Duisburg Stamp";
        }
        
        if (strpos($childSKUs, '+') !== false) {
            $whichShipping = "Trossingen GLS";
        }
        
        if ($childSKUThird == "CNP") {
            if ($childQty > 2) {
            $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 2) {
            $whichShipping = "Trossingen Stamps";
            }
        }
        
        if ($childSKUFour == "CMWH") {
            $whichShipping = "Trossingen Stamps";
        }
        
        if ($childSKUEight == "SCRN70BM") {
            if ($childQty > 5) {
                $whichShipping = "Trossingen GLS";
            } else if ($childQty <= 5) {
                $whichShipping = "Trossingen Stamps";
            }
        }

        if (strpos($childShipAddre, 'Packstation') !== false) {
            $whichShipping = "DHL";
        }

        if ($childShipAddreCountry != "Germany" && $childShipAddreCountry != "Deutschland") {
            $whichShipping = "Trossingen GLS";
        }
	
      	/*
        // application of returnQty function
        $returnQtyArray = returnQty($childSKUs,$con);
        $response = $returnQtyArray["responseCode"];

        if($response=="200"){
            // it is show minimum qty of combo products, and show real qty of single component
            $realQty = $returnQtyArray["quantity"];
        }else{
            $realQty = $returnQtyArray["error"];
        }

        if($realQty<=0){
            $whichShipping = "outofstock";
        }
        */
        
        $flags = "";
        if ($whichShipping == "DHL") {
            $flags = "German postage,DHL,Trossingen warehouse";
        } elseif ($whichShipping == "Trossingen Stamps") {
            $flags = "German postage,Trossingen stamps,Trossingen warehouse";
        } elseif ($whichShipping == "Trossingen GLS") {
            $flags = "German postage,Trossingen GLS,Trossingen warehouse";
        } elseif ($whichShipping == "Duisburg Stamp") {
            $flags = "German postage,Duisburg stamp, Duisburg warehouse";
        } elseif ($whichShipping == "Duisburg GLS") {
            $flags = "German postage,Duisburg GLS, Duisburg warehouse";
        } 
      /*elseif ($whichShipping == "outofstock") {
            $flags = "Book from UK";
        }*/
        
        return $flags;
    }

    //to fetch single data with condition
    function fetchsingleDataWithCondition($table,$field,$equalornot,$data,$whichfield,$con){
        $field_value = 'empty';
        $sql='SELECT * FROM `'.$table.'` WHERE `'.$field.'`'.$equalornot.'"'.$data.'"';
        $result=$con->query($sql);

        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc())
            {
                $field_value = $row[$whichfield];
            }
        }
        return $field_value;
    }

    //to fetch data with condition
    function fetchDataWithCondition($table,$field,$equalornot,$data,$limit,$con){
        $array = array();
        
        if($limit==0 or $limit==''){
            $sql='SELECT * FROM `'.$table.'` WHERE `'.$field.'`'.$equalornot.'"'.$data.'"';
        }else{
            $sql='SELECT * FROM `'.$table.'` WHERE `'.$field.'`'.$equalornot.'"'.$data.'" LIMIT '.$limit;
        }

        $result=$con->query($sql);

        if ($result->num_rows > 0) 
        {
            while($row = $result->fetch_assoc())
            {
                $array[] = $row;
            }
        }

        return $array;
    }

    // remove dash after text in single SKU
    function removeDashAfterTxt($sku){
        $divideSKU= explode('-', $sku);
        $sku=trim($divideSKU[0]);

        return $sku;
    }

    // remove PK in SKU
    function removePk($sku){
        $changed = true;
        $pknumber=substr($sku, -3, -2);
		
      	/*
        if(substr($sku,0,2)=="CL"){
            if($pknumber=="5" || $pknumber=="A" || $pknumber=="F"){
                $changed = false;
            }
        }
        */

        if(!$changed){
            $pknumber=1;
            $new_sku = $sku;
        }else{
            if($pknumber=="A"){
                $pknumber=10;
            }else if($pknumber=="B"){
                $pknumber=15;
            }else if($pknumber=="C"){
                $pknumber=20;
            }else if($pknumber=="D"){
                $pknumber=30;
            }else if($pknumber=="E"){
                $pknumber=50;
            }else if($pknumber=="F"){
                $pknumber=100;
            }else{
                $pknumber = $pknumber;
            }

            $new_sku = substr($sku, 0, -3);
        }

        return array($new_sku,$pknumber);
    }

    /*
        get quantity by sku in database
        This Function return two values as array
            first one - status (string 200, error)
            second one - quantity

        parameter 

        $sku - It's sku
        $con - db connection
    */
    function returnQty($sku,$con){
        $qty = array();
        $singleComboQty = array();
        $notOutofstock = true;

        $bool = true;
        $quantity = null;
        $error = null;
        $responseCode = "400";

        // added for german orders
        if(strpos($sku, "-IDE") === false){
    		$sku .= "-IDE";
        }

        $divideSKUByDash= explode('-', $sku);
        if(array_key_exists(1,$divideSKUByDash)){
            $wareHouse = trim($divideSKUByDash[1]);
        }else{
            $wareHouse = "UK";
        }

        $sku = $divideSKUByDash[0];

        if(substr($sku,0,3)=="ENC"){
            $encresult = fetchsingleDataWithCondition("comboproducts","sku","=",$sku,"originalsku",$con);
            if($encresult !== "empty"){
                $sku = $encresult;
            }else{
                $sku = "Wrong ENC";
            }
        }
        
        if($sku == "Wrong ENC"){
            $responseCode = "400";
            $error = "Wrong ENC. ENC not in database.";
        }else if(strpos($sku, "+") !== false){
            $skuArray = explode("+", $sku);

            foreach($skuArray as $index => $singlesku) {
                $singlesku = removeDashAfterTxt($singlesku);

                if(substr($singlesku, -2)=="PK"){
                    $removePK = removePk($singlesku);
                    $singlesku = $removePK[0];
                    $singleskuDivider = $removePK[1];
                }else{
                    $singlesku = $singlesku;
                    $singleskuDivider = 1;
                }

                $singleskuDivider = (int)$singleskuDivider;

                $new_qty = 0;

                $singleData_array = fetchDataWithCondition("products","SKU","=",$singlesku,0,$con);

                $count = count($singleData_array);

                if($count>0){
                    foreach ($singleData_array as $singleData) {
                    $outofstock = $singleData['outofstock'];

                    if($wareHouse == "IDE"){
                            $Quantityunit1 = (int)$singleData['germanInventory'];
                    }elseif($wareHouse == "CA"){
                            $Quantityunit1 = (int)$singleData['canada'];
                    }elseif($wareHouse == "IFR"){
                            $Quantityunit1 = (int)$singleData['france'];
                    }elseif($wareHouse == "NL"){
                            $Quantityunit1 = (int)$singleData['netherland'];
                    }else{
                            $Quantityunit1 = (int)$singleData['Quantity'] + (int)$singleData['unit1'];
                    }
                    
                    $new_qty = intdiv($Quantityunit1,$singleskuDivider);

                    $singleCombQty = $new_qty;

                    array_push($qty,$new_qty);

                    if($outofstock=="yes"){
                            $notOutofstock = false;
                    }
                    }
                    $bool = $bool * true;
                }else{
                    $bool = $bool * false;
                    $singleCombQty = "#N/A";
                }

                array_push($singleComboQty,array($singlesku,$singleCombQty));

                if($bool){
                    $responseCode = "200";
                }else{
                    $responseCode = "400";
                    $error = "One or more SKU did not find in Database.";
                }
            }
        }else{
            $sku = removeDashAfterTxt($sku);

            if(substr($sku, -2)=="PK"){
                $removePK = removePk($sku);
                $sku = $removePK[0];
                $skuDivider = $removePK[1];
            }else{
                $sku = $sku;
                $skuDivider = 1;
            }

            $singleData_array = fetchDataWithCondition("products","SKU","=",$sku,0,$con);

            $count = count($singleData_array);

            if($count>0){
                foreach ($singleData_array as $singleData) {
                    $outofstock = $singleData['outofstock'];
                    
                    if($wareHouse == "IDE"){
                    $Quantityunit1 = (int)$singleData['germanInventory'];
                    }elseif($wareHouse == "CA"){
                    $Quantityunit1 = (int)$singleData['canada'];
                    }elseif($wareHouse == "IFR"){
                    $Quantityunit1 = (int)$singleData['france'];
                    }elseif($wareHouse == "NL"){
                    $Quantityunit1 = (int)$singleData['netherland'];
                    }else{
                    $Quantityunit1 = (int)$singleData['Quantity'] + (int)$singleData['unit1'];
                    }

                    $new_qty = intdiv($Quantityunit1,$skuDivider);

                    array_push($qty,$new_qty);

                    if($outofstock=="yes"){
                    $notOutofstock = false;
                    }
                }
                $responseCode = "200";
            }else{
                $responseCode = "400";
                $error = "SKU did not find in Database.";
            }
        }

        if($notOutofstock){
            if(count($qty)>0){
                $quantity = min($qty);
                if($quantity<0){
                    $quantity = 0;
                }
            }
        }else if(!$notOutofstock){
            $quantity = 0;
        }

        return array("responseCode" => $responseCode,"quantity" => $quantity,"singleComboQty" => $singleComboQty, "error" => $error);
    }
?>