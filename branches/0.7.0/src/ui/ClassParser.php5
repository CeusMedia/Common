<?php
/**
 *	Parses Class and creates UML Diagram.
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
 *	@package		ui
 *	@extends		Object
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.06.2005
 *	@version		0.4
 */
import( 'de.ceus-media.file.Reader' );
/**
 *	Parses Class and creates UML Diagram.
 *	@package		ui
 *	@extends		Object
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.06.2005
 *	@version		0.4
 */
class ClassParser
{
	/**	@var		string		$fileName		File name of Class to parse */
	protected $fileName;
	/**	@var		array		$funcs			List of Functions */
	protected $methods		= array();
	/**	@var		array		$vars			List of Variables */
	protected $vars				= array();
	/**	@var		array		$imports		List of imported Classes */
	protected $imports			= array();
	/**	@var		array		$classData		List of Class Properties */
	protected $classData		= array(
		"package"		=> "",
		"desc"			=> array(),
		"extends"		=> "",
		"implements"	=> array(),
		"uses"			=> array(),
		"todo"			=> array(),
		"see"			=> array(),
		"license"		=> array(),
		"copyright"		=> array(),
		"link"			=> array(),
		"version"		=> array(),
		"since"			=> array(),
		"author"		=> array(),
		"package"		=> array(),
		"subpackage"	=> array(),
		);
	/**	@var		array		$patterns		Patterns for regular expression */
	protected $patterns	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of Class to parse 
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName = $fileName;
		$this->patterns = array(
			"import"		=> "^import",
			"class"			=> "^(final |abstract )*(class) ",
			"doc_start"		=> "^/##",
			"doc_end"		=> "#/$",
			"doc_data"		=> "^#",
			"method"		=> "^function ",
			"method"		=> "^(static |final |private |protected |public )*function ",
			"d_package"		=> "@package",
			"d_extends"		=> "@extends",
			"d_implements"	=> "@implements",
			"d_uses"		=> "@uses",
			"d_package"		=> "@package",
			"d_subpackage"	=> "@subpackage",
			"d_param"		=> "@param",
			"d_access"		=> "@access",
			"d_author"		=> "@author",
			"d_since"		=> "@since",
			"d_version"		=> "@version",
			"d_license"		=> "@license",
			"d_copyright"	=> "@copyright",
			"d_return"		=> "@return",
			"d_todo"		=> "@todo",
			"d_var"			=> "@var",	
			"d_see"			=> "@see",
			"d_link"		=> "@link",
			"d_throws"		=> "@throws",
			);
		$this->classProperties = array(
			"package"		=> "d_package",
			"implements"	=> "d_implements",
			"extends"		=> "d_extends",
			"package"		=> "d_package",
			"subpackage"	=> "d_subpackage",
			"license"		=> "d_license",
			"copyright"		=> "d_copyright",
			"link"			=> "d_link",
			"author"		=> "d_author",
			"version"		=> "d_version",
			"since"			=> "d_since",
			"see"			=> "d_see",
			);
			
		$this->methodProperties = array(
			"access"		=> "d_access",
			"author"		=> "d_author",
			"return"		=> "d_return",
			"version"		=> "d_version",
			"since"			=> "d_since",
			"see"			=> "d_see",
			"throws"		=> "d_throws",
			);

