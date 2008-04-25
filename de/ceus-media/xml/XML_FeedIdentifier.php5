<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.xml.dom.SyntaxValidator' );
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			File_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 */
/**
 *	Identifies Type and Version of RSS and ATOM Feeds.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			File_Reader
 *	@uses			XML_DOM_SyntaxValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.01.2006
 *	@version		0.1
 *	@deprecated		old PHP4 Version
 */
class XML_FeedIdentifier
{
	/**	@var	string	_type		Type of Feed */
	var $_type	= "";
	/**	@var	string	_version		Version of Feed Type */
	var $_version	= "";
	
	/**
	 *	Returns identified Type of Feed.
	 *	@access		public
	 *	@return		string
	 */
	function getType()
	{
		return $this->_type;
	}
	
	/**
	 *	Returns identified Version of Feed Type.
	 *	@access		public
	 *	@return		string
	 */
	function getVersion()
	{
		return $this->_version;
	}

	/**
	 *	Identifies Feed from XML.
	 *	@access		public
	 *	@param		string	xml		XML of Feed
	 *	@return		string
	 */
	function identify( $xml )
	{
		$this->_type		= "";
		$this->_version	= "";
		$type	= "";
		$version	= "";
		$xsv	= new XML_DOM_SyntaxValidator;
		if( $xsv->validate( $xml ) )
		{
			$doc	=& $xsv->getDocument();
			$xpath	= new DOMXPath( $doc );
			$rss	= $xpath->query( "//rss/@version" );
			if( $rss->length )
			{
				$attributes	= $rss->item( 0 );
				$type	= "RSS";
				$version	= $attributes->value;
			}
			else
			{
				$namespace = $xpath->evaluate( 'namespace-uri(//*)' );
				$xpath->registerNamespace( "rdf", $namespace );
				$rdf		= $xpath->evaluate( "//rdf:RDF" );
				if( $rdf->length )
				{
					$type		= "RSS";
					$version	= "1.0";
				}
				else
				{
					$atom	= $xpath->evaluate( "//feed/@version" );
					if( $atom->length )
					{
						$attributes	= $atom->item( 0 );
						$type	= "ATOM";
						$version	= $attributes->value;
					}
					else
					{
						$namespace = $xpath->evaluate( 'namespace-uri(//*)' );
						$xpath->registerNamespace( "pre", $namespace );
						$atom	= $xpath->evaluate( "//pre:feed/@version" );
						if( $atom->length )
						{
							$attributes	= $atom->item( 0 );
							$type	= "ATOM";
							$version	= $attributes->value;
						}
						else
						{
							$atom	= $xpath->evaluate( "//pre:feed/pre:title/text()" );
							if( $atom->length )
							{
								$type	= "ATOM";
								$version	= "1.0";
							}
						}
					}
				}
			}
			if( $type && $version )
			{
				$this->_type		= $type;
				$this->_version	= $version;
				return $type."/".$version;
			}
		}
		return false;
	}

	/**
	 *	Identifies Feed from a File.
	 *	@access		public
	 *	@param		string	filename		XML File of Feed
	 *	@return		string
	 */
	function identifyFromFile( $file )
	{
		$file	= new File_Reader( $filename );
		$xml	= $file->readString();
		return $this->identify( $xml );
	}
}
?>