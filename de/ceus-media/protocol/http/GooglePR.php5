<?php

/**********************************************************************
GooglePR -- Calculates the Google PageRank of a specified URL
Authors : Raistlin Majere (euclide at email dot it) (google_pagerank()
Emre Odabas (eodabas at msn dot com)
Version : 1.2

Description
What is Google PageRank?

PageRank is a family of algorithms for assigning numerical weightings
to hyperlinked documents (or web pages) indexed by a search engine.
Its properties are much discussed by search engine optimization (SEO)
experts. The PageRank system is used by the popular search engine
Google to help determine a page's relevance or importance.

As Google puts it:

> PageRank relies on the uniquely democratic nature of the web by
> using its vast link structure as an indicator of an individual
> page's value. Google interprets a link from page A to page B as
> a vote, by page A, for page B. But Google looks at more than the
> sheer volume of votes, or links a page receives; it also analyzes
> the page that casts the vote. Votes cast by pages that are
> themselves "important" weigh more heavily and help to make other
> pages "important."

For more info:
http://www.google.com/corporate/tech.html
http://en.wikipedia.org/wiki/PageRank
http://www.google.com/webmasters/4.html

This class will calculate and return the Google PageRank of the
specified input URL as integer. Class was build based on Raistlin
Majere's google_pagerank function

Change Log:

  2005-12-07	* Small bug removed (dies when caching disabled)
  2005-11-24	* Added user-agent support
		* Class selects random google hostnames in
		  order to prevent abuse. (You may define extra
		  google hostnames)
		* Class now first tries cURL, fsockopen() and
		  file_get_contents() to connect google servers.
		* Added caching option to class. Results now can be
		  cached to flat files in order to prevent abuse and
		  increase performance.
		* Cache files are stored in seperate directories for
		  performance issues.

  2005-11-04	* Initial version released


Ex:
$gpr = new GooglePR();
//$gpr->debug=true; //Uncomment this line to debug query process
echo $gpr->GetPR("http://www.progen.com.tr");

//Uncomment following line to view debug results
//echo "<pre>";print_r($gpr->debugResult);echo "</pre>";

**********************************************************************/
Class GooglePR {

	//Public vars
	var $googleDomains = Array("toolbarqueries.google.com","www.google.com","toolbarqueries.google.com.tr","www.google.com.tr","toolbarqueries.google.de","www.google.de", "64.233.187.99", "72.14.207.99");
	var $debugResult = Array();
	var $userAgent = "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.2.1) Gecko/20021204";
	var $cacheDir = "";
	var $maxCacheAge = 86400; // = 24h (yes, in seconds)
	var $useCache = false;
	var $debug = false;

	//Private vars
	var $GOOGLE_MAGIC = 0xE6359A60;
	var $PageRank = -1;
	var $cacheExpired = false;


	function GetPR($url,$forceNoCache = false) {
		$total_exec_start = $this->microtimeFloat();
		$result=array("",-1);

		if (($url.""!="")&&($url.""!="http://")) {

			$this->debugRes("url", $url);

			$this->cacheDir = (strlen($this->cacheDir) > 0)? $this->cacheDir:dirname(__FILE__)."/prcache/";
			$this->cacheDir .= (substr($this->cacheDir,-1) != "/")? "/":"";

			// check for protocol
			$url_ = "info:".((substr(strtolower($url),0,7)!="http://")? "http://".$url:$url);
			$host = $this->googleDomains[mt_rand(0,count($this->googleDomains)-1)];
			$target = "/search";
			$querystring = sprintf("client=navclient-auto&ch=6%u&features=Rank&q=",$this->GoogleCH($this->strord($url_)));
			$querystring .= urlencode($url_);
			$contents="";

			$this->debugRes("host", $host);
			$this->debugRes("query_string", $querystring);
			$this->debugRes("user_agent", $this->userAgent);

			$query_exec_start = $this->microtimeFloat();

			if ($forceNoCache == true) {
				$this->debugRes("force_no_cache", "true");
			} elseif ($contents = $this->readCacheResult($url)) {
				$this->debugRes("read_from_cache", "true");
			} else {
				$this->cacheExpired = true;
			}


			// let's get ranking
			if (strlen(trim($contents)) == 0)
			if (@function_exists("curl_init")) {

				// allways use curl if available for performance issues
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://".$host.$target."?".$querystring);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
				curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
				if (!($contents = trim(@curl_exec($ch)))) {
					$this->debugRes("error","curl_exec failed");
				}
				curl_close ($ch);
				$this->debugRes("method", "curl");

			} else {
				$this->debugRes("error","curl not installed");
				// use fsockopen as secondary method, to submit user agent
				if ($socket  = @fsockopen($host, "80", $errno, $errstr, 30)) {

					$request  = "GET $target?$querystring HTTP/1.0\r\n";
					$request .= "Host: $host\r\n";
					$request .= "User-Agent: ".$this->userAgent."\r\n";
					$request .= "Accept-Language: en-us, en;q=0.50\r\n";
					$request .= "Accept-Charset: ISO-8859-1, utf-8;q=0.66, *;q=0.66\r\n";
					$request .= "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,video/x-mng,image/png,image/jpeg,image/gif;q=0.2,text/css,*/*;q=0.1\r\n";
					$request .= "Connection: close\r\n";
					$request .= "Cache-Control: max-age=0\r\n\r\n";

					stream_set_timeout ( $socket,10);
					fwrite( $socket, $request );
					$ret = '';
					while (!feof($socket)) {
						$ret .= fread($socket,4096);
					}
					fclose($socket);
					$contents = trim(substr($ret,strpos($ret,"\r\n\r\n") + 4));
					$this->debugRes("method", "fsockopen");
				} else {
					$this->debugRes("error","fsockopen failed");
					// this way could cause problems because the Browser Useragent is not set...
					if ($contents = trim(@file_get_contents("http://".$host.$target."?".$querystring))) {
						$this->debugRes("method", "file_get_contents");
					} else {
						$this->debugRes("error","file_get_contents failed");
					}
				}

			}

			if ($this->cacheExpired == true)
			$this->updateCacheResult($url,$contents);

			$this->debugRes("query_exec_time",$this->microtimeFloat() - $query_exec_start);

			$result[0]=$contents;
			// Rank_1:1:0 = 0
			// Rank_1:1:5 = 5
			// Rank_1:1:9 = 9
			// Rank_1:2:10 = 10 etc
			$p=explode(":",$contents);
			if (isset($p[2])) $result[1]=$p[2];
		}

		if($result[1] == -1) $result[1] = 0;
		$this->PageRank =(int)$result[1];
		$this->debugRes("total_exec_time", $this->microtimeFloat() - $total_exec_start);
		$this->debugRes("result", $result);
		return $this->PageRank;

	}


	function debugRes($what,$sowhat) {
		if($this->debug == true) {
			$debugbt = debug_backtrace();
			$what = trim($what);
			$sowhat = trim($sowhat) . " (Line : ".$debugbt[0]["line"].")";
			if ($what == "error") {
				$this->debugResult[$what][] = $sowhat;
			} else {
				$this->debugResult[$what] = $sowhat;
			}
		}
	}

	function microtimeFloat() {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}


	function readCacheResult($url) {
		if ($this->useCache != true) {
			return false;
		}

		if (!is_dir($this->cacheDir)) {
			$this->debugRes("error","please create {$this->cacheDir}");
			return false;
		}

		$urlp = parse_url($url);
		$host_ = explode(".",$urlp["host"]);
		$path_ = (strlen($urlp["query"])>0)? urlencode($urlp["path"].$urlp["query"]):"default";

		$cache_file = $this->cacheDir;

		for ($i = count($host_)-1;$i>=0;$i--) {
			$cache_file .= $host_[$i]."/";
		}

		$cache_file .= $path_;
		$this->debugRes("cache_file", $cache_file);
		if (file_exists($cache_file)) {
			$mtime = filemtime($cache_file);
			if (time() - $mtime > $this->maxCacheAge) {
				$this->debugRes("cache", "expired");
				$this->cacheExpired = true;
				return false;
			} else {
				$this->cacheExpired = false;
				$this->debugRes("cache_age", time() - $mtime);
				return file_get_contents($cache_file);
			}
		}
		$this->debugRes("error","cache file not exists (reading)");
		return false;
	}

	function updateCacheResult($url,$content) {
		if ($this->useCache != true) {
			return false;
		}

		if (!is_dir($this->cacheDir)) {
			$this->debugRes("error","please create {$this->cacheDir}");
			return false;
		}

		$urlp = parse_url($url);
		$host_ = explode(".",$urlp["host"]);
		$path_ = (strlen($urlp["query"])>0)? urlencode($urlp["path"].$urlp["query"]):"default";

		$cache_file = $this->cacheDir;
		for ($i = count($host_)-1;$i>=0;$i--) {
			$cache_file .= $host_[$i]."/";
		}

		$cache_file .= $path_;

		if (!file_exists($cache_file)) {
			$this->debugRes("error","cache file not exists (writing)");
			$cache_file_tmp = substr($cache_file,strlen($this->cacheDir));
			$cache_file_tmp = explode("/",$cache_file_tmp);
			$cache_dir_ = $this->cacheDir;
			for ($i = 0;$i<count($cache_file_tmp)-1;$i++) {
				$cache_dir_ .= $cache_file_tmp[$i]."/";
				if (!file_exists($cache_dir_)) {
					if (!@mkdir($cache_dir_,0777)) {
						$this->debugRes("error","unable to create cache dir: $cache_dir_");
						//break;
					}
				}
			}
			if (!@touch($cache_file)) $this->debugRes("error","unable to create cache file");
			if (!@chmod($cache_file,0777)) $this->debugRes("error","unable to chmod cache file");
		}

		if (is_writable($cache_file)) {
			if (!$handle = fopen($cache_file, 'w')) {
				$this->debugRes("error", "unable to open $cache_file");
				return false;
			}
			if (fwrite($handle, $content) === FALSE) {
				$this->debugRes("error", "unable to write to $cache_file");
				return false;
			}
			fclose($handle);
			$this->debugRes("cached", date("Y-m-d H:i:s"));
			return true;
		}
		$this->debugRes("error", "$cache_file is not writable");
		return false;

	}

	function zeroFill($a, $b) {
		$z = hexdec(80000000);
		if ($z & $a) {
			$a = ($a>>1);
			$a &= (~$z);
			$a |= 0x40000000;
			$a = ($a>>($b-1));
		} else {
			$a = ($a>>$b);
		}
		return $a;
	}

	function mix($a,$b,$c) {
		$a -= $b; $a -= $c; $a ^= ($this->zeroFill($c,13));
		$b -= $c; $b -= $a; $b ^= ($a<<8);
		$c -= $a; $c -= $b; $c ^= ($this->zeroFill($b,13));
		$a -= $b; $a -= $c; $a ^= ($this->zeroFill($c,12));
		$b -= $c; $b -= $a; $b ^= ($a<<16);
		$c -= $a; $c -= $b; $c ^= ($this->zeroFill($b,5));
		$a -= $b; $a -= $c; $a ^= ($this->zeroFill($c,3));
		$b -= $c; $b -= $a; $b ^= ($a<<10);
		$c -= $a; $c -= $b; $c ^= ($this->zeroFill($b,15));

		return array($a,$b,$c);
	}

	function GoogleCH($url, $length=null) {
		if(is_null($length)) {
			$length = sizeof($url);
		}
		$a = $b = 0x9E3779B9;
		$c = $this->GOOGLE_MAGIC;
		$k = 0;
		$len = $length;
		while($len >= 12) {
			$a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24));
			$b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24));
			$c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24));
			$mix = $this->mix($a,$b,$c);
			$a = $mix[0]; $b = $mix[1]; $c = $mix[2];
			$k += 12;
			$len -= 12;
		}

		$c += $length;
		switch($len) /* all the case statements fall through */
		{
			case 11: $c+=($url[$k+10]<<24);
			case 10: $c+=($url[$k+9]<<16);
			case 9 : $c+=($url[$k+8]<<8);
			/* the first byte of c is reserved for the length */
			case 8 : $b+=($url[$k+7]<<24);
			case 7 : $b+=($url[$k+6]<<16);
			case 6 : $b+=($url[$k+5]<<8);
			case 5 : $b+=($url[$k+4]);
			case 4 : $a+=($url[$k+3]<<24);
			case 3 : $a+=($url[$k+2]<<16);
			case 2 : $a+=($url[$k+1]<<8);
			case 1 : $a+=($url[$k+0]);
			/* case 0: nothing left to add */
		}
		$mix = $this->mix($a,$b,$c);
		/*-------------------------------------------- report the result */
		return $mix[2];
	}

	//converts a string into an array of integers containing the numeric value of the char
	function strord($string) {
		for($i=0;$i<strlen($string);$i++) {
			$result[$i] = ord($string{$i});
		}
		return $result;
	}

}

?>