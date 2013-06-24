<?php
/**
 *	Finds not used variables within PHP methods or functions.
 *
 *	Copyright (c) 2008 Christian Würker (ceusmedia.de)
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
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.01.2008
 *	@version		$Id$
 */
/**
 *	Finds not used variables within PHP methods or functions.
 *	@category		cmClasses
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.01.2008
 *	@version		$Id$
 */
class Alg_UnusedVariableFinder
{
	/** @var		array		$methods 		Array of collect Method Parameters and Code Lines */
	protected $methods	= array();
	/** @var		array		$vars 			Array of collect Method Variables */
	protected $vars		= array();

	/**
	 *	Returns an Array of Methods and their Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get Variables for.
	 *	@return		array
	 */
	public function countUnusedVariables()
	{
		$unused	= 0;
		foreach( $this->methods as $method => $data )
			foreach( $this->methods[$method]['variables'] as $variable => $count )
				$unused	+= (int) !$count;
		return $unused;
	}

	/**
	 *	Returns an Array of Methods and their Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get Variables for.
	 *	@return		array
	 */
	public function countVariables()
	{
		$total	= 0;
		foreach( $this->methods as $method => $data )
			foreach( $this->methods[$method]['variables'] as $variable => $count )
				$total++;
		return $total;
	}

	/**
	 *	Returns an Array of Methods and their unused Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get unused Variables for.
	 *	@return		array
	 */
	public function getUnusedVars( $method = NULL )
	{
		$list	= array();
		if( !strlen( $method = trim( $method ) ) )
		{
			foreach( $this->methods as $method => $data )
				if( ( $vars = $this->getUnusedVars( $method ) ) )
					$list[$method]	= $vars;
			return $list;
		}
		if( !array_key_exists( $method, $this->methods ) )
			throw new InvalidArgumentException( 'Method "'.$method.'" not found' );
		foreach( $this->getVariables( $method ) as $var => $data )
			$data ? NULL : $list[]	= $var;
		return $list;
	}

	/**
	 *	Returns an Array of Methods and their Variables.
	 *	@access		public
	 *	@param		string		$method			Optional: Method to get Variables for.
	 *	@return		array
	 */
	public function getVariables( $method )
	{
		if( !strlen( $method = trim( $method ) ) )
			return $this->methods;
		if( !array_key_exists( $method, $this->methods ) )
			throw new InvalidArgumentException( 'Method "'.$method.'" not found' );
		return $this->methods[$method]['variables'];
	}

	/**
	 *	Inspects all before parsed methods for variables.
	 *	@access		public
	 *	@param		bool		$countCalls		Flag: count Number Variable uses
	 *	@return		void
	 */
	private function inspectParsedMethods( $countCalls = FALSE )
	{
		foreach( $this->methods as $method => $data )												//  iterate before parsed methods
		{
			foreach( $data['lines'] as $nr => $line )												//  iterate method/function lines
			{
				$pattern	= "@^ *\t*[$]([a-z0-9_]+)(\t| )+=[^>].*@i";								//  prepare regular expression for variable assignment
				if( preg_match( $pattern, $line ) )													//  line contains variable assignment
					if( $var = trim( preg_replace( $pattern, "\\1", $line ) ) )						//  extract variable name from line
						if( !array_key_exists( $var, $this->methods[$method]['variables'] ) )		//  variable is not noted, yet
							$this->methods[$method]['variables'][$var]	= 0;						//  note newly found variable
				foreach( $this->methods[$method]['variables'] as $name => $count )					//  iterate known method/function variables
				{
					if( !$countCalls && $count )													//  variable is used and count mode is off
						continue;																	//  skip to next line
					$line		= preg_replace( '/\$'.$name.'\s*=/', "", $line );					//  remove variable assignment if found
					if( preg_match( '@\$'.$name.'[^a-z0-9_]@i', $line ) )							//  if variable is used in this line
						$this->methods[$method]['variables'][$name]++;								//  increate variable's use counter
				}
			}
		}
	}

