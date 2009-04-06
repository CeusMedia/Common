<?php
$cssLibPath	= "//css.ceusmedia.com/";
$jsLibPath	= "//js.ceusmedia.com/";

return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title>'.$title.'</title>
    <link rel="stylesheet" href="'.$cssLibPath.'blueprint/reset.css"/>
    <link rel="stylesheet" href="'.$cssLibPath.'blueprint/typography.css"/>
    <link rel="stylesheet" href="'.$basePath.'.index/css/tool.css"/>
    <link rel="stylesheet" href="'.$basePath.'.index/css/services.index.filter.css"/>
    <link rel="stylesheet" href="'.$basePath.'.index/css/services.index.table.css"/>
    <link rel="shortcut icon" href="'.$basePath.'.index/images/favicon.ico"/>
    <script src="'.$jsLibPath.'jquery/1.2.3.pack.js"></script>
    <script src="'.$jsLibPath.'jquery/color.js"></script>
    <script src="'.$jsLibPath.'jquery/cmServiceIndex.js"></script>
    <script src="'.$jsLibPath.'jquery/cmBlitz.js"></script>
  </head>
  <body class="index">
    <div id="container">
      <div id="header">
        <h1>'.$title.'</h1>
      </div>

      <!--  FILTER  -->
      <div id="filter">
        <h3><acronym title="Filters will apply immediately">Filters and Options</acronym></h3>
        <ul>
          <li>
            <label for="filter_search"><acronym title="case senstive">Search</acronym></label>
            <input id="filter_search" autocomplete="off"/>
          </li>
          <li>
            <label for="filter_format"><acronym title="show services with a specific response format">Format</acronym></label>
            <select id="filter_format">'.$optFormat.'</select>
          </li>
          <li>
            <input id="filter_classes" type="checkbox"/>
            <label for="filter_classes"><acronym title="show names of classes implementing services">show&nbsp;Classes</acronym></label>
          </li>
          <li>
            <input id="filter_rules" type="checkbox"/>
            <label for="filter_rules"><acronym title="show service parameter rules">show&nbsp;Rules</acronym></label>
          </li>
        <div style="clear: both"></div>
        </ul>
      </div>

      <!--  INFORMATION  -->
      <p>'.$description.'</p>
      <b>Call Syntax</b><br/>
      <code>'.$syntax.'</code>
      <br/>

      <!--  LIST  -->
      <div id="list">
        <h2>Net Services List <small>(<span id="serviceCounter"></span>)</small></h2>
        '.$table.'
        <div id="message">No Services found.</div>
      </div>

    </div>
    <script>
$(document).ready(function(){
  var indexFilter = $(document).cmServiceIndex({
    searchDelay: 200,
    useBlitz: true
  });
});
$("#search").focus();
    </script>
  </body>
</html>';
?>