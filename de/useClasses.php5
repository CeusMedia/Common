<?php
/**
 *	Appending path to classes and interfaces to include path.
 *
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1.0
 */
$__container	= "c:/.mirror/classes5";

$__path		= ini_get ("include_path");
$__separator	= (substr(PHP_OS, 0, 3) == 'WIN') ? ";" : ":";
$__path_new	=  $__container.$__separator.$__path;
ini_set ("include_path", $__path_new);
require_once ("de/ceus-media/ClassImport.php");
import ("de.ceus-media.Object");
?>