	/**
	 *	Parse a Class File and collects Methods and their Parameters and Lines.
	 *	@access		private
	 *	@param		string		$content		PHP code string
	 *	@return		void
	 */
	private function parseCodeForMethods( $content )
	{
		$this->methods	= array();																	//  reset list of found methods
		$open		= FALSE;																		//  initial: no method found, yet
		$content	= preg_replace( "@/\*.*\*/@Us", "", $content );									//  remove all slash-star-comments
		$content	= preg_replace( '@".*"@Us', "", $content );										//  remove all strings
		$content	= preg_replace( "@'.*'@Us", "", $content );										//  remove all strings
		$content	= preg_replace( "@#.+\n@U", "", $content );										//  remove all hash-comments
		$content	= preg_replace( "@\s+\n@U", "\n", $content );									//  trailing white space
		$content	= preg_replace( "@\n\n@U", "\n", $content );									//  remove double line breaks
		$content	= preg_replace( "@//\s*[\w|\s]*\n@U", "\n", $content );							//  remove comment lines
		$matches	= array();																		//  prepare empty matches array
		$count		= 0;																			//  initial: open bracket counter
		foreach( explode( "\n", $content ) as $nr => $line )										//  iterate code lines
		{
			$line	= trim( $line );																//  remove leading and trailing white space
			if( !$open )																			//  if no method found, yet
			{
				$regExp		= "@^(final )?(private |protected |public )?(static )?function @";		//  prepare regular expression for method/function signature
				if( preg_match( $regExp, $line ) )													//  line is method/function signature
				{
					$regExp	= "@^.*function ([^(]+) ?\((.*)\).*$@i";								//  prepare regular expression for method/function name and parameters
					$name	= preg_replace( $regExp, "\\1____\\2", $line );							//  find method/function name and parameters
					$parts	= explode( "____", $name );												//  split name and parameters
					$open	= $parts[0];															//  note found method/function
					$matches[$open]['variables']	= array();										//  prepare empty method/function parameter list
					$matches[$open]['lines']		= array();										//  prepare empty method/function line list
					if( isset( $parts[1] ) && trim( $parts[1] ) )									//  parameters are defined
					{
						$params	= explode( ",", $parts[1] );										//  split parameters
						foreach( $params as $param )												//  iterate parameters
						{
							$regExp		= '@^([a-z0-9_]+ )?&?\$(.+)(\s?=\s?\S+)?$@Ui';				//  prepare regular expression for parameter name
							$param		= preg_replace( $regExp, "\\2", trim( $param ) );			//  get clean parameter name
							$matches[$open]['variables'][$param]	= 0;							//  note parameter in method variable list
						}
					}
					if( preg_match( "/\{$/", $line ) )												//  signature line ends with opening bracket
						$count++;																	//  increase open bracket counter
				}
			}
			else																					//  inside method code lines
			{
				$matches[$open]['lines'][$nr]	= $line;											//  note method code line for inspection
				if( preg_match( "/^\{$/", $line ) || preg_match( "/\{$/", $line ) )					//  line contains opening bracket
					$count++;																		//  increase open bracket counter
				else if( preg_match( "/^\}/", $line ) || preg_match( "/\}$/", $line ) )				//  line contains closing bracket
					if( !( --$count ) )																//  decrease open bracket counter and if all open brackets are closed
						$open	= FALSE;															//  leave method code mode
			}
		}
		$this->methods	= $matches;																	//  note all found methods and their variables
	}

	/**
	 *	Reads a Class Code and finds unused Variables in Methods.
	 *	@access		public
	 *	@param		string		$code			Code of Class
	 *	@return		void
	 */
	public function readCode( $code )
	{
		$this->parseCodeForMethods( $code );
		$this->inspectParsedMethods();
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
}
?>
