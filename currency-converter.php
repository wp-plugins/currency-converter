<?php
/*
Plugin Name: Currency Converter
Description: Currency Converter for any currency in the world. You can choose the default currency for the converter.
Author: fx-rate.net
Version: 2.0
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
	   	'currency_code'=>'',
	   	'currency_name'=>'',
	   	'title'=>'Euro',
           	'country_code' => '',
   		'layout' => 'vertical',
           	'length' => 'medium',
           	'width' => '150',
           	'default_amount' => '100',
           	'default_from' => 'USD',
           	'default_to' => 'EUR',
           	'text_color' => '#000000',
           	'border_color' => '#BBBBBB',
           	'background_color' => '#FFFFFF',
           	'transparentflag'=>'0'
	   );
	}

	if ( $_POST['currency-converter-submit'] ) {
	     $options_flag=1;
	      $currency_code = strip_tags(stripslashes($_POST['currency-converter-currency-code']));
              $newoptions['currency_code'] = $currency_code;
	      $newoptions['currency_name'] = $currency_list[$currency_code]['currency_name'];
	      $newoptions['country_code'] = $currency_list[$currency_code]['country_code'];
              $newoptions['layout'] = strip_tags(stripslashes($_POST['currency-converter-layout']));
              $newoptions['title'] = $newoptions['currency_name'] ;
              $newoptions['length'] = strip_tags(stripslashes($_POST['currency-converter-length']));
              $newoptions['width'] = strip_tags(stripslashes($_POST['currency-converter-width']));
              $newoptions['default_amount'] = strip_tags(stripslashes($_POST['currency-converter-default-amount']));
              $newoptions['default_from'] = strip_tags(stripslashes($_POST['currency-converter-default-from']));
              $newoptions['default_to'] = strip_tags(stripslashes($_POST['currency-converter-default-to']));
              $newoptions['text_color'] = strip_tags(stripslashes($_POST['currency-converter-text-color']));
              $newoptions['border_color'] = strip_tags(stripslashes($_POST['currency-converter-border-color']));
              $newoptions['background_color'] = strip_tags(stripslashes($_POST['currency-converter-background-color']));
              $newoptions['transparentflag'] = strip_tags(stripslashes($_POST['currency-converter-transparent-flag']));
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
      	$layout = htmlspecialchars($options['layout'], ENT_QUOTES);
      	$width = htmlspecialchars($options['width'], ENT_QUOTES);
      	$default_amount = htmlspecialchars($options['default_amount'], ENT_QUOTES);
      	$default_from = htmlspecialchars($options['default_from'], ENT_QUOTES);
      	$default_to = htmlspecialchars($options['default_to'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);

      	echo '<ul><li style="text-align:center;list-style: none;"><label for="currency-converter-title">Currency Converter<br> by <a href="http://fx-rate.net">fx-rate.net</a></label></li>';

       	// Get currency, length and label type 


       	echo '<li style="list-style: none;"><label for="currency-converter-currency-code">Currency:'.
               '<select id="currency-converter-currency-code" name="currency-converter-currency-code" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	cc_print_thecurrency_list($currency_code, $currency_list);
      	echo '</select></label></li>';

      	// Set layout type
      	echo '<li style="list-style: none;"><label for="currency-converter-label-type">'.'Layout:&nbsp;&nbsp;';
       	echo '<select id="currency-converter-layout" name="currency-converter-layout"  style="width:120px" >';
      	cc_print_layout_list($layout);
      	echo '</select></label>';
      	echo '</li>';

	if(empty($currency_code) && $layout != "horizontal"){
      	// Set Length
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="currency-converter-length">'.'Length: &nbsp;'.
         '<select id="currency-converter-length" name="currency-converter-length"  style="width:75px">';
      	cc_print_thelength_list($length);
      	echo '</select></label></li>';
	}

      	// Set Width
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="currency-converter-width">'.'Width: &nbsp;&nbsp;&nbsp;'.
         '<select id="currency-converter-width" name="currency-converter-width"  style="width:75px">';
      	cc_print_thewidth_list($width);
      	echo '</select></label></li>';

      	// Set Default Amount
	echo "\n";
      	echo '<li style="list-style: none;text-align:bottom"><label for="currency-converter-default-amount">'.'Default Amount: &nbsp;&nbsp;&nbsp;'.
         '<input id="currency-converter-default-amount" name="currency-converter-default-amount"  style="width:40px;" value="' .$default_amount .'">';
      	echo '</input></label></li>';

      	// Set Default To Currency
	if(empty($currency_code)){
       	echo '<li style="list-style: none;"><label for="currency-converter-default-to">To Currency: &nbsp;&nbsp;&nbsp;'.
               '<select id="currency-converter-default-to" name="currency-converter-default-to" style="width:125px" >';
      	echo '<OPTION value=""></option>';
     	cc_print_thecurrency_list($default_to, $currency_list);
      	echo '</select></label></li>';
	}
	else{
		echo '<label for="currency-converter-default-to">';
                echo '<input type="hidden" id="currency-converter-default-to" name="currency-converter-default-to" value="EUR"></input>';
		echo '</label>';
	}

      	// Set Default From Currency
       	echo '<li style="list-style: none;"><label for="currency-converter-default-from">From Currency:'.
               '<select id="currency-converter-default-from" name="currency-converter-default-from" style="width:125px">';
      	echo '<OPTION value=""></option>';
     	cc_print_thecurrency_list($default_from, $currency_list);
      	echo '</select></label></li>';


      	// Set Text Widget color
      	echo '<li style="list-style: none;"><label for="currency-converter-text-color">'.'Text Color: &nbsp;&nbsp;&nbsp;&nbsp;';
       	echo '<select id="currency-converter-text-color" name="currency-converter-text-color"  style="width:95px" >';
      	cc_print_textcolor_list($text_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Border Widget color
      	echo '<li style="list-style: none;"><label for="currency-converter-border-color">'.'Header Color:&nbsp;';
       	echo '<select id="currency-converter-border-color" name="currency-converter-border-color"  style="width:95px" >';
      	cc_print_bordercolor_list($border_color);
      	echo '</select></label>';
      	echo '</li>';

      	// Set Background Widget color
      	echo '<li style="list-style: none;"><label for="currency-converter-background-color">'.'Background Color:&nbsp;';
       	echo '<select id="currency-converter-background-color" name="currency-converter-background-color"  style="width:95px" >';
      	cc_print_backgroundcolor_list($background_color);
      	echo '</select></label>';
      	echo '</li>';


	//   Transparent option

	$transparent_checked = "";
	if ($transparentflag =="1")
	   $transparent_checked = "CHECKED";
	echo "\n";
        echo '<li style="list-style: none;"><label for="currency-converter-transparent-flag"> Transparent: 
	<input type="checkbox" id="currency-converter-transparent-flag" name="currency-converter-transparent-flag" value=1 '.$transparent_checked.' /> 
	</label></li>';


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
      	$layout = htmlspecialchars($options['layout'], ENT_QUOTES);
      	$length = htmlspecialchars($options['length'], ENT_QUOTES);
      	$width = htmlspecialchars($options['width'], ENT_QUOTES);
      	$default_amount = htmlspecialchars($options['default_amount'], ENT_QUOTES);
      	$default_from = htmlspecialchars($options['default_from'], ENT_QUOTES);
      	$default_to = htmlspecialchars($options['default_to'], ENT_QUOTES);
      	$text_color = htmlspecialchars($options['text_color'], ENT_QUOTES);
      	$border_color = htmlspecialchars($options['border_color'], ENT_QUOTES);
      	$background_color = htmlspecialchars($options['background_color'], ENT_QUOTES);
      	$transparentflag = htmlspecialchars($options['transparentflag'], ENT_QUOTES);

	if($transparentflag == "1"){
  	     $background_color ="";
  	     $border_color ="";
	}

	if($currency_code)
		$length = "medium";

	$text_color = str_replace("#","",$text_color);

	echo $before_widget; 


	// Output title
	echo $before_title . $title . $after_title; 
	

	// Output calculator

	$widget_call_string = 'http://fx-rate.net/wp_converter.php?';
	if($currency_code) $widget_call_string .= 'currency='.$currency_code ."&";
	$widget_call_string .="size=". $length;
	$widget_call_string .="&layout=". $layout;
	$widget_call_string .="&amount=". $default_amount;
	$widget_call_string .="&tcolor=". $text_color;
	$widget_call_string .="&default_pair=". $default_from . "/" . $default_to;

	$country_code = strtolower($country_code);
	$image_url = 'http://fx-rate.net/images/countries/'.$country_code.'.png';

       $calc_label= strtoupper(substr($layout,0,1));
       if($length == "short") $calc_label .= "S";


	if($currency_code){
		$target_url= "http://fx-rate.net/$currency_code/";
		$flag_string = '<img style="margin:0;padding:0;border:0;" src="http://fx-rate.net/images/countries/'.$country_code.'.png" border=0 >&nbsp;<b>';
		$flag_string2 = '</b>';
		$title = UCWords($currency_name) . " Converter";
		$calc_label .=  "1";
	}
	else{
		$target_url= "http://fx-rate.net/";
		$title = "Currency Converter";
	}
	
	$tsize=12;
	if($layout == "vertical" && $length =="short") $tsize = 10;

#
#
#


	echo '<!-Currency Converter widget - HTML code - fx-rates.net -->
<div  style="width:'.$width.'px; background-color:'.$background_color.';border:2px solid #888;text-align:center;margin: 0px; padding: 0px;margin-top:10px!important">';

	echo '<div style="margin: 0px; padding: 0px;text-align:center;align:center;background-color:'.$border_color. ';border-bottom:1px solid #888;width:100%">
	     <a class="'.$calc_label.'label" 
	     style="font-size:'.$tsize.'px!important;line-height:16px!important;font-family:arial;text-weight:bold;margin-bottom:6px;text-decoration:none;color:#'.$text_color.'" href="'.$target_url.'">';

	echo $flag_string;
	
	echo $title.$flag_string2.'</a></div>';

     	echo'<script type="text/javascript" src="'.$widget_call_string.'"></script></div><!-end of code-->';







	echo $after_widget;


    }
  
    register_sidebar_widget('Currency Converter', 'currency_converter');
    register_widget_control('Currency Converter', 'currency_converter_control', 245, 300);


}


add_action('plugins_loaded', 'currency_converter_init');




include("functions.php");


?>