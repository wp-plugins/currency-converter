<?php

function cc_print_thecurrency_list($currency_code, $currency_list)
{


	$currency_name ="";
	foreach($currency_list as $k => $v)
	{
		$check_value = "";
		if ($currency_code == $k){
	   		$check_value = " SELECTED ";
			$country_name = $v['country_name'];
			$country_code = $v['country_code'];
			$currency_name = $v['currency_name'];
			$selected_values = $v;
		}

		$country_code = $v['country_code'];
		$country_name = $v['country_name'];
		$currency_name = $v['currency_name'];
		$output_string = '<option value="' . $k . '" ';
		$output_string .= $check_value . '>';
		$output_string .= $currency_name . '</option>';
		echo $output_string;
		echo "\n";

	}

}


// This function print for selector clock size list

function cc_print_thelength_list($size){
	 $size_list = array("short","medium");

	 echo "\n";
	foreach($size_list as $isize)
	{
		$check_value = "";
		if ($isize == $size)
	   		$check_value = ' SELECTED ';
		echo '<option value="'.$isize.'" '.$check_value .'>'.UCWords($isize).'</option>';
		echo "\n";
	}
}


// This function print for selector clock color list

function cc_print_layout_list($type){

	 $type_list =array(
	       "horizontal" => "Horizontal",
	       "vertical" => "Vertical");

	 echo "\n";
	foreach($type_list as $key=>$ttype)
	{
		$check_value = "";
		if ($type == $key)
	   		$check_value = ' SELECTED ';

		echo '<option value="'.$key.'" style="background-color:'.$key .'" '.$check_value .'>'.$ttype.'</option>';
		echo "\n";
	}

}



?>