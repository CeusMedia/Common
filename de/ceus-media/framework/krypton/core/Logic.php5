<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.framework.krypton.core.DefinitionValidator' );
import( 'de.ceus-media.framework.krypton.exception.IO' );
import( 'de.ceus-media.framework.krypton.exception.Validation' );
/**
 *	Logic Base Class with Validation
 *	@package		mv2.core
 *	@uses			Core_Registry
 *	@uses			Core_DefinitionValidator
 *	@uses			Exception_Validation
 *	@uses			Exception_IO
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
/**
 *	Logic Base Class with Validation
 *	@package		mv2.core
 *	@uses			Core_Registry
 *	@uses			Core_DefinitionValidator
 *	@uses			Exception_Validation
 *	@uses			Exception_IO
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.2
 */
class Framework_Krypton_Core_Logic
{
	/**	@var	array		$errors			Errors of last Validation */
	protected $errors	= array();
	/**	@var	Registry	$registry		Registry for Objects */
	protected $registry;
	/**	@var	array		$errors			Field Defintion */
	protected $definition;
	
	static public function getCategoryLogic( $category )
	{
		$category	= ucFirst( $category );
		import( "classes.logic.".$category );
		$logic		= eval( "return new Logic_".$category."();" );
		return $logic;
	}

	/**
	 *	Constructor, loads Definition Validator and Field Definition.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->registry		= Framework_Krypton_Core_Registry::getInstance();
		if( $this->registry->has( 'definition' ) )
			$this->definition	= $this->registry->get( 'definition' );
	}
	
	/**
	 *	Returns Table Fields of Model
	 *	@access		public
	 *	@param		string		$model_name		Class Name of Model
	 *	@throws		Exception_IO
	 *	@return		array
	 */
	
	public function getFieldFromModel( $model_name )
	{
		if( class_exists( $model_name, true ) )
		{
			$model	= new $model_name;
			return $model->getFields();
		}
		throw new Framework_Krypton_Exception_IO( "Class '".$model_name."' is not existing." );
	}
	
	/**
	 *	Runs Validation of Field Definitions against Input, creates Error Objects and returns Success.
	 *	@access		protected
	 *	@param		string		$file			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		array		$data			Array of Input Data
	 *	@param		string		$prefix			Prefix used within Fields of Input Data
	 *	@return		bool
	 */
	protected function validateForm( $file, $form, $data, $prefix = "")
	{
		$errors	= array();
		$validator	= new Framework_Krypton_Core_DefinitionValidator;

		$this->loadDefinition( $file , $form );
		$fields	= $this->definition->getFields();

		foreach( $fields as $field )
		{
			$def	= $this->definition->getField( $field );
			$key	= $this->removePrefixFromFieldName( $def['input']['name'], $prefix );
			$value	= isset( $data[$key] ) ? $data[$key] : null;
			
//			print_m( $def );
//			print_m( $data );
//			remark( "key:".$key );
//			remark( "value:".$value );
			if( is_array( $value ) )
			{
				foreach( $value as $entry )
				{
					$errors	= $validator->validateSyntax( $field, $def, $entry );
					if( !count( $errors ) )
						$errors	= $validator->validateSemantics( $field, $def, $entry );
					foreach( $errors as $error )
						$this->noteError( $error );
				}
			}
			else
			{
				$errors	= $validator->validateSyntax( $field, $def, $value );
				if( strlen( $value ) && !count( $errors ) )
					$errors	= $validator->validateSemantics( $field, $def, $value );
				foreach( $errors as $error )
					$this->noteError( $error );
			}
		}
		if( $this->hasErrors() )
		{
			$errors	= $this->errors;
			$this->errors	= array();
			throw new Framework_Krypton_Exception_Validation( "error_not_valid", $errors );
			return false;
		}
		return true;
	}

	/**
	 *	Returns Errors of last Validation.
	 *	@access		public
	 *	@return		bool
	 */
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 *	Notes an Error.
	 *	@access		public
	 *	@param		Logic_Error	$error		Error to note
	 *	@return		void
	 */
	protected function noteError( $error )
	{
		$this->errors[]	= $error;	
	}

	/**
	 *	Resets Errors.
	 *	@access		public
	 *	@return		void
	 */
	public function resetErrors()
	{
		$this->errors	= array();
	}

	/**
	 *	Indicated whether last Validation had Errors.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasErrors()
	{
		return (bool)count( $this->errors );	
	}
	
	/**
	 *	Loads Field Definitions.
	 *	@access		private
	 *	@param		string		$fileKey		File Key of XML Definition File (e.g. #FOLDER.FILE#.xml)
	 *	@return		void
	 */
	private function loadDefinition( $fileKey, $form )
	{
		$this->definition->setForm( $form );
		$this->definition->loadDefinition( $fileKey );
	}

	/**
	 *	Removes Prefix from Field Name.
	 *	@access		protected
	 *	@param		string		$name		Field Name
	 *	@param		string		$prefix		Prefix to be removed
	 *	@return		string
	 */
	static public function removePrefixFromFieldName( $name, $prefix )
	{
		if( $prefix )
		{
			if( preg_match( "@^".$prefix."@", $name ) )
			{
				$name	= preg_replace( "@^".$prefix."@", "", $name );
			}
		}
		return $name;
	}
	
	/**
	 *	Removes Prefix from Fields within an associative Array.
	 *	@access		public
	 *	@param		string		$array		Associative Array of Fields and Values
	 *	@param		string		$prefix		Prefix to be removed
	 *	@return		array
	 */
	static public function removePrefixFromFields( $data, $prefix, $clean = false )
	{
		if( $prefix )
		{
			foreach( $data as $key => $value )
			{
				$newkey	= self::removePrefixFromFieldName( $key, $prefix );
				unset( $data[$key] );
				if( $clean )
				{
					if( $newkey != $key )
					{
						$data[$newkey]	= $value;
					}
					continue;
				}
				$data[$newkey]	= $value;
			}
		}
		return $data;
	}
}
?>