<?php
import( 'de.ceus-media.framework.neon.DefinitionAction' );
import( 'de.ceus-media.framework.neon.models.RightAction' );
/**
 *	Actions for Right Actions.
 *	@package		framework.neon.actions
 *	@extends		Framework_Neon_DefinitionAction
 *	@uses			Framework_Neon_Models_RightAction
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
/**
 *	Actions for Right Actions.
 *	@package		framework.neon.actions
 *	@extends		Framework_Neon_DefinitionAction
 *	@uses			Framework_Neon_Models_RightAction
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.01.2007
 *	@version		0.2
 */
class Framework_Neon_Actions_RightActionActions extends Framework_Neon_DefinitionAction
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->loadLanguage( 'right_action' );
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

		if( $request->get( 'rightActionId' ) )
		{
			if( $request->get( 'editAction' ) )
				$this->editAction();
			else if( $request->get( 'removeAction' ) )
				$this->removeAction();
		}
		else if( $request->get( 'addAction' ) )
			$this->addAction();
	}

	/**
	 *	Adds a Right Action.
	 *	@access		protected
	 *	@return		void
	 */
	protected function addAction()
	{
		$request	= $this->ref->get( 'request' );
		if( $this->validateForm( 'right_action', 'addAction', 'right_action', 'add' ) )
		{
			$title	= $request->get( 'add_title' );
			$action	= new Framework_Neon_Models_RightAction();
			$actions	= $action->getAllData();
			foreach( $actions as $action )
				if( strtolower( $action['title'] ) == strtolower( $title ) )
					return $this->messenger->noteError( $this->words['right_action']['msg']['error3'], $title );
			$action	= new Framework_Neon_Models_RightAction();
			$data	= array(
				"title"		=> $title,
				"description"	=> $request->get( 'add_description' ),
				);
			$roid	= $action->addData( $data );
			$request->remove( 'add_title' );
			$request->remove( 'add_description' );
			$this->messenger->noteSuccess( $this->words['right_action']['msg']['success1'], $title );
		}
	}

	/**
	 *	Edits a Right Action.
	 *	@access		protected
	 *	@return		void
	 */
	protected function editAction()
	{
		$request	= $this->ref->get( 'request' );
		if( $rightActionId = $request->get( 'rightActionId' ) )
		{
			if( $this->validateForm( 'right_action', 'editAction', 'right_action', 'edit' ) )
			{
				$title = $request->get( 'edit_title' );
				$action	= new Framework_Neon_Models_RightAction();
				$actions	= $action->getAllData();
				foreach( $actions as $action )
					if( $action['rightActionId'] != $rightActionId && strtolower( $action['title'] ) == strtolower( $title ) )
						return $this->messenger->noteError( $this->words['right_action']['msg']['error3'], $title );
				$data	= array(
					"title"		=> $title,
					"description"	=> $request->get( 'edit_description' ),
					);
				$action	= new Framework_Neon_Models_RightAction( $rightActionId );
				$action->modifyData( $data );
				if( $request->get( 'edit_action' ) == "add" )
					$request->remove( 'rightActionId' );
				$this->messenger->noteSuccess( $this->words['right_action']['msg']['success2'], $title );
			}
		}
		else
			$this->messenger->noteError( $this->words['right_action']['msg']['error1'] );
	}

	/**
	 *	Removes a Right Action.
	 *	@access		protected
	 *	@return		void
 	 *	@todo		finish Implementation
	 */
	protected function removeAction()
	{
		$request	= $this->ref->get( 'request' );
		if( $rightActionId = $request->get( 'rightActionId' ) )
		{
			$action	= new Framework_Neon_Models_RightAction( $rightActionId );
			$data	= $action->getData( true );
			$action->deleteData();
			$request->remove( 'rightActionId' );
			$this->messenger->noteSuccess( $this->words['right_action']['msg']['success3'], $data['title'] );
		}
	}
}
?>