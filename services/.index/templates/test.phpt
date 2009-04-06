<?php
$cssLibPath	= "//css.ceusmedia.com/";
$jsLibPath	= "//js.ceusmedia.com/";

$list	= array();
foreach( $parameters as $parameter )
	$list[]	= UI_HTML_Elements::ListItem( $parameter['label'].$parameter['rules']."<br/>".$parameter['input'] );
$parameters	= UI_HTML_Elements::unorderedList( $list );

return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>'.$service.' @ '.$title.'</title>
    <script src="'.$jsLibPath.'jquery/1.2.3.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="'.$cssLibPath.'blueprint/reset.css"/>
    <link rel="stylesheet" type="text/css" href="'.$cssLibPath.'blueprint/typography.css"/>
    <link rel="stylesheet" type="text/css" href="'.$basePath.'.index/css/tool.css"/>
    <link rel="stylesheet" type="text/css" href="'.$basePath.'.index/css/services.test.css"/>
    <link rel="stylesheet" type="text/css" href="'.$basePath.'.index/css/tabs.css"/>
    <link rel="shortcut icon" href="'.$basePath.'.index/images/favicon.ico"/>
    <!--[if IE]>
      <link rel="stylesheet" type="text/css" media="screen" href="'.$basePath.'.index/css/tabs.msie.css"/>
    <![endif]-->
    <script type="text/javascript">
if($.browser.mozilla)
  $("head").append($(\'<link type="text/css" media="screen" rel="stylesheet" href="'.$basePath.'.index/css/tabs.moz.css"/>\'));
    </script>
  </head>
  <body class="test">
    <div id="container">
      <div id="header">
        <h1><a href="./" alt="back to Index" title="back to Index">'.$title.'</a> - '.$service.'</h1>
      </div>

      <!--  INFO  -->
      <div id="info">
        <h3>Information</h3>
        <dl>
          <dt>Service Class</dt>
          <dd>'.$class.'</dd>
          <dt>Service Name</dt>
          <dd><acronym title="'.$description.'">'.$service.'</acronym></dd>
          <dt>Default Format</dt>
          <dd>'.$defaultFormat.'</dd>
          <dt>Request Format</dt>
          <dd>'.$format.'</dd>
          <dt>Service Request URL</dt>
          <dd>
            <a href="'.$requestUrl.'" title="'.basename( $requestUrl ).'">URL</a>
            <a href="'.$requestUrl.'" title="'.basename( $requestUrl ).'" target="_blank">^</a>
          </dd>
          <dt>Service Test URL</dt>
          <dd>
            <a href="'.$testUrl.'" title="'.basename( $testUrl ).'">URL</a>
            <a href="'.$testUrl.'" title="'.basename( $testUrl ).'" target="_blank">^</a>
          </dd>
          <dt>Response Time</dt>
          <dd><acronym title="'.$time.' &micro;s">'.round( $time / 1000, 1 ).' ms</acronym></dd>
          <dt>DOM Elements</dt>
          <dd id="domElements"></dd>
          <div style="clear: both"></div>
        </dl>
        <br/>
      </div>

      <!--  FORM  -->
<!--      &laquo;&nbsp;<a href="./">back to Index</a>
      <h2>'.$service.'</h2>-->
      <br/>
      <em>'.$description.'</em><br/><br/>
      <div id="control">
        <h3>Parameters</h3>
        <form action="./?test='.$service.'" method="POST">
          '.$parameters.'
         <button type="submit" name="call">call</button>
         </form>
        <br/>
      </div>

      <!--  RESPONSE  -->
      <div id="response">
        '.$tabs->buildTabs( 'tabs' ).'
      </div>

    </div>
  </body>
  <script type="text/javascript" src="'.$jsLibPath.'jquery/history/1.0.3.pack.js"></script>
  <script type="text/javascript" src="'.$jsLibPath.'jquery/tabs/2.7.4.pack.js"></script>
  <script type="text/javascript">
<!--
'.$tabs->buildScript( '#tabs' ).'

$("#tabs li>a>span:contains(\'Data\')").addClass("data");
$("#tabs li>a>span:contains(\'Exception\')").addClass("exception");
$("#tabs li>a>span:contains(\'Response\')").addClass("response");
$("#tabs li>a>span:contains(\'Request\')").addClass("request");
$("#tabs li>a>span:contains(\'Trace\')").addClass("trace");

$(document).ready(function(){
  $("#domElements").html(document.getElementsByTagName("*").length);
});
-->
  </script>
</html>';
?>