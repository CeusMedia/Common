#!/usr/bin/env php
<?php
require_once dirname( __FILE__, 2 ).'/vendor/autoload.php';
date_default_timezone_set( "Europe/Berlin" );					//  set default time zone
new \CeusMedia\CommonTool\Go\Application;
