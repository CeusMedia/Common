<?php
import( 'de.ceus-media.file.File' );
/**
 *	Class to parse Class Source and print UML.
 *	@extends		Object
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
/**
 *	Class to parse Class Source and print UML.
 *	@extends		Object
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			22.06.2005
 *	@version		0.1
 */
class ClassParser
{
	/**	@var		string	$fileName		name of Class File to parse */
	protected $fileName;
	/**	@var		array	$_funcs			List of Functions */
	protected $functions	= array();
	/**	@var		array	$vars			List of Variables */
	protected $vars			= array();
	/**	@var		array	$imports		List of imported Classes */
	protected $imports		= array();
	/**	@var		array	$classData		List of Class Properties */
	protected $classData	= array(
		"desc"	=> array(),
		"uses"	=> array(),
		"todo"	=> array(),
		);
	/**	@var		array	$patterns		Patterns for regular expression */
	var $patterns	= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	fileName		Class Source to parse
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
		$this->patterns	= array(
			"import"		=> "^import",
			"class"			=> "^class",
			"doc_start"		=> "^/##",
			"doc_end"		=> "#/$",
			"doc_data"		=> "^#",
			"function"		=> "^function ",
			"d_extends"		=> "@extends",
			"d_uses"		=> "@uses",
			"d_package"		=> "@package",
			"d_package"		=> "@package",
			"d_subpackage"	=> "@subpackage",
			"d_param"		=> "@param",
			"d_access"		=> "@access",
			"d_author"		=> "@author",
			"d_since"		=> "@since",
			"d_version"		=> "@version",
			"d_license"		=> "@license",
			"d_return"		=> "@return",
			"d_todo"		=> "@todo",
			"d_var"			=> "@var",
			"d_see"			=> "@see"
			);
		$this->_class_props = array(
			"extends"		=> "d_extends",
			"package"		=> "d_package",
			"subpackage"	=> "d_subpackage",
			"license"		=> "d_license",
			"author"		=> "d_author",
			"since"			=> "d_since",
			"version"		=> "d_version",
			"see"			=> "d_see",
			);
			
