<?php
class Fork_Server_Client_WebProxy extends Fork_Server_Client_Abstract
{
	public function getRequest()
	{
		return http_build_query( $_REQUEST, "", "&" );
	}
}
?>