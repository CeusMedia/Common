<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.framework.krypton.core.DefinitionValidator' );
import( 'de.ceus-media.alg.validation.Predicates' );
import( 'de.ceus-media.framework.krypton.exception.IO' );
import( 'de.ceus-media.framework.krypton.exception.Validation' );
import( 'de.ceus-media.framework.krypton.exception.Logic' );
/**
 *	Logic Base Class with Validation
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_DefinitionValidator
 *	@uses			Framework_Krypton_Core_DefinitionValidator
 *	@uses			Alg_Validation_Predicates
 *	@uses			Framework_Krypton_Exception_Validation
 *	@uses			Framework_Krypton_Exception_IO
 *	@uses			Framework_Krypton_Exception_Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.6
 */
/**
 *	Logic Base Class with Validation
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_DefinitionValidator
 *	@uses			Framework_Krypton_Core_DefinitionValidator
 *	@uses			Alg_Validation_Predicates
 *	@uses			Framework_Krypton_Exception_Validation
 *	@uses			Framework_Krypton_Exception_IO
 *	@uses			Framework_Krypton_Exception_Logic
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2007
 *	@version		0.6
 */
class Framework_Krypton_Core_Logic
{
	/**	@var	Registry	$registry		Registry for Objects */
	protected $registry;

	/**
	 *	Constructor, loads Definition Validator and Field Definition.
	 *	@access		public
	 *	@param		string		$predicateClass		Class holding Validation Predicates
	 *	@return		void
	 */
	public function __construct()
	{
		$this->registry			= Framework_Krypton_Core_Registry::getInstance();
	}

	/**
	 *	Returns Table Fields of Model
	 *	@access		public
	 *	@param		string		$modelName		Class Name of Model
	 *	@throws		Exception_IO
	 *	@return		array
	 */

	public static function getFieldFromModel( $modelName )
	{
		if( class_exists( $modelName, true ) )
		{
			$model	= new $modelName;
			return $model->getFields();
		}
		throw new Framework_Krypton_Exception_IO( 'Class "'.$modelName.'" is not existing.' );
	}

	/**
	 *	Runs Validation of Field Definitions against Input, creates Error Objects and returns Success.
	 *	@access		protected
	 *	@param		string		$file			Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		array		$data			Array of Input Data
	 *	@param		string		$prefix			Prefix used within Fields of Input Data
	 *	@param		string		$predicateClass	Class holding Validation Predicates
	 *	@throws		Framework_Krypton_Exception_Validation
	 *	@return		bool
	 */
	protected static function validateForm( $file, $form, &$data, $prefix = "", $predicateClass = "Alg_Validation_Predicates" )
	{
		$validator	= new Framework_Krypton_Core_DefinitionValidator( $predicateClass );
		$errors		= array();

		$definition	= self::loadDefinition( $file , $form );
		$fields		= $definition->getFields();
		foreach( $fields as $field )
		{
			$def	= $definition->getField( $field );
			$key	= self::removePrefixFromFieldName( $def['input']['name'], $prefix );
			$value	= isset( $data[$key] ) ? $data[$key] : NULL;

			//  --  SET NEGATIVE CHECKBOXES  --  //
			if( preg_match( "@check@", $def['input']['type'] ) )
				if( $value === NULL )
					$data[$field]	= $value	= (int) $def['input']['default'];

			if( is_array( $value ) )
				foreach( $value as $entry )
					$errors	= array_merge( $errors, $validator->validate( $field, $def, $entry, $prefix ) );
			else
				$errors	= array_merge( $errors, $validator->validate( $field, $def, $value, $prefix ) );
		}
		if( $errors )
			throw new Framework_Krypton_Exception_Validation( "error_not_valid", $errors, $form );
		return true;
	}

	/**
	 *	Loads Field Definitions.
	 *	@access		private
	 *	@param		string		$fileKey		File Key of XML Definition File (e.g. #FOLDER.FILE#.xml)
	 *	@return		void
	 */
	private static function loadDefinition( $fileKey, $form )
	{
		$registry		= Framework_Krypton_Core_Registry::getInstance();
		if( $registry->has( 'definition' ) )
			$definition	= $registry->get( 'definition' );
		
		$definition->setForm( $form );
		$definition->loadDefinition( $fileKey );
		return $definition;
	}

	/**
	 *	Removes Prefix from Field Name.
	 *	@access		protected
	 *	@param		string		$name		Field Name
	 *	@param		string		$prefix		Prefix to be removed
	 *	@return		string
	 */
	public static function removePrefixFromFieldName( $name, $prefix )
	{
		if( $prefix )
			if( preg_match( "@^".$prefix."@", $name ) )
				$name	= preg_replace( "@^".$prefix."@", "", $name );
		return $name;
	}

	/**
	 *	Removes Prefix from Fields within an associative Array.
	 *	@access		public
	 *	@param		string		$array		Associative Array of Fields and Values
	 *	@param		string		$prefix		Prefix to be removed
	 *	@return		array
	 */
	public static function removePrefixFromFields( $data, $prefix, $clean = true )
	{
		if( !$prefix )
			return $data;
		$list	= array();
		foreach( $data as $key => $value )
		{
			$newkey	= self::removePrefixFromFieldName( $key, $prefix );
			if( $newkey != $key )
				$list[$newkey]	= $value;
			else if( !$clean )
				$list[$key] = $value;
		}
		return $list;
	}
}
?>