<?php  
include 'db_config.php';

 if(isset($_POST["order_id"]))  
 {  
      $output = '';  
      $query = "SELECT * FROM germantemporders WHERE id = '".$_POST["order_id"]."'";  
      $result = mysqli_query($connect, $query);  
      $output .= '  
      <div class="table-responsive">  
           <table class="table table-bordered">';  
      while($row = mysqli_fetch_array($result))  
      {  
           $output .= '  
                <tr>  
                     <td width="30%"><label>SKU</label></td>  
                     <td width="70%">'.$row["sku"].'</td>  
                </tr>  
                <tr>  
                     <td width="30%"><label>Channel</label></td>  
                     <td width="70%">'.$row["channel"].'</td>  
                </tr>   
           ';  
      }  
      $output .= '  
           </table>  
      </div>  
      ';  
      echo $output;  
 }  
 ?>