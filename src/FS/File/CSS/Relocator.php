<?php /** @noinspection */
/** @noinspection PhpMissingParamTypeInspection */
/** @noinspection PhpMissingReturnTypeInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 * Class Relocator
 *
 * @category	Library
 * @package		CeusMedia_Common_FS_File_CSS
 * @author		Stephen Clay <steve@mrclay.org>
 */

namespace CeusMedia\Common\FS\File\CSS;

/**
 * Rewrite file-relative URIs as root-relative in CSS files
 *
 * @category	Library
 * @package		CeusMedia_Common_FS_File_CSS
 * @author		Stephen Clay <steve@mrclay.org>
 */
class Relocator
{
	/**	@var		string					$debugText		rewrite() and rewriteRelative() append debugging information here */
	public static string $debugText			= '';

	/**	@var		string					$className		Defines which class to call as part of callbacks, change this if you extend FS_File_CSS_Relocator */
	protected static string $className		= Relocator::class;

	/**	@var		string					$_currentDir	Directory of this stylesheet */
	private static string $_currentDir		= '';

	/**	@var		string					$_docRoot		DOC_ROOT */
	private static string $_docRoot			= '';

	/**	@var		array					$_symlinks		directory replacements to map symlink targets back to their source (within the document root) E.g. '/var/www/symlink' => '/var/realpath' */
	private static array $_symlinks			= [];

	/**	@var		string|NULL				$_prependPath	Path to prepend */
	private static ?string $_prependPath	= NULL;

	/**
	 *	In CSS content, prepend a path to relative URIs
	 *	@param		string		$css
	 *	@param		string		$path The path to prepend.
	 *	@return		string
	 */
	public static function prepend(string $css, string $path): string
	{
		self::$_prependPath = $path;

		$css = self::_trimUrls($css);

		// append
		$callback = [self::$className, '_processUriCB'];
		$css = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/', $callback, $css);
		$css = preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/', $callback, $css);

		self::$_prependPath = NULL;
		return $css;
	}

	/**
	 * Remove instances of "./" and "../" where possible from a root-relative URI
	 *
	 * @param string $uri
	 * @return string
	 */
	public static function removeDots(string $uri): string
	{
		$uri = str_replace('/./', '/', $uri);
		// inspired by patch from Oleg Cherniy
		do {
			$uri = preg_replace('@/[^/]+/\\.\\./@', '/', $uri, 1, $changed);
		} while ($changed);
		return $uri;
	}

	/**
	 *	In CSS content, rewrite file relative URIs as root relative
	 *
	 *	@param		string			$css
	 *	@param		string			$currentDir		The directory of the current CSS file.
	 *	@param		string|NULL		$docRoot		The document root of the website in which the CSS file resides (default = $_SERVER['DOCUMENT_ROOT']).
	 *	@param		array			$symlinks		If the CSS file is stored in a symlink-ed directory, provide an array of link paths to target paths, where the link paths are within the document root. Because paths need to be normalized for this to work, use "//" to substitute the doc root in the link paths (the array keys). E.g.:
	 *	@example
	 * <code>
	 * // unix
	 * array('//symlink' => '/real/target/path')
	 * // Windows
	 * array('//static' => 'D:\\staticStorage')
	 * </code>
	 *	@return		string
	 */
	public static function rewrite(string $css, string $currentDir, ?string $docRoot = null, array $symlinks = [])
	{
		self::$_docRoot = self::_realpath($docRoot ?: $_SERVER['DOCUMENT_ROOT']);
		self::$_currentDir = self::_realpath($currentDir);
		self::$_symlinks = [];

		// normalize symlinks
		foreach ($symlinks as $link => $target) {
			$link = ($link === '//')
				? self::$_docRoot
				: str_replace('//', self::$_docRoot . '/', $link);
			$link = strtr($link, '/', DIRECTORY_SEPARATOR);
			self::$_symlinks[$link] = self::_realpath($target);
		}

		self::$debugText .= "docRoot    : " . self::$_docRoot . "\n"
						  . "currentDir : " . self::$_currentDir . "\n";
		if (self::$_symlinks) {
			self::$debugText .= "symlinks : " . var_export(self::$_symlinks, true) . "\n";
		}
		self::$debugText .= "\n";

		$css = self::_trimUrls($css);

		// rewrite
		$callback = [self::$className, '_processUriCB'];
		$css = preg_replace_callback('/@import\\s+([\'"])(.*?)[\'"]/', $callback, $css);
		return preg_replace_callback('/url\\(\\s*([^\\)\\s]+)\\s*\\)/', $callback, $css);
	}

