<?php
/**
 *	...
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@package		net.api.google.code
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		net.api.google.code
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Net_API_Google_Code_PostCommit
{
	protected $authKey			= "";
	protected $headerName		= 'GOOGLE_CODE_PROJECT_HOSTING_HOOK_HMAC';
	protected $request			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$authKey		Google Code Post-Commit Authentication Key
	 *	@return		void
	 *	@throws		RuntimeException if Key is empty
	 */
	public function __construct( $authKey )
	{
		if( empty( $authKey ) )
			throw new RuntimeException( 'Key cannot be empty' );
	}

	/**
	 *	Receives Data from Google Code Post-Commit Hook and returns JSON string.
	 *	@access		public
	 *	@return		string
	 *	@throws		RuntimeException if raw POST data is empty
	 *	@throws		RuntimeException if Key is invalid
	 */
	public function receiveData( Net_HTTP_Request_Receiver $request)
	{
		$data	= $request->getRawPostData();
		if( !$data )
			throw new RuntimeException( 'No raw POST data received' );
		$digest	= hash_hmac( "md5", $data, $this->authKey );
		$header	= array_pop( $request->getHeadersByName( $this->headerName ) );
		if( $digest !== $header->getValue() )
			throw new RuntimeException( 'Authentication failed' );
		return $data;
	}

	/**
	 *	Receives Data from Google Code Post-Commit Hook and returns decoded object structure.
	 *	@access		public
	 *	@return		object
	 */
	public function receiveDataAndDecode( Net_HTTP_Request_Receiver $request )
	{
		$data	= $this->receiveData();
		return json_decode( $data );
	}
}	
?>