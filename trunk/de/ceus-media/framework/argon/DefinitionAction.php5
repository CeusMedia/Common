<?php
import( 'de.ceus-media.framework.neon.Action' );
import( 'de.ceus-media.file.log.LogFile' );
import( 'de.ceus-media.alg.validation.Predicates' );
import( 'de.ceus-media.alg.validation.DefinitionValidator' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework
 *	@subpackage		argon
 *	@extends		Framework_Neon_Action
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.5
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework
 *	@subpackage		argon
 *	@extends		Framework_Neon_Action
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.5
 */
class DefinitionAction extends Framework_Neon_Action
{
	/**	@var	string		$prefix		Prefix of XML Definition Files */
	var $prefix	= "";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->validator	= new Alg_Validation_DefinitionValidator;
		$this->loadLanguage( 'validator', false, false );
		$this->validator->setMessages( $this->words['validator']['messages'] );
		$this->definition	=& $this->ref->get( 'definition' );
	}

	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages and returns Success.
	 *	@access		public
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form			Name of Form within XML Definition File (e.g. 'addExample' )
	 *	@param		string		$lan_file			Name of Language File (e.g. 'example')
	 *	@param		string		$lan_section		Section in Language File (e.g. 'add')
	 *	@return		bool
	 */
	public function validateForm( $file , $form, $lan_file, $lan_section )
	{
		$request	= $this->ref->get( 'request' );
		$labels		= $this->words[$lan_file][$lan_section];

		$this->validator->setLabels( $labels );
		$errors	= array();
		$this->_loadDefinition( $file , $form, $this->prefix );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$data	= $this->definition->getField( $field );
			$value	= $request->get( $data['input']['name'] );
//			if( is_array( $value ) )
//				$this->messenger->noteError( "Skipped Validation of Field '".$field."' because of Data Type Array" );
//			else
//			{
			if( !is_array( $value ) )
			{
				$_errors	= $this->validator->validate( $field, $data, $value );
				foreach( $_errors as $error )
					$errors[]	= $error;
			}
//			else 
//				$this->messenger->noteError( "Skipped Validation of Field '".$field."'" );
		}
		if( count( $errors ) )
			foreach( $errors as $error )
				$this->messenger->noteError( $error );
		return !count( $errors );
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Runs Validation of Field Definitions againt Request Input and creates Error Messages.
	 *	@access		protected
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@return		void
	 */
	protected function _loadDefinition( $file , $form )
	{
		$this->definition->setForm( $form );
		$this->definition->setOption( 'prefix', $this->prefix );
		$this->definition->loadDefinition( $file );
	}
}
?>