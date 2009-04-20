<?php
/**
 *	Collects Class information with String Tokenizer.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.08.2005
 *	@version		0.5
 */
/**
 *	Collects Class information with String Tokenizer.
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.08.2005
 *	@version		0.5
 */
class ClassInfo
{
	var $_tokens = array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	fileName		File Name of Class
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$content = file_get_contents( $fileName );
		$this->_tokens = token_get_all( $content );
	}
	
	/**
	 *	Returns Name of Class.
	 *	@access		public
	 *	@return		string
	 */
	public function getClassName()
	{
		$catch = false;
		foreach( $this->_tokens as $token )
		{
			if( is_array( $token ) )
			{
				list( $id, $text ) = $token;
				if( $id == T_CLASS )
					$catch = true;
				else if( $id == T_STRING && $catch )
					return $text;
			}
		}
	}

	/**
	 *	Returns all Methods of Class.
	 *	@access		public
	 *	@return		array
	 */
	public function getFunctions()
	{
		$catch = false;
		$functions = array();
		foreach( $this->_tokens as $token )
		{
			if( is_array( $token ) )
			{
				list( $id, $text ) = $token;
				if( $id == T_FUNCTION )
					$catch = true;
				else if( $id == T_STRING && $catch )
					$functions[] = $text;
			}
			else if( $token == "(" )
				$catch = false;
		}
		return $functions;
	}

	/**
	 *	Returns all Variables of Class.
	 *	@access		public
	 *	@return		array
	 */
	public function getVars()
	{
		$catch	= false;
		$vars	= array();
		foreach( $this->_tokens as $token )
		{
			if( is_array( $token ) )
			{
				list( $id, $text ) = $token;
				if( $id == T_VAR )
					$catch = true;
				else if( $id == T_VARIABLE && $catch )
				{
					$vars[] = $text;
					$catch = false;
				}
			}
			else if( $token == "(" )
				$catch = false;
		}
		return $vars;
	}

	/**
	 *	Returns Method Variables of Class.
	 *	@access		public
	 *	@return		array
	 */
	public function getFunctionVars( $function )
	{
		$catch	= 0;
		$vars	= array();
		foreach( $this->_tokens as $token )
		{
			if( is_array( $token ) )
			{
				list( $id, $text ) = $token;
				if( $id == T_FUNCTION )
					$catch = 1;
				else if( $id == T_STRING && $catch == 1)
					$catch = $text == $function ? 2 : 0;
				else if( $id == T_VARIABLE && $catch == 3 )
				{
					$vars[] = $text;
					$catch = 2;
				}
			}
			else
			{
				if( $token == ","  && $catch == 2 )
					$catch = 3;
				else if( $token == "("  && $catch == 2 )
					$catch = 3;
				else if( $token == ")")
					$catch = 0;
			}
		}
		return $vars;
	}
}
?>