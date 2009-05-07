<?php
$uri	= "../../tools/dev/UnitTestResultViewer";
if( file_exists( $uri ) )
	echo '<body marginwidth="0" marginheight="0"><iframe frameborder="0" width="100%" height="100%" src="'.$uri.'"></iframe>';
else
	echo "No Result Viewer installed.";
?>