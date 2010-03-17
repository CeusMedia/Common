<?php
/**
 *	Finds not used Variables in Methods of a Class.
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.01.2008
 *	@version		0.6
 */
/**
 *	Finds not used Variables in Methods of a Class.
 *	@category		cmClasses
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.01.2008
 *	@version		0.6
 */
class Alg_UnusedVariableFinder
{
	/** @var		array		$methods 		Array of collect Method Parameters and Code Lines */
	protected $methods	= array();
	/** @var		array		$vars 			Array of collect Method Variables */
	protected $vars		= array();
	
	/**
	 *	Returns an Array of Methods and their unused Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get unused Variables for.
	 *	@return		array
	 */
	public function getUnusedVars( $method = NULL )
	{
		$list	= array();
		if( $method !== NULL )
		{
			foreach( $this->vars[$method] as $var => $count )
			{
				if( !$count )
					$list[]	= $var;
			}
		}
		else
		{
			foreach( $this->vars as $method => $vars )
			{
				$vars	= $this->getUnusedVars( $method );
				if( $vars )
					$list[$method]	= $vars;
			}
		}
		return $list;
	}

	/**
	 *	Returns an Array of Methods and their Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get Variables for.
	 *	@return		array
	 */
	public function getVars( $method = "" )
	{
		if( $method )
			return $this->vars[$method];
		return $this->vars;
	}

	/**
	 *	Parse a Class File and collects Methods and their Lines.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Class
	 *	@return		void
	 */
	private function parseCode( $content )
	{
		$open		= FALSE;
		$content	= preg_replace( "@/\*.*\*/@Us", "", $content );
		$lines		= explode( "\n", $content );
		$matches	= array();
		$count		= 0;
		foreach( $lines as $line )
		{
			$line	= trim( $line );
			if( !$open )
			{
				if( preg_match( "@^(final )?(private |protected |public )?(static )?function @", $line ) )
				{
					$name	= preg_replace( "@^.*function ([^(]+) ?\((.*)\).*$@i", "\\1____\\2", $line );
					$parts	= explode( "____", $name );
					$open	= $parts[0];
					$matches[$open]['params']	= array();
					$matches[$open]['lines']	= array();
					if( isset( $parts[1] ) && trim( $parts[1] ) )
					{
						$params	= explode( ",", preg_replace( "@\(.*\)@", "", $parts[1] ) );
						foreach( $params as $param )
						{
							$param	= trim( $param );
							$matches[$open]['params'][]	= preg_replace( '@^([a-z0-9_]+ )?&?\$(.*)( ?= ?.*)?$@Ui', "\\2", $param );
						}
					}
				}
			}
			else
			{
				if( preg_match( "@^{.*$@", $line ) )
				{
					$count++;
				}
				if( preg_match( "@^}.*$@", $line ) )
				{
					$count--;
					if( !$count )
						$open	= FALSE;
				}
				$line	= trim( $line );
				if( $line && !preg_match( "@^\}|\{$@", $line ) )
					$matches[$open]['lines'][]	= $line;
			}
		}
		$this->methods	= $matches;
	}

	/**
	 *	Parse a Class Method and collects Variables.
	 *	@access		public
	 *	@param		bool		$countCalls		Flag: count Number Variable uses
	 *	@return		void
	 */
	private function parseFunctions( $countCalls = FALSE )
	{
		foreach( $this->methods as $method => $data )
		{
			$this->vars[$method]	= array();
			foreach( $data['params'] as $param )
				$this->vars[$method][$param]	= 0;
		
			foreach( $data['lines'] as $line )
			{
				$var	= "";
				$pattern	= "@^ *\t*[$]([^ ]+)(\t| )+=[^>].*@";
				if( preg_match( $pattern, $line ) )
				{
					$var	= trim( preg_replace( $pattern, "\\1", $line ) );
					if( $var && !preg_match( "@\[|->@", $var ) && !array_key_exists( $var, $this->vars[$method] ) )
					{
						$this->vars[$method][$var]	= 0;
					}
				}
				if( !isset( $this->vars[$method] ) )
					continue;
				foreach( $this->vars[$method] as $name => $count )
				{
					if( $name == $var )
						continue;
					if( !$countCalls && $count )
						continue;
					$pattern	= '@(\$'.$name.'([^a-z0-9_]|\[))|(\$'.$name.'$)@Ui';
					if( preg_match( $pattern, $line ) )
					{
						$this->vars[$method][$name]++;
					}
				}
			}
		}
	}

	/**
	 *	Reads a Class File and finds unused Variables in Methods.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Class
	 *	@return		void
	 */
	public function readFile( $fileName )
	{
		$code	= File_Reader::load( $fileName );
		$this->readCode( $code );
	}

	/**
	 *	Reads a Class Code and finds unused Variables in Methods.
	 *	@access		public
	 *	@param		string		$code			Code of Class
	 *	@return		void
	 */
	public function readCode( $code )
	{
		$this->methods	= array();
		$this->vars		= array();
		$this->parseCode( $code );
		$this->parseFunctions();
	}
}
?>