<?php
/**
 *	Interface for Services.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Net.Service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
/**
 *	Interface for Services.
 *	@category		cmClasses
 *	@package		Net.Service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
interface Net_Service_Interface_Point
{
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@param		array			$requestData		Array (or Object with ArrayAccess Interface) of Request Data
	 *	@return		string			Response String of Service	
	 */
	public function callService( $serviceName, $responseFormat = NULL, $requestData = NULL );

	/**
	 *	Returns Class of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Class of Service
	 */
	public function getServiceClass( $serviceName );
	
	/**
	 *	Returns Description of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Description of Service
	 */
	public function getServiceDescription( $serviceName );

	/**
	 *	Returns available Response Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array			Response Formats of Service
	 */
	public function getServiceFormats( $serviceName );

	/**
	 *	Returns available Formats of Service.
	 *	@access		public
	 *	@param		string			Service to get Formats of
	 *	@return		array			Formats of this Service
	 */
	public function getServiceParameters( $serviceName );

	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array			Services in Service Point
	 */
	public function getServices();

	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string			Title of Service Point
	 */
	public function getTitle();

	/**
	 *	Returns Syntax of Service Point.
	 *	@access		public
	 *	@return		string			Syntax of Service Point
	 */
	public function getSyntax();
}
?>