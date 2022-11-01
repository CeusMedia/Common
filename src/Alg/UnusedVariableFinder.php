<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Finds not used variables within PHP methods or functions.
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
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

use CeusMedia\Common\FS\File\Reader as FileReader;
use InvalidArgumentException;

/**
 *	Finds not used variables within PHP methods or functions.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2015-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class UnusedVariableFinder
{
	/** @var		array		$methods 		Array of collect Method Parameters and Code Lines */
	protected $methods	= [];

	/** @var		array		$vars 			Array of collect Method Variables */
	protected $vars		= [];

	/**
	 *	Returns an Array of Methods and their Variables.
	 *	@access		public
	 *	@return		int
	 */
	public function countUnusedVariables(): int
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
	 *	@return		int
	 */
	public function countVariables(): int
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
	public function getUnusedVars( ?string $method = NULL ): array
	{
		$list	= [];
		if( !strlen( $method = trim( $method ) ) ){
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
	public function getVariables( string $method ): array
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
	private function inspectParsedMethods( bool $countCalls = FALSE )
	{
		//  iterate before parsed methods
		foreach( $this->methods as $method => $data ){
			//  iterate method/function lines
			foreach( $data['lines'] as $nr => $line ){
				//  prepare regular expression for variable assignment
				$pattern	= "@^ *\t*[$]([a-z0-9_]+)(\t| )+=[^>].*@i";
				//  line contains variable assignment
				if( preg_match( $pattern, $line ) ){
					//  extract variable name from line
					if( $var = trim( preg_replace( $pattern, "\\1", $line ) ) ){
						//  variable is not noted, yet
						if( !array_key_exists( $var, $this->methods[$method]['variables'] ) ){
							//  note newly found variable
							$this->methods[$method]['variables'][$var]	= 0;
						}
					}
				}
				//  iterate known method/function variables
				foreach( $this->methods[$method]['variables'] as $name => $count ){
					//  variable is used and count mode is off
					if( !$countCalls && $count )
						//  skip to next line
						continue;
					if( preg_match( "/\(/", $name ) || preg_match( "/\)/", $name ) )
						xmp( $method."::".$name.' ('.join( ",", array_keys( $this->methods ) ).')' );
					//  remove variable assignment if found
					$line		= preg_replace( "/\$".addslashes( $name )."\s*=/", "", $line );
					//  if variable is used in this line
					if( preg_match( '@\$'.addslashes( $name ).'[^a-z0-9_]@i', $line ) ){
						//  increate variable's use counter
						$this->methods[$method]['variables'][$name]++;
					}
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
	private function parseCodeForMethods( string $content )
	{
		//  initial: no method found, yet
		$open		= FALSE;
		//  remove all slash-star-comments
		$content	= preg_replace( "@/\*.*\*/@Us", "", $content );
//  remove all strings
//		$content	= preg_replace( '@".*"@Us', "", $content );
		//  remove all strings
		$content	= preg_replace( "@'.*'@Us", "", $content );
		//  remove all hash-comments
		$content	= preg_replace( "@#.+\n@U", "", $content );
		//  trailing white space
		$content	= preg_replace( "@\s+\n@U", "\n", $content );
		//  remove double line breaks
		$content	= preg_replace( "@\n\n@U", "\n", $content );
		//  remove comment lines
		$content	= preg_replace( "@//\s*[\w|\s]*\n@U", "\n", $content );
		//  prepare empty matches array
		$matches	= [];
		//  initial: open bracket counter
		$count		= 0;
		//  iterate code lines
		foreach( explode( "\n", $content ) as $nr => $line ){
			//  remove leading and trailing white space
			$line	= trim( $line );
			//  if no method found, yet
			if( !$open ){
				//  prepare regular expression for method/function signature
				$regExp	= '@^(abstract )?(final )?(static )?(protected |private |public )?(static )?function (\w+)\((.*)\)(\s*{\s*)?;?\s*$@s';
				//  line is method/function signature
				if( preg_match( $regExp, $line ) ){
					//  prepare regular expression for method/function name and parameters
					$regExp	= "@^.*function ([^(]+) ?\((.*)\).*$@i";
					//  find method/function name and parameters
					$name	= preg_replace( $regExp, "\\1@@\\2", $line );
					//  split name and parameters
					$parts	= explode( "@@", $name );
					//  note found method/function
					$open	= trim( $parts[0] );
					//  prepare empty method/function parameter list
					$matches[$open]['variables']	= [];
					//  prepare empty method/function line list
					$matches[$open]['lines']		= [];
					//  remove all strings
					$parts[1]	= preg_replace( '@\(.*\)@U', "", $parts[1] );
					//  parameters are defined
					if( isset( $parts[1] ) && trim( $parts[1] ) ){
						//  split parameters
						$params	= explode( ",", $parts[1] );
						//  iterate parameters
						foreach( $params as $param ){
							//  prepare regular expression for parameter name
							$regExp		= '@^(\S+ )?&?\$(.+)(\s?=\s?.*)?$@Ui';
							//  get clean parameter name
							$param		= preg_replace( $regExp, "\\2", trim( $param ) );
							//  note parameter in method variable list
							$matches[$open]['variables'][$param]	= 0;
						}
					}
					//  signature line ends with opening bracket
					if( preg_match( "/\{$/", $line ) )
						//  increase open bracket counter
						$count++;
				}
			}
			//  inside method code lines
			else{
				//  note method code line for inspection
				$matches[$open]['lines'][$nr]	= $line;
				//  line contains opening bracket
				if( preg_match( "/^\{$/", $line ) || preg_match( "/\{$/", $line ) )
					//  increase open bracket counter
					$count++;
				//  line contains closing bracket
				else if( preg_match( "/^\}/", $line ) || preg_match( "/\}$/", $line ) )
					//  decrease open bracket counter and if all open brackets are closed
					if( !( --$count ) )
						//  leave method code mode
						$open	= FALSE;
			}
		}
		//  note all found methods and their variables
		$this->methods	= $matches;
	}

	/**
	 *	Reads a Class Code and finds unused Variables in Methods.
	 *	@access		public
	 *	@param		string		$code			Code of Class
	 *	@return		self
	 */
	public function readCode( string $code ): self
	{
		$this->parseCodeForMethods( $code );
		$this->inspectParsedMethods();
		return $this;
	}

	/**
	 *	Reads a Class File and finds unused Variables in Methods.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Class
	 *	@return		self
	 */
	public function readFile( string $fileName ): self
	{
		$code	= FileReader::load( $fileName );
		$this->readCode( $code );
		return $this;
	}
}
