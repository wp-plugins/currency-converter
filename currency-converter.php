<?php
/*
Plugin Name: Currency Converter
Description: Currency Converter for any currency in the world. You can choose the default currency for the converter.
Author: fx-rate.net
Version: 1.0
Author URI: http://fx-rate.net
Plugin URI: http://fx-rate.net/wordpress-currency-converter-plugin/
*/



function currency_converter_init() 
{


     if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
    	   return; 

    function currency_converter_control() 
    {

        $newoptions = get_option('currency_converter');
    	$options = $newoptions;
	$options_flag=0;

      	if(empty($currency_list)){
		$file_location = dirname(__FILE__)."/currencies.ser"; 
		if ($fd = fopen($file_location,'r')){
	   	   $currency_list_ser = fread($fd,filesize($file_location));
	   	   fclose($fd);
		}
		$currency_list = array();
		$currency_list = unserialize($currency_list_ser);
        }


    	if ( empty($newoptions) )
	{
	   $options_flag=1;
      	   $newoptions = array(
	   	'currency_code'=>'EUR',
	   	'currency_name'=>'Euro',
	   	'title'=>'Euro',
           	'country_code' => 'EU',
           	'label_type' => 'currency_name',
           	'length' => 'short',
	   );
	}

	if ( $_POST['currency-converter-submit'] ) {
	     $options_flag=1;
	      $currency_code = strip_tags(stripslashes($_POST['currency-converter-currency-code']));
              $newoptions['currency_code'] = $currency_code;
	      $newoptions['currency_name'] = $currency_list[$currency_code]['currency_name'];
	      $newoptions['country_code'] = $currency_list[$currency_code]['country_code'];
              $newoptions['title'] = $newoptions['currency_name'] ;
              $newoptions['length'] = strip_tags(stripslashes($_POST['currency-converter-length']));
              $newoptions['label_type'] = strip_tags(stripslashes($_POST['currency-converter-label-type']));
        }


      	if ( $options_flag ==1 ) {
              $options = $newoptions;
              update_option('currency_converter', $options);
      	}


      	// Extract value from vars
      	$currency_code = htmlspecialchars($options['currency_code'], ENT_QUOTES);
	$currency_name = htmlspecialchars($options['currency_name'], ENT_QUOTES);
	$title = $currency_name;
      	$country_code = htmlspecialchars($options['country_code'], ENT_QUOTES);
      	$length = htmlspecialchars($options['length'], ENT_QUOTES);
      	$label_type = htmlspecialchars($options['label_type'], ENT_QUOTES);

      	echo '<ul><li style="text-align:center;list-style: none;"><label for="currency-converter-title">Currency Converter<br> by <a href="http://fx-rate.net">fx-rate.net</a></label></li>';

       	// Get currency, length and label type 


       	echo '<li style="list-style: none;"><label for="currency-converter-currency-code">Currency:'.
               '<select id="currency-converter-currency_code" name="currency-converter-currency-code" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	cc_print_thecurrency_list($currency_code, $currency_list);
      	echo '</select></label></li>';

      	// Set layout type
      	echo '<li style="list-style: none;"><label for="currency-converter-label-type">'.'Layout:&nbsp;';
       	echo '<select id="currency-converter-label-type" name="currency-converter-label-type"  style="width:120px" >';
      	cc_print_layout_list($label_type);
      	echo '</select></label>';
      	echo '</li>';


      	// Set Size
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="currency-converter-length">'.'Size: &nbsp;'.
         '<select id="currency-converter-length" name="currency-converter-length"  style="width:75px">';
      	cc_print_thelength_list($length);
      	echo '</select></label></li>';


      	// Hidden "OK" button
      	echo '<label for="currency-converter-submit">';
      	echo '<input id="currency-converter-submit" name="currency-converter-submit" type="hidden" value="Ok" />';
      	echo '</label>';

        echo '<label for="currency-converter-title"> <input type="hidden" id="currency-converter-title" name="currency-converter-title" value="'.$title.'" /> </label>';

	echo '</ul>';


    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    //	OUTPUT TABLE WIDGET
    //
    /////////////////////////////////////////////////////////////////////////////////////////////////////

     function currency_converter($args) 
     {

	// Get values 
      	extract($args);

      	$options = get_option('currency_converter');


      	// Extract value from vars
      	$currency_code = htmlspecialchars($options['currency_code'], ENT_QUOTES);
	$currency_name = htmlspecialchars($options['currency_name'], ENT_QUOTES);
	$title = $currency_name;
      	$country_code = htmlspecialchars($options['country_code'], ENT_QUOTES);
      	$length = htmlspecialchars($options['length'], ENT_QUOTES);
      	$label_type = htmlspecialchars($options['label_type'], ENT_QUOTES);

	echo $before_widget; 


	// Output title
	echo $before_title . $title . $after_title; 


	// Output calculator

	$country_code = strtolower($country_code);
	$image_url = 'http://fx-rate.net/images/countries/'.$country_code.'.png';

	if($currency_code)
		$target_url= "http://fx-rate.net/$currency_code/";
	else
		$target_url= "http://fx-rate.net/";

	
	$widget_call_string = 'http://fx-rate.net/wp_convert.php?';
	if($currency_code) $widget_call_string .= 'currency='.$currency_code ."&";
	$widget_call_string .="size=". $length;
	$widget_call_string .="&layout=". $label_type;

	if($currency_code){
		$flag_string = '<img style="margin:0;padding:0;border:0;" src="http://fx-rate.net/images/countries/'.$country_code.'.png" border=0 >&nbsp;<b>';
		$flag_string2 = '</b>';
	}


	if($currency_name)
		$title = UCWords($currency_name) . " Converter";
	else
		$title = "Currency Converter";


	if($label_type == "horizontal"){
	      if($length=="short"){
		$width=474;
		$class="HSlabel";
	      }
	      else{
		$width=390;
		$class="H1label";
	     }
	}
	else
	{
	      if($length=="short"){
		$width=104; 
		$class="VSlabel";
	      }
	      else{
		$width=114;
		$class="V1label";
	      }
	}

$width2 = $width-2;

#
#
#


	echo '<!-Currency Converter widget - HTML code - fx-rates.net --><div  style="width:'.$width.'px; background-color:#fff;border:2px solid #888;text-align:center">';

	echo '<div style="margin: 0px 0px 0px 0px; padding: 3px 0px 2px 0px;text-align:center;background-color:#ccc;border:1px solid #888;width:'.$width2.'">
	     <a class="'.$class.'" style="text-weight:bold;text-decoration:none;color:#000000;" href="'.$target_url.'">';

	echo $flag_string;
	
	echo 'Currency Converter'.$flag_string2.'</a></div>';

     	echo'<script type="text/javascript" src="'.$widget_call_string.'"></script></div><!-end of code-->';







	echo $after_widget;


    }
  
    register_sidebar_widget('Currency Converter', 'currency_converter');
    register_widget_control('Currency Converter', 'currency_converter_control', 245, 300);


}


add_action('plugins_loaded', 'currency_converter_init');




include("functions.php");


?>