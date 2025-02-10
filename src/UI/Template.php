<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Template Class.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@deprecated		use CeusMedia::TemplateEngine instead
 *	@todo			to be removed in 0.9.1
 */

namespace CeusMedia\Common\UI;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Alg\Obj\MethodFactory as ObjectMethodFactory;
use CeusMedia\Common\Deprecation;
use CeusMedia\Common\Exception\Template as TemplateException;
use ArrayObject;
use InvalidArgumentException;
use ReflectionObject;

/**
 *	Template Class.
 *	@category		Library
 *	@package		CeusMedia_Common_UI
 *	@author			David Seebacher <dseebacher@gmail.com>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *
 *	<b>Syntax of a template file</b>
 *	- comment <%--comment--%>				 | will be removed on render
 *	- optional tag <%?tagName%>              | will be replaced, even with empty string
 *	- non optional tag <%tagName%>           | will be replaced but must be defined and have content
 *	- optional content <%?--optional--%>     | content will be shown or removed depending on ::$removeOptional
 *  - load(file.html)                        | load another template relatively to this one an insert here
 *
 *	<b>Example</b>
 *	<code>
 *	<html>
 *		<head>
 *			<title><%?pageTitle%></title>
 *			<%load(meta.html)%>
 *		</head>
 *		<body>
 *			<%-- this is a comment --%>
 *			<h1><%title%></h1>
 *			<p><%text%></p>
 *			<%-- just an other comment --%>
 *			<%?-- this content is optional and will be show if $removeOptional is not set to true --%>
 *		</body>
 *	</html>
 *	</code>
 */
class Template
{
	public static bool $removeComments	= FALSE;

	public static bool $removeOptional	= FALSE;

	/**
	 *	Constructor
	 *	@access		public
	 *	@throws		TemplateException				if given template file is not existing
	 */
	public function __construct()
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		Deprecation::getInstance()
			->setErrorVersion( '0.9' )
			->setExceptionVersion( '0.9.1' )
			->message( 'Please use CeusMedia::TemplateEngine instead' );
	}
}
