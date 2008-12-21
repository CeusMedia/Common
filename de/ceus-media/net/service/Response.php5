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
 *	@version		0.6.5
 */
/**
 *	Basic Response Class for a Service.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.6.5
 */
class Net_Service_Response
{
	/**
	 *	Converts a Data Array to a XML Structure and appends it to the given SimpleXMLElement.
	 *	@access		private
	 *	@param		XML_Element		$xmlNode		XML Node to append to
	 *	@param		array			$dataArray		Array to append
	 *	@param		string			$lastParent		Recursion: Outer Node Name for Integer Values
	 *	@return		void
	 */
	private function addArrayToXmlNode( &$xmlNode, $dataArray, $lastParent = "" )
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
				$this->addArrayToXmlNode( $child, $value, "1".$key );
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
	 *	Return Content as Base64 String.
	 *	@access		protected
	 *	@param		string			$string			String to convert to Base64
	 *	@return 	string
	 */
	protected function getBase64( $string )
	{
		return base64_encode( $string );
	}

	/**
	 *	Return Content as JSON.
	 *	@access		protected
	 *	@param		string			$data			Content
	 *	@return 	string
	 */
	protected function getJson( $mixed, $status = "data" )
	{
		if( $mixed instanceof Exception )
			throw $mixed;
		$data	= array(
			'status'	=> $status,
			'data'		=> $mixed
		);
		return json_encode( $data );		
	}

	/**
	 *	Return Content as PHP Serial.
	 *	@access		protected
	 *	@param		string			$data			Content
	 *	@return 	string
	 */
	protected function getPhp( $mixed, $status = "data" )
	{
		if( $mixed instanceof Exception )
			throw $mixed;
		$data	= array(
			'status'	=> $status,
			'data'		=> $mixed
		);
		return serialize( $data );
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
	 *	@param		string			$data			Content
	 *	@return 	string
	 */
	protected function getWddx( $mixed, $status = "data" )
	{
		if( $mixed instanceof Exception )
			throw $mixed;
#		if( $mixed instanceof Exception )
#			$mixed	= get_class( $mixed ).": ".$midex->getMessage();
		$data	= array(
			'status'	=> $status,
			'data'		=> $mixed
		);
		return wddx_serialize_value( $data );
	}

	/**
	 *	Return Content as XML String.
	 *	@access		protected
	 *	@param		string			$data			Content
	 *	@param		string			$status			Status String, by default "data"
	 *	@return 	string
	 */
	protected function getXml( $data, $status = "data" )
	{
		if( $data instanceof Exception )
			throw $data;
		$data	= array(
			'status'	=> $status,
			'data'		=> $data,
		);
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