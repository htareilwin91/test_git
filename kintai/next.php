<?php
if( $_REQUEST["location"] )
{
   $location = $_REQUEST['location'];
 if($location=="Japan")
    date_default_timezone_set("Asia/Tokyo"); 
    echo "Japan Time Zone -> ".date('d-m-Y H:i:s'); //Returns IST 

}
?>