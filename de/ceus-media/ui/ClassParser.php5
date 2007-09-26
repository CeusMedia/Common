<?php
import( 'de.ceus-media.file.File' );
/**
 *	Parses Class and creates UML Diagram.
 *	@package		ui
 *	@extends		Object
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.4
 */
/**
 *	Parses Class and creates UML Diagram.
 *	@package		ui
 *	@extends		Object
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.4
 */
class ClassParser
{
	/**	@var		string		$filename		File name of Class to parse */
	var $_filename;
	/**	@var		array		$_funcs		List of Functions */
	var $_functions	= array();
	/**	@var		array		$_vars		List of Variables */
	var $_vars		= array();
	/**	@var		array		$_imports		List of imported Classes */
	var $_imports		= array();
	/**	@var		array		$_class_data	List of Class Properties */
	var $_class_data	= array(
		"desc"			=> array(),
		"uses"			=> array(),
		"todo"			=> array(),
		"see"			=> array(),
		"license"		=> array(),
		"version"		=> array(),
		"since"			=> array(),
		"author"		=> array(),
		"package"		=> array(),
		"subpackage"	=> array(),
		);
	/**	@var		array		$_patterns	Patterns for regular expression */
	var $_patterns	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$filename		File name of Class to parse 
	 *	@return		void
	 */
	public function __construct( $filename )
	{
		$this->_filename = $filename;
		$this->_patterns = array(
			"import"			=> "^import",
			"class"			=> "^class",
			"doc_start"		=> "^/##",
			"doc_end"		=> "#/$",
			"doc_data"		=> "^#",
			"function"		=> "^function ",
			"function"		=> "^(static |final | private |protected |public )*function ",
			"d_extends"		=> "@extends",
			"d_uses"			=> "@uses",
			"d_package"		=> "@package",
			"d_package"		=> "@package",
			"d_subpackage"	=> "@subpackage",
			"d_param"		=> "@param",
			"d_access"		=> "@access",
			"d_author"		=> "@author",
			"d_since"			=> "@since",
			"d_version"		=> "@version",
			"d_license"		=> "@license",
			"d_return"		=> "@return",
			"d_todo"			=> "@todo",
			"d_var"			=> "@var",	
			"d_see"			=> "@see"
			);
		$this->_class_props = array(
			"implements"	=> "d_implements",
			"extends"		=> "d_extends",
			"package"		=> "d_package",
			"subpackage"	=> "d_subpackage",
			"license"		=> "d_license",
			"author"		=> "d_author",
			"version"		=> "d_version",
			"since"			=> "d_since",
			"see"			=> "d_see",
			);
			
		$this->_func_props = array(
			"access"		=> "d_access",
			"author"		=> "d_author",
			"return"		=> "d_return",
			"version"		=> "d_version",
			"since"			=> "d_since",
			"see"			=> "d_see",
			);

		$this->_parse();
	}

