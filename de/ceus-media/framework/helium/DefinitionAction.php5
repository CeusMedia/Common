<?php
import( 'de.ceus-media.framework.helium.Action' );
import( 'de.ceus-media.alg.validation.Predicates' );
import( 'de.ceus-media.alg.validation.DefinitionValidator' );
/**
 *	Generic Definition Action Handler.
 *	@package		framework.helium
 *	@extends		Framework_Helium_Action
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.5
 */
/**
 *	Generic Definition Action Handler.
 *	@package		framework.helium
 *	@extends		Framework_Helium_Action
 *	@uses			Alg_Validation_Predicates
 *	@uses			Alg_Validation_DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2006
 *	@version		0.5
 */
class Framework_Helium_DefinitionAction extends Framework_Helium_Action
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
		$this->validator	= new Alg_Definition_DefinitionValidator;
		if( $this->loadLanguage( 'validator', false, false ) )
			$this->validator->setMessages( $this->lan['validator']['messages'] );
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
		$labels	= $this->lan[$lan_file][$lan_section];

		$this->validator->setLabels( $labels );
		$errors	= array();
		$this->loadDefinition( $file , $form, $this->prefix );
		$fields	= $this->definition->getFields();
		foreach( $fields as $field )
		{
			$data	= $this->definition->getField( $field );
			$value	= $request->get( $data['input']['name'] );
			if( is_string( $value ) )
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

	/**
	 *	Loads Form Definitions.
	 *	@access		protected
	 *	@param		string		$file				Name of XML Definition File (e.g. %PREFIX%#FILE#.xml)
	 *	@param		string		$form				Form Name in XML Definition File
	 *	@return		void
	 */
	protected function loadDefinition( $file , $form )
	{
		$this->definition->setForm( $form );
		$this->definition->setOption( 'prefix', $this->prefix );
		$this->definition->loadDefinition( $file );
	}
}
?>