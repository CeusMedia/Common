<?php
/**
 *	Actions for Right Objects.
 *	@package		framework.neon.actions
 *	@extends		Framework_Neon_DefinitionAction
 *	@uses			Framework_Neon_Models_RightObject
 *	@uses			Framework_Neon_Models_RightObjectAction
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.DefinitionAction' );
import( 'de.ceus-media.framework.neon.models.RightObject' );
import( 'de.ceus-media.framework.neon.models.RightObjectAction' );
/**
 *	Actions for Right Objects.
 *	@package		framework.neon.actions
 *	@extends		Framework_Neon_DefinitionAction
 *	@uses			Framework_Neon_Models_RightObject
 *	@uses			Framework_Neon_Models_RightObjectAction
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Actions_RightObjectActions extends Framework_Neon_DefinitionAction
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->loadLanguage( 'right_object' );
	}
	
	/**
	 *	Handles Actions.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
		$config		= $this->ref->get( "config" );
		$request	= $this->ref->get( "request" );
		$auth		= $this->ref->get( 'auth' );

		if( !$auth->isAuthenticated() )
			$this->restart( ";" );

		if( $request->get( 'rightObjectId' ) )
		{
			if( $request->get( 'editObject' ) )
				$this->editObject();
			else if( $request->get( 'removeObject' ) )
				$this->removeObject();
			else if( $request->get( 'addActions' ) )
				$this->addActions();
			else if( $request->get( 'removeActions' ) )
				$this->removeActions();
		}
		else if( $request->get( 'addObject' ) )
			$this->addObject();
	}

	/**
	 *	Adds a Right Object.
	 *	@access		private
	 *	@return		void
	 */
	private function addObject()
	{
		$request	=& $this->ref->get( 'request' );
		if( $this->validateForm( 'right_object', 'addObject', 'right_object', 'add' ) )
		{
			$title = $request->get( 'add_title' );
			$object	= new Framework_Neon_Models_RightObject();
			$objects	= $object->getAllData();
			foreach( $objects as $object )
				if( strtolower( $object['title'] ) == strtolower( $title ) )
					return $this->messenger->noteError( $this->words['right_object']['msg']['error3'], $title );
			$object	= new Framework_Neon_Models_RightObject();
			$data	= array(
				"title"		=> $title,
				"description"	=> $request->get( 'add_description' ),
				);
			$roid	= $object->addData( $data );
			$request->remove( 'add_title' );
			$request->remove( 'add_description' );
			$this->messenger->noteSuccess( $this->words['right_object']['msg']['success1'], $title );
		}
	}

	/**
	 *	Edits a Right Object.
	 *	@access		private
	 *	@return		void
	 */
	private function editObject()
	{
		$request	=& $this->ref->get( 'request' );
		if( $rightObjectId = $request->get( 'rightObjectId' ) )
		{
			if( $this->validateForm( 'right_object', 'editObject', 'right_object', 'edit' ) )
			{
				$title = $request->get( 'edit_title' );
				$object	= new Framework_Neon_Models_RightObject();
				$objects	= $object->getAllData();
				foreach( $objects as $object )
					if( $object['rightObjectId'] != $rightObjectId && strtolower( $object['title'] ) == strtolower( $title ) )
						return $this->messenger->noteError( $this->words['right_object']['msg']['error3'], $title );
				$data	= array(
					"title"		=> $title,
					"description"	=> $request->get( 'edit_description' ),
					);
				$object	= new Framework_Neon_Models_RightObject( $rightObjectId );
				$object->modifyData( $data );
				$this->messenger->noteSuccess( $this->words['right_object']['msg']['success2'], $title );
			}
		}
		else
			$this->messenger->noteError( $this->words['right_object']['msg']['error1'] );
	}

	/**
	 *	Removes a Right Object.
	 *	@access		private
	 *	@return		void
	 *	@todo		finish Implementation
	 */
	private function removeObject()
	{
		$request	=& $this->ref->get( 'request' );
		if( $rightObjectId = $request->get( 'rightObjectId' ) )
		{
			$object	= new Framework_Neon_Models_RightObject( $rightObjectId );
			$data	= $object->getData( true );
			$object->deleteData();
			$request->remove( 'rightObjectId' );
			$this->messenger->noteSuccess( $this->words['right_object']['msg']['success3'], $data['title'] );
		}
	}

	/**
	 *	Adds a Right Actions to a Right Object.
	 *	@access		private
	 *	@return		void
	 */
	private function addActions()
	{
		$request	=& $this->ref->get( 'request' );
		if( $rightObjectId = $request->get( 'rightObjectId' ) )
		{
			$relation	= new Framework_Neon_Models_RightObjectAction();
			$actions	= (array)$request->get( 'actions' );
			foreach( $actions as $action )
			{
				$data	= array(
					"rightObjectId"	=> $rightObjectId,
					"rightActionId"	=> $action,
				);
				$relation->addData( $data );
			}
		}
		else
			$this->messenger->noteError( $this->words['right_object']['msg']['error1'] );
	}

	/**
	 *	Removes Right Actions from a Right Object.
	 *	@access		private
	 *	@return		void
	 */
	private function removeActions()
	{
		$request	= $this->ref->get( 'request' );
		if( $rightObjectId = $request->get( 'rightObjectId' ) )
		{
			$relation	= new Framework_Neon_Models_RightObjectAction();
			$relation->focusForeign( "rightObjectId", $rightObjectId );
			$actions	= (array)$request->get( 'actions_has' );
			foreach( $actions as $action )
			{
				$relation->focusForeign( "rightActionId", $action );
				$relation->deleteData();
			}
		}
		else
			$this->messenger->noteError( $this->words['right_object']['msg']['error1'] );
	}
}
?>