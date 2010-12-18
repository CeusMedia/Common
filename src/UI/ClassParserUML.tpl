<?php
$code = "
<table style='border: 1px solid black' cellspacing='0' cellpadding='0' width='600'>
  <tr><th style='background: white; font-weight: bold; text-align: center'>".$this->_class_data['class']."</th></tr>
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='0' cellpadding='2'>
	  <colgroup><col width='170px'/><col width='430px'/></colgroup>";
if( $props['class']['desc'] )
	$code .= "
	  <tr><td colspan='2'>".$props['class']['desc']."</td></tr>";
if( $props['class']['package'] )
	$code .= "
	  <tr><td>Package</td><td>".$props['class']['package']."</td></tr>";
if( $props['class']['subpackage'] )
	$code .= "
	  <tr><td>Subpackage</td><td>".$props['class']['subpackage']."</td></tr>";
if( $props['class']['extends'] )
	$code .= "
	  <tr><td>Extends Class</td><td>".$props['class']['extends']."</td></tr>";
if( $props['class']['uses'] )
	$code .= "
	  <tr><td>Uses Classes</td><td>".$props['class']['uses']."</td></tr>";
if( $props['class']['imports'] )
	$code .= "
	  <tr><td>Imports Classes</td><td>".$props['class']['imports']."</td></tr>";
if( $props['class']['desc'] )
	$code .= "
	  <tr><td>Package</td><td>".$props['class']['package']."</td></tr>";
if( $props['class']['author'] )
	$code .= "
	  <tr><td>Author</td><td>".$props['class']['author']."</td></tr>";
if( $props['class']['since'] )
	$code .= "
	  <tr><td>Since</td><td>".$props['class']['since']."</td></tr>";
if( $props['class']['version'] )
	$code .= "
	  <tr><td>Version</td><td>".$props['class']['version']."</td></tr>";
if( $props['class']['todo'] )
	$code .= "
	  <tr><td>Todo</td><td>".$props['class']['todo']."</td></tr>";
$code .= "
    </table>
  </td></tr>  
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='0' cellpadding='2'>
	  <colgroup><col width='50px'/><col width='120px'/><col width='430px'></colgroup>
	  ".$vars."
	</table>
  </td></tr>  
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='0' cellpadding='2'>
	  <colgroup><col width='50px'/><col width='120px'/><col width='430px'></colgroup>
	  ".$methods."
	</table>
  </td></tr>  
</table>";
?>