	/**
	 * Get a root relative URI from a file relative URI
	 *
	 * <code>
	 * Relocator::rewriteRelative(
	 *	   '../img/hello.gif'
	 * // path of CSS file
	 *	 , '/home/user/www/css'
	 * // doc root
	 *	 , '/home/user/www'
	 * );
	 * // returns '/img/hello.gif'
	 *
	 * // example where static files are stored in a symlinked directory
	 * Relocator::rewriteRelative(
	 *	   'hello.gif'
	 *	 , '/var/staticFiles/theme'
	 *	 , '/home/user/www'
	 *	 , array('/home/user/www/static' => '/var/staticFiles')
	 * );
	 * // returns '/static/theme/hello.gif'
	 * </code>
	 *
	 *	@param		string		$uri			file relative URI
	 *	@param		string		$realCurrentDir	realpath of the current file's directory.
	 *	@param		string		$realDocRoot	realpath of the site document root.
	 *	@param		array		$symlinks		If the file is stored in a symlink-ed directory, provide an array of link paths to real target paths, where the link paths "appear" to be within the document root. E.g.:
	 *	@example
	 * <code>
	 * // unix
	 * array('/home/foo/www/not/real/path' => '/real/target/path')
	 * // Windows
	 * array('C:\\htdocs\\not\\real' => 'D:\\real\\target\\path')
	 * </code>
	 *	@return		string
	 */
	public static function rewriteRelative(string $uri, string $realCurrentDir, string $realDocRoot, array $symlinks = []): string
	{
		// prepend path with current dir separator (OS-independent)
		$path = strtr($realCurrentDir, '/', DIRECTORY_SEPARATOR)
			. DIRECTORY_SEPARATOR . strtr($uri, '/', DIRECTORY_SEPARATOR);

		self::$debugText .= sprintf("file-relative URI  : %s\n", $uri)
						  . sprintf("path prepended     : %s\n", $path);

		// "unresolve" a symlink back to doc root
		foreach ($symlinks as $link => $target) {
			if (0 === strpos($path, (string) $target)) {
				// replace $target with $link
				$path = $link . substr($path, strlen($target));

				self::$debugText .= sprintf("symlink unresolved : %s\n", $path);

				break;
			}
		}
		// strip doc root
		$path = substr($path, strlen($realDocRoot));

		self::$debugText .= sprintf("docroot stripped   : %s\n", $path);

		// fix to root-relative URI
		$uri = strtr($path, '/\\', '//');
		$uri = self::removeDots($uri);

		self::$debugText .= sprintf("traversals removed : %s\n\n", $uri);

		return $uri;
	}

	/**
	 *	@param		array		$m
	 *	@return		string
	 * @noinspection PhpUnusedPrivateMethodInspection
	 */
	private static function _processUriCB(array $m): string
	{
		// $m matched either '/@import\\s+([\'"])(.*?)[\'"]/' or '/url\\(\\s*([^\\)\\s]+)\\s*\\)/'
		$isImport = ($m[0][0] === '@');
		// determine URI and the quote character (if any)
		if ($isImport) {
			$quoteChar = $m[1];
			$uri = $m[2];
		} else {
			// $m[1] is either quoted or not
			$quoteChar = ($m[1][0] === "'" || $m[1][0] === '"')
				? $m[1][0]
				: '';
			$uri = ($quoteChar === '')
				? $m[1]
				: substr($m[1], 1, strlen($m[1]) - 2);
		}
		// analyze URI
		// root-relative
		if ('/' !== $uri[0]
			// protocol (non-data)
			&& false === strpos($uri, '//')
			// data protocol
			&& 0 !== strpos($uri, 'data:')
		) {
			// URI is file-relative: rewrite depending on options
			if (self::$_prependPath === null) {
				$uri = self::rewriteRelative($uri, self::$_currentDir, self::$_docRoot, self::$_symlinks);
			} else {
				$uri = self::$_prependPath . $uri;
				if ($uri[0] === '/') {
					$root = '';
					$rootRelative = $uri;
					$uri = $root . self::removeDots($rootRelative);
				} elseif (preg_match('@^((https?:)?//([^/]+))/@', $uri, $m) && (false !== strpos($m[3], '.'))) {
					$root = $m[1];
					$rootRelative = substr($uri, strlen($root));
					$uri = $root . self::removeDots($rootRelative);
				}
			}
		}
		return $isImport
			? sprintf("@import %s%s%s", $quoteChar, $uri, $quoteChar)
			: sprintf("url(%s%s%s)", $quoteChar, $uri, $quoteChar);
	}

	/**
	 *	Get realpath with any trailing slash removed. If realpath() fails, just remove the trailing slash.
	 *	@param		string		$path
	 *	@return		string		path with no trailing slash
	 */
	protected static function _realpath(string $path): string
	{
		$realPath = realpath($path);
		if ($realPath !== false) {
			$path = $realPath;
		}
		return rtrim($path, '/\\');
	}

	/**
	 *	@param		string		$css
	 *	@return		string
	 */
	private static function _trimUrls($css)
	{
		return preg_replace('/
			url\\(      # url(
			\\s*
			([^\\)]+?)  # 1 = URI (assuming does not contain ")")
			\\s*
			\\)         # )
		/x', 'url($1)', $css);
	}
}