	/**
	 *	Parses Class and stores Class data.
	 *	@access		private
	 *	@return		void
	 */
	function _parse()
	{
		$inside = false;
		$doc_open	= false;
		$func_data	= array();

		$f = new File( $this->_filename );
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
					if( ereg( $this->_patterns['doc_start'], $line ) )
						$doc_open = true;
					else if( ereg( $this->_patterns["function"], $line ) )
					{
						$parts = explode( "function", $line );
						$function = substr( $parts[1], 0, strpos($parts[1], "(" ) );
						$function = str_replace( "&", "", $function );
						$function = trim( $function );
						$this->_functions[$function] = $func_data;
						$func_data = array ();
					}
				}
				if( $doc_open )
				{
					$found_pattern = false;
					if( ereg( $this->_patterns["doc_data"], $line ) )
					{
						foreach( $this->_func_props as $prop => $pattern )
						{
							if( ereg( $this->_patterns[$pattern], $line ) )
							{
								$found_pattern = true;
								$func_data[$prop] = $this->_getValueOfDocLine( $line, $pattern );
							}
						}
						if( ereg( $this->_patterns["d_param"], $line ) )
						{
							$parts = explode( $this->_patterns["d_param"], $line );
							$parts = explode( "\t", trim($parts[1] ) );
							$func_data["param"][$parts[1]]['type'] = $parts[0];
							$func_data["param"][$parts[1]]['desc'] = $parts[2];
						}
						else if( !$found_pattern && !ereg( $this->_patterns['doc_end'], $line ) )
						{
							$desc = ereg_replace( $this->_patterns["doc_data"], "", $line );
							$desc = trim( $desc );
							if( $desc )
								$func_data["desc"][] = $desc;
						}
					}
					else	if( ereg( $this->_patterns["d_var"], $line ) )
					{
						$parts = explode( $this->_patterns["d_var"], $line );
						$parts = explode( "\t", trim($parts[1] ) );
						$this->_vars[$parts[1]]['type'] = $parts[0];
						$access = ( substr($parts[1], 0, 1) == "_" ) ? "private" : "public";
							$this->_vars[$parts[1]]['access'] = $access;
						$this->_vars[$parts[1]]['desc'][] = str_replace( array( "#", "/" ), "", $parts[2] );
					}
					if( ereg( $this->_patterns['doc_end'], $line ) )
						$doc_open = false;
				}
			}
			else
			{
				if( eregi( $this->_patterns["import"], $line ) )
				{
					$import = str_replace( array( "import", " ", "(", ")", ";", "'", '"' ), "", $line );
					$parts = explode( ".", $import );
					$this->_imports[$parts[count( $parts )-1]] = implode( "/", $parts );
				}
				else if( eregi( $this->_patterns["class"], $line ) )
				{
					$parts = explode( " extends ", $line );
					$class = explode( " implements ", $parts[0] );
					$class = $class[0];
//					$class = str_replace( array( "class", " " ), "", $class );
					$this->_class_data["class"] = $class;
					if( $parts[1] )
						$this->_class_data["extends"] = $parts[1];
					$inside = true;
				}
				else if( !$doc_open )
				{
					if( ereg( $this->_patterns['doc_start'], $line ) )
						$doc_open = true;
				}
				else if( $doc_open )
				{
					if( ereg( $this->_patterns['doc_end'], $line ) )
						$doc_open = false;
					else
					{
						if( ereg( $this->_patterns["doc_data"], $line ) )
						{
							$found_pattern = false;
							foreach( $this->_class_props as $prop => $pattern )
							{
								if( ereg( $this->_patterns[$pattern], $line ) )
								{
									$found_pattern = true;
									$this->_class_data[$prop][] = $this->_getValueOfDocLine( $line, $pattern );
								}
							}
							if( !$found_pattern && !ereg( $this->_patterns['doc_end'], $line ) )
							{
								if( ereg( $this->_patterns['d_uses'], $line ) )
								{
									$parts = explode( $this->_patterns["d_uses"], $line );
									$uses = trim( $parts[1] );
									if( !in_array ( $uses, $this->_class_data["uses"] ) )
										$this->_class_data["uses"][] = $uses; 
								}
								else if( ereg( $this->_patterns['d_todo'], $line ) )
								{
									$parts = explode( $this->_patterns["d_todo"], $line );
									$todo = trim( $parts[1] );
									if( !in_array( $todo, $this->_class_data["todo"] ) )
										$this->_class_data["todo"][] = $todo; 
								}
								else
								{
									$desc = ereg_replace( $this->_patterns["doc_data"], "", $line );
									$desc = trim( $desc );
									if( $desc && !in_array( $desc, $this->_class_data["desc"] ) )
										$this->_class_data["desc"][] = $desc;
								}
							}
						}
					}	
				}
			}
		}
		foreach( $this->_class_props as $prop => $pattern )
			if( is_array( $this->_class_data[$prop] ) )
				$this->_class_data[$prop]	= array_unique( $this->_class_data[$prop] );
	}
	
	/**
	 *	Returns the Value of a Documentation Line determined by a pattern.
	 *	@access		private
	 *	@param		string		$line			Documentation Line
	 *	@param		string		$pattern		Pattern to read Docuementation Line
	 *	@return		string
	 */
	function _getValueOfDocLine( $line, $pattern )
	{
		$parts	= explode( $this->_patterns[$pattern], $line );
		$value	= trim( $parts[1] );
		return $value;
	}
	
	/**
	 *	Returns an Array of all Functions.
	 *	@access		public
	 *	@return		array
	 */
	function getFunctions()
	{
		return array_keys( $this->_functions );
	}
	
	/**
	 *	Returns an Array of all Properties of a Functions.
	 *	@access		public
	 *	@param		string		$function		Function name
	 *	@return		array
	 */
	function getFunctionData( $function )
	{
		return $this->_functions[$function];
	}
	
	/**
	 *	Returns an Array of all Variables.
	 *	@access		public
	 *	@return		array
	 */
	function getVars()
	{
		return array_keys( $this->_vars );
	}
	
	/**
	 *	Returns an Array of all Properties of a Variable.
	 *	@access		public
	 *	@param		string		$function		Variable name
	 *	@return		array
	 */
	function getVarData( $var )
	{
		return $this->_vars[$var];
	}
	
	/**
	 *	Returns an Array of all imported Classes.
	 *	@access		public
	 *	@return		array
	 */
	function getImports()
	{
		return $this->_imports;
	}
	
	/**
	 *	Returns an Array of all Properties of the Class.
	 *	@access		public
	 *	@return		array
	 */
	function getClassData()
	{
		$data = array(
			"class"		=> $this->_class_data,
			"imports"		=> $this->_imports,
			"functions"	=> $this->_functions,
			"vars"		=> $this->_vars,
			);
		return $data;
	}
	
	/**
	 *	Returns a UML Diagramm of the Class as HTML Code.
	 *	@access		public
	 *	@param		bool			$show_private		Flag: show private Variables & Functions in UML
	 *	@param		string		$template			Template URI to use
	 *	@return		string
	 */
	function toUML( $show_private = false, $template = false )
	{
		if( !$template )
			$template = dirname( __FILE__ )."/ClassParserUML.tpl";
		$data = $this->getClassData();
		$vars = $funcs = $props = array();
		
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

		foreach( $data['functions'] as $function => $func_data )
		{
			if( count( $func_data['param'] ) )
			{
				$params = array();
				foreach( $func_data['param'] as $param => $p_data )
					$params[] = $p_data['type']." ".( $p_data['desc'] ? "<acronym title='".$p_data['desc']."'>".$param."</acronym>" : $param);
				$params =  implode( ", ", $params );
			}
			if( count( $func_data['desc'] ) )
				$function = "<acronym title='".implode( " ", $func_data['desc'] )."'>".$function."</acronym>";
			if( $func_data['access'] != "private" || ( $func_data['access'] == "private" && $show_private ) )
				$funcs[] = "<tr><td>".$func_data['access']."</td><td>".$func_data['return']."</td><td>".$function."( ".$params.")</td></tr>";
		}
		$funcs = implode( "\n\t  ", $funcs );

		
		foreach( $data['vars'] as $var => $var_data )
		{
			if( count( $var_data['desc'] ) )
				$var = "<acronym title='".implode( " ", $var_data['desc'] )."'>".$var."</acronym>";
			if( $var_data['access'] != "private" || ( $var_data['access'] == "private" && $show_private ) )
				$vars[] = "<tr><td>".$var_data['access']."</td><td>".$var_data['type']."</td><td>".$var."</td></tr>";
		}
		$vars = implode( "\n\t  ", $vars );
		require_once( $template );
		return $code;	
	}
}
?>