<?php
import( 'de.ceus-media.file.css.Combiner' );
class File_CSS_Theme_Combiner extends File_CSS_Combiner
{
	const PROTOCOL_NONE		= 0;
	const PROTOCOL_HTTP		= 1;
	const PROTOCOL_HTTPS	= 2;

	protected $protocol;

	/**
	 *	Callback Method for additional Modifikations before Combination.
	 *	@access		protected
	 *	@param		string		$content		Content of Style File
	 *	@return		string		Revised Content of Style File
	 */
	protected function reviseStyle( $content )
	{
		if( $this->protocol == self::PROTOCOL_HTTP )
		{
			$content	= str_ireplace( "https://", "http://", $content );
		}
		else if( $this->protocol == self::PROTOCOL_HTTPS )
		{
			$content	= str_ireplace( "http://", "https://", $content );
		}
		return $content;
	}
	
	public function setProtocol( $integer )
	{
		$this->protocol	= $integer;
	}
}
?>