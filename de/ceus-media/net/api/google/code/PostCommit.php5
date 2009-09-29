<?php
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