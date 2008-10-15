<?php
/**
 *	Basic Response Class for a Service.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Basic Response Class for a Service.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6
 */
class Net_Service_Response
{
	/**
	 *	Return Content as JSON.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getJson( $mixed )
	{
		return json_encode( $mixed );		
	}

	/**
	 *	Return Content as PHP Serial.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getPhp( $mixed )
	{
		return serialize( $mixed );
	}
	
	/**
	 *	Return Content as Base64 String.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getBase64( $mixed )
	{
		return base64_encode( $mixed );
	}
	
	/**
	 *	Return Content as WDDX String.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getWddx( $mixed )
	{
#		if( $mixed instanceof Exception )
#			$mixed	= get_class( $mixed ).": ".$midex->getMessage();
		return wddx_serialize_value( $mixed );
	}
}
?>