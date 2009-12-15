<?php
/**
 *	Basic Response Class for a Service.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		net.service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6.5
 */
import( 'de.ceus-media.alg.time.Clock' );
/**
 *	Basic Response Class for a Service.
 *	@category		cmClasses
 *	@uses			Alg_Time_Clock
 *	@package		net.service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6.5
 */
class Net_Service_Response
{
	protected $watch	= NULL;

	public function __construct()
	{
		$this->watch	= new Alg_Time_Clock;	
	}

	/**
	 *	Converts a Data Array to a XML Structure and appends it to the given SimpleXMLElement.
	 *	@access		protected
	 *	@param		XML_Element		$xmlNode		XML Node to append to
	 *	@param		array			$dataArray		Array to append
	 *	@param		string			$lastParent		Recursion: Outer Node Name for Integer Values
	 *	@return		void
	 */
	protected function addArrayToXmlNode( &$xmlNode, $dataArray, $lastParent = "" )
	{
		if( !( is_string( $lastParent ) && $lastParent ) )
			$lastParent	= "item";
		foreach( $dataArray as $key => $value )
		{
			if( is_array( $value ) )
			{
				if( is_int( $key ) )
				{
					$child	=& $xmlNode->addChild( "set" );
					$this->addArrayToXmlNode( $child, $value, "items" );
					continue;
				}
				$child	=& $xmlNode->addChild( $key );
				$this->addArrayToXmlNode( $child, $value, $key );
				continue;
			}
			else if( is_int( $key ) )
			{
				if( $lastParent )
					$key	= $this->getSingular( $lastParent );
				else
					$key	= "item";
			}
			$xmlNode->addChild( $key, str_replace( "&", "&amp;", $value ) );
		}
	}
	
	/**
	 *	Builds Response Structure for every Format, containing Response Data Content, Status and more.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	array
	 */
	protected function buildResponseStructure( $content, $status )
	{
		if( $content instanceof Exception )
		{
#			import( 'de.ceus-media.ui.html.exception.TraceViewer' );
#			$trace	= UI_HTML_Exception_TraceViewer::buildTrace( $content, 2 );
			$serial		= ( $content instanceof PDOException ) ? serialize( $content ) : NULL;
			$content	= array(
				'type'		=> get_class( $content ),
				'message'	=> $content->getMessage(),
				'code'		=> $content->getCode(),
				'file'		=> $content->getFile(),
				'line'		=> $content->getLine(),
				'trace'		=> $content->getTraceAsString(),
				'serial'	=> $serial
			);
			$status	= "exception";
		}
		$structure	= array(
			'status'	=> $status,
			'data'		=> $content,
			'timestamp'	=> time(),
			'duration'	=> $this->watch === NULL ? NULL : $this->watch->stop( 6, 0 ),
		
		);
		return $structure;
	}

	/**
	 *	Tries to convert Data to Output Format.
	 *	@access		public
	 *	@param		mixed			$content		Response Data Content to Convert
	 *	@param		mixed			$format			Response Format
	 *	@param		string			$status			Status String, by default "data"
	 *	@return		string
	 *	@throws		BadMethodCallException
	 */
	public function convertToOutputFormat( $content, $format, $status = "data" )
	{
		$method	= "get".ucFirst( $format );
		if( method_exists( $this, $method ) )
			return $this->$method( $content, $status );
		$message	= 'No method "'.$method.'" implemented for output format "'.$format.'".';
		throw new BadMethodCallException( $message );
	}
	
	/**
	 *	Return Content as Base64 String.
	 *	@access		protected
	 *	@param		string			$string			String to convert to Base64
	 *	@return 	string
	 */
	protected function getBase64( $string )
	{
		if( !is_string( $string ) )
			throw new InvalidArgumentException( 'Base64 needs a string.' );
		return base64_encode( $string );
	}

	/**
	 *	Return Content as JSON.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getJson( $content, $status = "data" )
	{
		$content	= $this->buildResponseStructure( $content, $status );
		return json_encode( $content );		
	}

	/**
	 *	Return Content as PHP Serial.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getPhp( $content, $status = "data" )
	{
		$content	= $this->buildResponseStructure( $content, $status );
		return serialize( $content );
	}

	/**
	 *	Returns Singular of a Word.
	 *	@access		public
	 *	@param		string			$words			Word in Plural
	 *	@return		string
	 */
	protected function getSingular( $word )
	{
		$word	= preg_replace( '@ies$@', "y", $word );
		$word	= preg_replace( '@(([s|x|h])e)?s$@', "\\2", $word );
		return $word;
	}
	
	/**
	 *	Return Content as WDDX String.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getTxt( $content, $status = "data" )
	{
		if( $content instanceof Exception )
			return $content->getMessage();
		return (string) $content;
	}

	/**
	 *	Return Content as WDDX String.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getWddx( $content, $status = "data" )
	{
		$content	= $this->buildResponseStructure( $content, $status );
		return wddx_serialize_value( $content );
	}

	/**
	 *	Return Content as XML String.
	 *	@access		protected
	 *	@param		mixed			$content		Response Data Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getXml( $content, $status = "data" )
	{
		$data	= $this->buildResponseStructure( $content, $status );
		import( 'de.ceus-media.xml.Element' );
		import( 'de.ceus-media.xml.dom.Formater' );
		$root	= new XML_Element( "<response/>" );
		$this->addArrayToXmlNode( $root, $data, "item" );
		$xml	= $root->asXml();
		$xml	= XML_DOM_Formater::format( $xml );
		return $xml;
	}
}
?>