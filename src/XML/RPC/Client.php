<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Generates URL for Gravatar API.
 *
 *	Copyright (c) 2015-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RPC
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://xmlrpc.scripting.com/spec.html XML-RPC Specification
 */

namespace CeusMedia\Common\XML\RPC;

use CeusMedia\Common\Net\HTTP\Post as HttpPost;
use CeusMedia\Common\XML\ElementReader;
use CeusMedia\Common\XML\Element;
use Exception;
use InvalidArgumentException;

/**
 *	Generates URL for Gravatar API.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RPC
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://xmlrpc.scripting.com/spec.html XML-RPC Specification
 */
class Client
{
	/**	@var		string		$url			Base URL of XML-RPC */
	protected string $url;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			Base URL of XML-RPC
	 *	@return		void
	 */
	public function __construct( string $url )
	{
		$this->url	= $url;
	}

	/**
	 *	Calls XML-RPC method with parameters and returns resulting answer data.
	 *	@access		public
	 *	@param		string		$method			Method XML-RPC
	 *	@param		array		$parameters		List of method parameters
	 *	@return		array
	 *	@throws		Exception
	 */
	public function call( string $method, array $parameters ): array
	{
		$params	= [];
		foreach( $parameters as $parameter )
			$params[]	= '<param>'.self::encodeXmlParameter( $parameter ).'</param>';
		$method	= '<methodName>'.$method.'</methodName>';
		$params	= '<params>'.join( $params ).'</params>';
		$call	= '<methodCall>'.$method.$params.'</methodCall>';
		return self::parseResponse( HttpPost::sendData( $this->url, $call ) );
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		Element		$node				...
	 *	@param		bool		$preserveObjects	Flag: ...
	 *	@return		array|bool|float|int|object|string|void
	 */
	protected static function decodeXmlParameter( Element $node, bool $preserveObjects = FALSE )
	{
		switch( $node->getName() ){
			case 'struct':
				$data	= [];
				foreach( $node->member as $member )
					foreach( $member->value->children() as $value )
						$data[(string) $member->name]	= self::decodeXmlParameter( $value );
				if( $preserveObjects )
					$data	= (object) $data;
				return $data;
			case 'array':
				$data	= [];
				foreach( $node->data->children() as $values )
					foreach( $values as $value )
						$data[]	= self::decodeXmlParameter( $value );
				return $data;
			case 'int':
			case 'i4':
				return (int) (string) $node;
			case 'boolean':
				return (bool) (int) $node;
			case 'string':
				return (string) $node;
			case 'double':
				return (double) $node;
		}
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		mixed		$parameter			...
	 *	@return		string
	 */
	protected static function encodeXmlParameter( mixed $parameter ): string
	{
		$data	= [];
		$type	= gettype( $parameter );
		switch( $type ){
			case 'object':
				foreach( get_object_vars( $parameter ) as $key => $value ){
					$value	= self::encodeXmlParameter( $value );
					$data[]	= '<member><name>'.$key.'</name>'.$value.'</member>';
				}
				return '<struct>'.join( $data ).'</struct>';
			case 'array':
				foreach( $parameter as $value )
					$data[]	= self::encodeXmlParameter( $value );
				return '<array><data>'.join( $data ).'</data></array>';
			case 'integer':
				return '<value><int>'.$parameter.'</int></value>';
			case 'boolean':
				return '<value><boolean>'.( (int) $parameter ).'</boolean></value>';
			case 'double':
				return '<value><double>'.$parameter.'</double></value>';
			case 'string':
				return '<value><string>'.$parameter.'</string></value>';
			default:
				throw new InvalidArgumentException( 'Unsupported type: '.$type );
		}
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$xml			Method XML-RPC
	 *	@return		array
	 *	@throws		Exception
	 */
	static protected function parseResponse( string $xml ): array
	{
		$list		= [];
		$response	= ElementReader::read( $xml );
		foreach( $response->params as $params )
			foreach( $params as $param )
				foreach( $param as $value )
					foreach( $value as $node )
						$list[]	= self::decodeXmlParameter( $node );
		return $list;
	}
}