		$this->_func_props = array(
			"access"		=> "d_access",
			"return"		=> "d_return",
			"author"		=> "d_author",
			"version"		=> "d_version",
			"since"			=> "d_since",
			"see"			=> "d_see",
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
			"class"		=> $this->classData,
			"imports"		=> $this->imports,
			"functions"	=> $this->functions,
			"vars"		=> $this->vars,
			);
		return $data;
	}
	
	/**
	 *	Returns an Array of all Functions.
	 *	@access		public
	 *	@return		array
	 */
	public function getFunctions()
	{
		return array_keys( $this->functions);
	}
	
	/**
	 *	Returns an Array of all Properties of a Functions.
	 *	@access		public
	 *	@return		array
	 */
	public function getFunctionData( $function )
	{
		return $this->functions[$function];
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
	 *	Returns the Value of a Documenation Line determined by a pattern.
	 *	@access		protected
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
	 *	Parses Class Source and stores Information.
	 *	@access		protected
	 *	@return		void
	 */
	protected function parse()
	{
		$inside = false;
		$doc_open = false;
		$func_data = array();

		$f = new File( $this->fileName );
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
					else if( ereg( $this->patterns["function"], $line ) )
					{
						$parts	= explode( "function", $line );
						$function = substr( $parts[1], 0, strpos($parts[1], "(" ) );
						$function = str_replace( "&", "", $function );
						$function = trim( $function );
						$this->functions [$function] = $func_data;
						$func_data = array();
					}
				}
				if( $doc_open )
				{
					$found_pattern = false;
					if( ereg( $this->patterns["doc_data"], $line ) )
					{
						foreach( $this->_func_props as $prop => $pattern )
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
							$parts = explode( "\t", trim( $parts[1] ) );
							$func_data["param"][$parts[1]]['type'] = $parts[0];
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
						$parts = explode( "\t", trim($parts[1] ) );
						$this->vars[$parts[1]]['type'] = $parts[0];
						$access = ( substr( $parts[1], 0, 1) == "_" ) ? "private" : "public";
							$this->vars[$parts[1]]['access'] = $access;
						$this->vars[$parts[1]]['desc'][] = str_replace( array( "#", "/" ), "", $parts[2]);
					}
					if( ereg( $this->patterns['doc_end'], $line ) )
						$doc_open = false;
				}
			}
			else
			{
				if( eregi( "import", $line ) )
				{
					$import = str_replace( array( "import", " ", "(", ")", ";", "'", '"' ), "", $line );
					$parts = explode( ".", $import );
					$this->imports[$parts[count( $parts )-1]] = implode( "/", $parts );
				}
				else if( eregi( $this->patterns["class"], $line ) )
				{
					$parts = explode( " extends ", $line );
					$class = $parts[0];
					$class = str_replace( array( "class", " "), "", $class );
					$this->classData["class"] = $class;
					if( $parts[1] )
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
							foreach( $this->_class_props as $prop => $pattern )
							{
								if( ereg( $this->patterns[$pattern], $line ) )
								{
									$found_pattern = true;
									$this->classData[$prop] = $this->getValueOfDocLine( $line, $pattern );
								}
							}
							if( !$found_pattern && !ereg( $this->patterns['doc_end'], $line ) )
							{
								if( ereg( $this->patterns['d_uses'], $line ) )
								{
									$parts	= explode( $this->patterns["d_uses"], $line );
									$uses	= trim($parts[1]);
									if( !in_array( $uses, $this->classData["uses"] ) )
										$this->classData["uses"][] = $uses; 
								}
								else if( ereg( $this->patterns['d_todo'], $line ) )
								{
									$parts = explode( $this->patterns["d_todo"], $line );
									$todo = trim($parts[1] );
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
	}
	
	/**
	 *	Returns a UML Diagramm of the Class as HTML-Code.
	 *	@access		public
	 *	@param		bool		$showPrivate	List private Methods and Variables
	 *	@param		mixed		$width			Width of HTML-Table
	 *	@return		string
	 */
	public function toUML( $showPrivate = false, $width = 600 )
	{
		$data = $this->getClassData();
		$vars = $funcs = $props = array();
		
		if( count($data['class']['desc'] ) )
			$props[] = "<tr><td colspan='2'>".implode( "<br/>", $data['class']['desc'] )."</td></tr>";
		if( $data['class']['package'] )
			$props[] = "<tr><td>Package</td><td>".$data['class']['package']."</td></tr>";
		if( $data['subpackage'] )
			$props[] = "<tr><td>Subpackage</td><td>".$data['class']['subpackage']."</td></tr>";
		if( $data['class']['extends'] )
			$props[] = "<tr><td>Extends Class</td><td>".$data['class']['extends']."</td></tr>";
		if( count( $data['class']['uses'] ) )
			$props[] = "<tr><td>Uses Classes</td><td>".implode( ", ", $data['class']['uses'] )."</td></tr>";
		if( count( $data['imports'] ) )
			$props[] = "<tr><td>Imports Classes</td><td>".implode( ", ", array_keys($data['imports'] ) )."</td></tr>";
		if( $data['class']['author'] )
			$props[] = "<tr><td>Author</td><td>".$data['class']['author']."</td></tr>";
		if( $data['class']['since'] )
			$props[] = "<tr><td>Since</td><td>".$data['class']['since']."</td></tr>";
		if( $data['class']['version'] )
			$props[] = "<tr><td>Version</td><td>".$data['class']['version']."</td></tr>";
		if( count($data['class']['todo'] ) )
			$props[] = "<tr><td>Todo</td><td>".implode( ", ", $data['class']['todo'] )."</td></tr>";
		$props = implode( "\n\t  ", $props );
		foreach( $data['functions'] as $function => $func_data )
		{
			if( count( $func_data['param'] ) )
			{
				$params = array();
				foreach( $func_data['param'] as $param => $p_data )
					$params [] = $p_data['type']." ".( $p_data['desc'] ? "<acronym title='".$p_data['desc']."'>".$param."</acronym>" : $param );
				$params =  implode( ", ", $params );
			}
			if( count( $func_data['desc'] ) )
				$function = "<acronym title='".implode( " ", $func_data['desc'] )."'>".$function."</acronym>";
			if( $func_data['access'] != "private" || ( $func_data['access'] == "private" && $showPrivate ) )
				$funcs [] = "<tr><td>".$func_data['access']."</td><td>".$func_data['return']."</td><td>".$function."( ".$params.")</td></tr>";
		}
		$funcs = implode( "\n\t  ", $funcs );

		foreach( $data['vars'] as $var => $var_data )
		{
			if( count( $var_data['desc'] ) )
				$var = "<acronym title='".implode( " ", $var_data['desc'] )."'>".$var."</acronym>";
			if( $var_data['access'] != "private" || ( $var_data['access'] == "private" && $showPrivate ) )
				$vars [] = "<tr><td>".$var_data['access']."</td><td>".$var_data['type']."</td><td>".$var."</td></tr>";
		}
		$vars = implode( "\n\t  ", $vars );
$code = "
<table style='border: 1px solid black' cellspacing='0' cellpadding='0' width='".$width."'>
  <tr><th style='text-align:center; font-weight: bold; color: black; background: white; padding: 0px; text-indent:0px'>".$this->classData['class']."</th></tr>
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='' cellpadding='2'>
	  <colgroup><col width='170px'/><col width='430px'/></colgroup>
	  ".$props."
	</table>
  </td></tr>  
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='0' cellpadding='2'>
	  <colgroup><col width='50px'/><col width='120px'/><col width='430px'></colgroup>
	  ".$vars."
	</table>
  </td></tr>  
  <tr><td style='background: #BFBFBF; height:1px'></td></tr>
  <tr><td style='background: white;'>
	<table width='100%' cellspacing='0' cellpadding='2'>
	  <colgroup><col width='50px'/><col width='120px'/><col width='430px'></colgroup>
	  ".$funcs."
	</table>
  </td></tr>  
</table>";
		return $code;	
	}
}
?>