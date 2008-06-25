<?php

import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.Tag' );

abstract class View
{
	private $config;
	private $logic;
	private $request;
	private $response;
	private $session;

	public function __construct( Environment $environment )
	{
		$this->config		= $environment->getConfiguration();							//  get Configuration Object
		$this->logic		= $environment->getLogic();									//  get Logic Object
		$this->request		= $environment->getRequest();								//  get HTTP Request Object
		$this->response		= $environment->getResponse();								//  get HTTP Response Object
		$this->session		= $environment->getSession();								//  get Session Object

		$this->buildView();
	}
	
	abstract private function buildView();
}
?>