<?php
/**
 *	Generates URL for Gravatar API.
 *
 *	Copyright (c) 2015-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RPC
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://xmlrpc.scripting.com/spec.html XML-RPC Specification
 *	@since			0.7.7
 */

namespace CeusMedia\Common\XML\RPC;

use CeusMedia\Common\Net\HTTP\Post as HttpPost;
use CeusMedia\Common\XML\ElementReader;

/**
 *	Generates URL for Gravatar API.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_XML_RPC
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://xmlrpc.scripting.com/spec.html XML-RPC Specification
 *	@since			0.7.7
 */
class Client
{
	/**	@var		string		$url			Base URL of XML-RPC */
	protected $url;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			Base URL of XML-RPC
	 *	@return		void
	 */
	public function __construct( $url ){
		$this->url	= $url;
	}

	/**
	 *	Calls XML-RPC method with parameters and returns resulting answer data.
	 *	@access		public
	 *	@param		string		$method			Method XML-RPC
	 *	@param		array		$parameters		List of method parameters
	 *	@return		mixed
	 */
	public function call( $method, $parameters ){
		$params	= array();
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
	 *	@param		string		$method			Method XML-RPC
	 *	@param		array		$parameters		List of method parameters
	 *	@return		mixed
	 */
	static protected function decodeXmlParameter( $node, $preserveObjects = FALSE ){
		switch( $node->getName() ){
			case 'struct':
				$data	= array();
				foreach( $node->member as $member )
					foreach( $member->value->children() as $value )
						$data[(string) $member->name]	= self::decodeXmlParameter( $value );
				if( $preserveObjects )
					$data	= (object) $data;
				return $data;
			case 'array':
				$data	= array();
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
	 *	@param		string		$method			Method XML-RPC
	 *	@param		array		$parameters		List of method parameters
	 *	@return		string
	 */
	static protected function encodeXmlParameter( $parameter ){
		$data	= [];
		switch( gettype( $parameter ) ){
			case 'object':
				foreach( get_object_vars( $parameter ) as $key => $value ){
					$value	= self::encodeXmlParameter( $value );
					$data[]	= '<member><name>'.$key.'</name>'.$value.'</member>';
				}
				return '<struct>'.join( $data ).'</struct>';
			case 'array':
				$data	= array();
				foreach( $parameter as $value )
					$data[]	= self::encodeXmlParameter( $value );
				return '<array><data>'.join( $data ).'</data></array>';
			case 'int':
			case 'integer':
				return '<value><int>'.$parameter.'</int></value>';
			case 'bool':
			case 'boolean':
				return '<value><boolean>'.( (int) $parameter ).'</boolean></value>';
			case 'double':
			case 'float':
				return '<value><double>'.$parameter.'</double></value>';
			case 'string':
				return '<value><string>'.$parameter.'</string></value>';
		}
	}

	/**
	 *	...
	 *	@access		protected
	 *	@param		string		$method			Method XML-RPC
	 *	@param		array		$parameters		List of method parameters
	 *	@return		mixed
	 */
	static protected function parseResponse( $xml ){
		$list		= array();
		$response	= ElementReader::read( $xml );
		foreach( $response->params as $params )
			foreach( $params as $param )
				foreach( $param as $value )
					foreach( $value as $node )
						$list[]	= self::decodeXmlParameter( $node );
		return $list;
	}
}
