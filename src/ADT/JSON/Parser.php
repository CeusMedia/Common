<?php
/**
 *	JSON Parser.
 *
 *	Copyright (c) 2010-2018 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
namespace CeusMedia\Common\ADT\JSON;

/**
 *	JSON Parser.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.0
 *	@version		$Id$
 */
class Parser
{
	/**
	 *	Get new instance of JSON reader by static call.
	 *	This method is useful for chaining method calls.
	 *	@access		public
	 *	@static
	 *	@return		self
	 */
	public static function getNew( $filePath ){
		return new self( $filePath );
	}

	/**
	 *	Reads a JSON file to an object or array statically.
	 *	@access		public
	 *	@param		string		$filePath		Path to JSON file
	 *	@param		bool		$asArray		Flag: read into an array
	 *	@return		object|array
	 */
	public static function load( $json, $asArray = NULL )
	{
		$parser	= new Parser();
		return $parser->parse( $json, $asArray );
	}

	public function getError(){
		return json_last_error();
	}

	public function getMessage(){
		return json_last_error_msg();
	}


	/**
	 *	Returns data of parsed JSON string.
	 *	@access		public
	 *	@param		string		$json			JSOn sting to parse
	 *	@param		boolean		$asArray		Flag: read into an array
	 *	@return		object|array
	 *	@throws		\RuntimeException			if parsing failed
	 */
	public function parse( $json, $asArray = NULL ){
		$data	= json_decode( $json, $asArray );
		if( json_last_error() !== \JSON_ERROR_NONE ){
			$message	= 'Decoding JSON failed (%s): %s';
			$message	= vsprintf( $message, array(
				\CeusMedia\Common\ADT\Constant::getKeyByValue( 'JSON_ERROR_', json_last_error() ),
				json_last_error_msg()
			) );
			throw new \RuntimeException( $message, json_last_error() );
		}
		return $data;

	}
}
?>