		$this->parse();
	}

	/**
	 *	Returns an Array of all Properties of the Class.
	 *	@access		public
	 *	@return		array
	 */
	public function getClassData()
	{
		$data = array(
			"class"			=> $this->classData,
			"imports"		=> $this->imports,
			"methods"		=> $this->methods,
			"vars"			=> $this->vars,
			);
		return $data;
	}
	
	/**
	 *	Returns an Array of all imported Classes.
	 *	@access		public
	 *	@return		array
	 */
	public function getImports()
	{
		return $this->imports;
	}
	
	/**
	 *	Returns an Array of all Properties of a Method.
	 *	@access		public
	 *	@param		string		$method		Method name
	 *	@return		array
	 */
	public function getMethodData( $method )
	{
		return $this->methods[$method];
	}
	
	/**
	 *	Returns an Array of all Methods.
	 *	@access		public
	 *	@return		array
	 */
	public function getMethods()
	{
		return array_keys( $this->methods );
	}
	
	/**
	 *	Returns the Value of a Documentation Line determined by a pattern.
	 *	@access		protected
	 *	@param		string		$line			Documentation Line
	 *	@param		string		$pattern		Pattern to read Docuementation Line
	 *	@return		string
	 */
	protected function getValueOfDocLine( $line, $pattern )
	{
		$parts	= explode( $this->patterns[$pattern], $line );
		$value	= trim( $parts[1] );
		return $value;
	}
	
	/**
	 *	Returns an Array of all Properties of a Variable.
	 *	@access		public
	 *	@param		string		$var		Variable name
	 *	@return		array
	 */
	public function getVarData( $var )
	{
		return $this->vars[$var];
	}
	
	/**
	 *	Returns an Array of all Variables.
	 *	@access		public
	 *	@return		array
	 */
	public function getVars()
	{
		return array_keys( $this->vars );
	}
	
	/**
	 *	Parses Class and stores Class data.
	 *	@access		protected
	 *	@return		void
	 */
	protected function parse()
	{
		$inside = false;
		$doc_open	= false;
		$func_data	= array();

		$f = new File_Reader( $this->fileName );
		$lines = $f->readArray();
		array_pop( $lines );
		array_shift( $lines ); 
		foreach( $lines as $line )
		{
			$line = trim( $line );
			$line = str_replace( array( "*", "\t\t", "\t\t" ), array( "#", "\t", "\t" ), $line );
			if( $inside )
			{
				if( !$doc_open )
				{
					if( ereg( $this->patterns['doc_start'], $line ) )
						$doc_open = true;
					else if( ereg( $this->patterns["method"], $line ) )
					{
						$parts = explode( "function", $line );
						$method = substr( $parts[1], 0, strpos($parts[1], "(" ) );
						$method = str_replace( "&", "", $method );
						$method = trim( $method );
						$this->methods[$method] = $func_data;
						$func_data = array ();
					}
				}
				if( $doc_open )
				{
					$found_pattern = false;
					if( ereg( $this->patterns["doc_data"], $line ) )
					{
						foreach( $this->methodProperties as $prop => $pattern )
						{
							if( ereg( $this->patterns[$pattern], $line ) )
							{
								$found_pattern = true;
								$func_data[$prop] = $this->getValueOfDocLine( $line, $pattern );
							}
						}
						if( ereg( $this->patterns["d_param"], $line ) )
						{
							$parts = explode( $this->patterns["d_param"], $line );
							$parts = explode( "\t", trim($parts[1] ) );
							$func_data["param"][$parts[1]]['type'] = $parts[0];
							if( isset( $parts[2] ) )
								$func_data["param"][$parts[1]]['desc'] = $parts[2];
						}
						else if( !$found_pattern && !ereg( $this->patterns['doc_end'], $line ) )
						{
							$desc = ereg_replace( $this->patterns["doc_data"], "", $line );
							$desc = trim( $desc );
							if( $desc )
								$func_data["desc"][] = $desc;
						}
					}
					else	if( ereg( $this->patterns["d_var"], $line ) )
					{
						$parts = explode( $this->patterns["d_var"], $line );
						$parts = preg_split( "@\t+@", trim($parts[1] ) );
						$this->vars[$parts[1]]['type'] = $parts[0];
						$access = ( substr($parts[1], 0, 1) == "_" ) ? "private" : "public";
							$this->vars[$parts[1]]['access'] = $access;
						$this->vars[$parts[1]]['desc'][] = str_replace( array( "#", "/" ), "", $parts[2] );
					}
					if( ereg( $this->patterns['doc_end'], $line ) )
						$doc_open = false;
				}
			}
			else
			{
				if( eregi( $this->patterns["import"], $line ) )
				{
					$import = str_replace( array( "import", " ", "(", ")", ";", "'", '"' ), "", $line );
					$parts = explode( ".", $import );
					$this->imports[$parts[count( $parts )-1]] = implode( "/", $parts );
				}
				else if( eregi( $this->patterns["class"], $line ) )
				{
					$line	= preg_replace( "@".$this->patterns["class"]."@i", "", $line );
					$parts = explode( " extends ", $line );
					$class = explode( " implements ", $parts[0] );
					$class = $class[0];
					
//					$class = str_replace( array( "class", " " ), "", $class );
					$this->classData["class"] = $class;
					if( isset( $parts[1] ) )
						$this->classData["extends"] = $parts[1];
					$inside = true;
				}
				else if( !$doc_open )
				{
					if( ereg( $this->patterns['doc_start'], $line ) )
						$doc_open = true;
				}
				else if( $doc_open )
				{
					if( ereg( $this->patterns['doc_end'], $line ) )
						$doc_open = false;
					else
					{
						if( ereg( $this->patterns["doc_data"], $line ) )
						{
							$found_pattern = false;
							foreach( $this->classProperties as $prop => $pattern )
							{
								if( ereg( $this->patterns[$pattern], $line ) )
								{
									$found_pattern = true;
									$this->classData[$prop][] = $this->getValueOfDocLine( $line, $pattern );
								}
							}
							if( !$found_pattern && !ereg( $this->patterns['doc_end'], $line ) )
							{
								if( ereg( $this->patterns['d_uses'], $line ) )
								{
									$parts = explode( $this->patterns["d_uses"], $line );
									$uses = trim( $parts[1] );
									if( !in_array ( $uses, $this->classData["uses"] ) )
										$this->classData["uses"][] = $uses; 
								}
								else if( ereg( $this->patterns['d_todo'], $line ) )
								{
									$parts = explode( $this->patterns["d_todo"], $line );
									$todo = trim( $parts[1] );
									if( !in_array( $todo, $this->classData["todo"] ) )
										$this->classData["todo"][] = $todo; 
								}
								else
								{
									$desc = ereg_replace( $this->patterns["doc_data"], "", $line );
									$desc = trim( $desc );
									if( $desc && !in_array( $desc, $this->classData["desc"] ) )
										$this->classData["desc"][] = $desc;
								}
							}
						}
					}	
				}
			}
		}
		foreach( $this->classProperties as $prop => $pattern )
			if( isset( $this->classData[$prop] ) && is_array( $this->classData[$prop] ) )
				$this->classData[$prop]	= array_unique( $this->classData[$prop] );
	}
	
	/**
	 *	Returns a UML Diagramm of the Class as HTML Code.
	 *	@access		public
	 *	@param		bool		$showPrivate		Flag: show private Variables & Functions in UML
	 *	@param		string		$template			Template URI to use
	 *	@return		string
	 */
	public function toUML( $showPrivate = FALSE, $template = FALSE )
	{
		if( !$template )
			$template = dirname( __FILE__ )."/ClassParserUML.tpl";
		$data = $this->getClassData();
		$vars = $methods = $props = array();
		
		if( count( $data['class']['desc']))
			$props['class']['desc']		= implode( "<br/>", $data['class']['desc'] );
		if( $data['class']['package'])
			$props['class']['package']	= $data['class']['package'][0];
		if( $data['subpackage'])
			$props['class']['subpackage']	= $data['class']['subpackage'][0];
		if( $data['class']['extends'])
			$props['class']['extends']	= implode( ", ", $data['class']['extends'] );
		if( count( $data['class']['uses']))
			$props['class']['uses']		= implode( ", ", $data['class']['uses'] );
		if( count( $data['imports']))
			$props['imports']			= implode( ", ", array_keys($data['imports'] ) );
		if( $data['class']['author'])
			$props['class']['author']		= implode( ", ", $data['class']['author'] );
		if( $data['class']['since'])
			$props['class']['author']		= $data['class']['since'][0];
		if( $data['class']['version'])
			$props['class']['version']		= $data['class']['version'][0];
		if( count($data['class']['todo']))
			$props['class']['todo']		= implode( ", ", $data['class']['todo'] );
//		$props = implode ("\n\t  ", $props);

		foreach( $data['methods'] as $methodName => $methodData )
		{
			if( count( $methodData['param'] ) )
			{
				$params = array();
				foreach( $methodData['param'] as $param => $p_data )
					$params[] = $p_data['type']." ".( $p_data['desc'] ? "<acronym title='".$p_data['desc']."'>".$param."</acronym>" : $param);
				$params =  implode( ", ", $params );
			}
			if( count( $methodData['desc'] ) )
				$methodName	= "<acronym title='".implode( " ", $methodData['desc'] )."'>".$methodName."</acronym>";
			if( $methodData['access'] != "private" || ( $methodData['access'] == "private" && $showPrivate ) )
				$methods[] = "<tr><td>".$methodData['access']."</td><td>".$methodData['return']."</td><td>".$methodName."( ".$params.")</td></tr>";
		}
		$methods = implode( "\n\t  ", $methods );

		
		foreach( $data['vars'] as $varName => $varData )
		{
			if( count( $varData['desc'] ) )
				$protected = "<acronym title='".implode( " ", $varData['desc'] )."'>".$var."</acronym>";
			if( $varData['access'] != "private" || ( $varData['access'] == "private" && $showPrivate ) )
				$vars[] = "<tr><td>".$varData['access']."</td><td>".$varData['type']."</td><td>".$varName."</td></tr>";
		}
		$vars = implode( "\n\t  ", $vars );
		require_once( $template );
		return $code;	
	}
}
?>