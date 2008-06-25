<?php
/**
 *	Free PDF Generator - PHP5 Version.
 *	@package		fpdf
 *	@version		1.53
 *	@date			2004-12-31
 *	@author			Olivier PLATHEY
 *	@license		Freeware
 */
/**
 *	Free PDF Generator - PHP5 Version.
 *	@package		fpdf
 *	@version		1.53
 *	@date			2004-12-31
 *	@author			Olivier PLATHEY
 *	@license		Freeware
 */
define('FPDF_VERSION','1.53');
class FPDF
{
	public $currentFont;				//  current font info
	public $currentOrientation;			//  current orientation
	public $lMargin;					//  left margin
	public $tMargin;					//  top margin
	public $rMargin;					//  right margin
	public $bMargin;					//  page break margin
	public $cMargin;					//  cell margin


	protected $page;					//  current page number
	protected $n;						//  current object number
	protected $offsets;					//  array of object offsets
	protected $buffer;					//  buffer holding in-memory PDF
	protected $pages;					//  array containing pages
	protected $state;					//  current document state
	protected $compress;				//  compression flag
	protected $DefOrientation;			//  default orientation
	protected $OrientationChanges;		//  array indicating orientation changes
	protected $k;						//  scale factor (number of points in user unit)
	protected $fwPt,$fhPt;				//  dimensions of page format in points
	protected $fw,$fh;					//  dimensions of page format in user unit
	protected $wPt,$hPt;				//  current dimensions of page in points
	protected $w,$h;					//  current dimensions of page in user unit
	protected $x,$y;					//  current position in user unit for cell positioning
	protected $lasth;					//  height of last cell printed
	protected $LineWidth;				//  line width in user unit
	protected $CoreFonts;				//  array of standard font names
	protected $fonts;					//  array of used fonts
	protected $FontFiles;				//  array of font files
	protected $diffs;					//  array of encoding differences
	protected $images;					//  array of used images
	protected $PageLinks;				//  array of links in pages
	protected $links;					//  array of internal links
	protected $FontFamily;				//  current font family
	protected $FontStyle;				//  current font style
	protected $underline;				//  underlining flag
	protected $FontSizePt;				//  current font size in points
	protected $FontSize;				//  current font size in user unit
	protected $DrawColor;				//  commands for drawing color
	protected $FillColor;				//  commands for filling color
	protected $TextColor;				//  commands for text color
	protected $ColorFlag;				//  indicates whether fill and text colors are different
	protected $ws;						//  word spacing
	protected $AutoPageBreak;			//  automatic page breaking
	protected $pageBreakTrigger;		//  threshold used to trigger page breaks
	protected $InFooter;				//  flag set when processing footer
	protected $ZoomMode;				//  zoom display mode
	protected $LayoutMode;				//  layout display mode
	protected $title;					//  title
	protected $subject;					//  subject
	protected $author;					//  author
	protected $keywords;				//  keywords
	protected $creator;					//  creator
	protected $AliasNbPages;			//  alias for total number of pages
	protected $PDFVersion;				//  PDF version number

	/*******************************************************************************
	*                                                                              *
	*                               Public methods                                 *
	*                                                                              *
	*******************************************************************************/
	public function __construct($orientation='P',$unit='mm',$format='A4')
	{
		//Some checks
		$this->doChecks();
		//Initialization of properties
		$this->page=0;
		$this->n=2;
		$this->buffer='';
		$this->pages=array();
		$this->OrientationChanges=array();
		$this->state=0;
		$this->fonts=array();
		$this->FontFiles=array();
		$this->diffs=array();
		$this->images=array();
		$this->links=array();
		$this->InFooter=false;
		$this->lasth=0;
		$this->FontFamily='';
		$this->FontStyle='';
		$this->FontSizePt=12;
		$this->underline=false;
		$this->DrawColor='0 G';
		$this->FillColor='0 g';
		$this->TextColor='0 g';
		$this->ColorFlag=false;
		$this->ws=0;
		//Standard fonts
		$this->CoreFonts=array('courier'=>'Courier','courierB'=>'Courier-Bold','courierI'=>'Courier-Oblique','courierBI'=>'Courier-BoldOblique',
			'helvetica'=>'Helvetica','helveticaB'=>'Helvetica-Bold','helveticaI'=>'Helvetica-Oblique','helveticaBI'=>'Helvetica-BoldOblique',
			'times'=>'Times-Roman','timesB'=>'Times-Bold','timesI'=>'Times-Italic','timesBI'=>'Times-BoldItalic',
			'symbol'=>'Symbol','zapfdingbats'=>'ZapfDingbats');
		//Scale factor
		if($unit=='pt')
			$this->k=1;
		elseif($unit=='mm')
			$this->k=72/25.4;
		elseif($unit=='cm')
			$this->k=72/2.54;
		elseif($unit=='in')
			$this->k=72;
		else
			throw new Exception('Incorrect unit: '.$unit);
		//Page format
		if(is_string($format))
		{
			$format=strtolower($format);
			if($format=='a3')
				$format=array(841.89,1190.55);
			elseif($format=='a4')
				$format=array(595.28,841.89);
			elseif($format=='a5')
				$format=array(420.94,595.28);
			elseif($format=='letter')
				$format=array(612,792);
			elseif($format=='legal')
				$format=array(612,1008);
			else
				throw new Exception('Unknown page format: '.$format);
			$this->fwPt=$format[0];
			$this->fhPt=$format[1];
		}
		else
		{
			$this->fwPt=$format[0]*$this->k;
			$this->fhPt=$format[1]*$this->k;
		}
		$this->fw=$this->fwPt/$this->k;
		$this->fh=$this->fhPt/$this->k;
		//Page orientation
		$orientation=strtolower($orientation);
		if($orientation=='p' || $orientation=='portrait')
		{
			$this->DefOrientation='P';
			$this->wPt=$this->fwPt;
			$this->hPt=$this->fhPt;
		}
		elseif($orientation=='l' || $orientation=='landscape')
		{
			$this->DefOrientation='L';
			$this->wPt=$this->fhPt;
			$this->hPt=$this->fwPt;
		}
		else
			throw new Exception('Incorrect orientation: '.$orientation);
		$this->currentOrientation=$this->DefOrientation;
		$this->w=$this->wPt/$this->k;
		$this->h=$this->hPt/$this->k;
		//Page margins (1 cm)
		$margin=28.35/$this->k;
		$this->setMargins($margin,$margin);
		//Interior cell margin (1 mm)
		$this->cMargin=$margin/10;
		//Line width (0.2 mm)
		$this->LineWidth=.567/$this->k;
		//Automatic page break
		$this->setAutoPageBreak(true,2*$margin);
		//Full width display mode
		$this->setDisplayMode('fullwidth');
		//Enable compression
		$this->setCompression(true);
		//Set default PDF version number
		$this->PDFVersion='1.3';
	}

	public function setMargins($left,$top,$right=-1)
	{
		//Set left, top and right margins
		$this->lMargin=$left;
		$this->tMargin=$top;
		if($right==-1)
			$right=$left;
		$this->rMargin=$right;
	}

	public function setLeftMargin($margin)
	{
		//Set left margin
		$this->lMargin=$margin;
		if($this->page>0 && $this->x<$margin)
			$this->x=$margin;
	}

	public function setTopMargin($margin)
	{
		//Set top margin
		$this->tMargin=$margin;
	}

	public function setRightMargin($margin)
	{
		//Set right margin
		$this->rMargin=$margin;
	}

	public function setAutoPageBreak($auto,$margin=0)
	{
		//Set auto page break mode and triggering margin
		$this->AutoPageBreak=$auto;
		$this->bMargin=$margin;
		$this->pageBreakTrigger=$this->h-$margin;
	}

	public function setDisplayMode($zoom,$layout='continuous')
	{
		//Set display mode in viewer
		if($zoom=='fullpage' || $zoom=='fullwidth' || $zoom=='real' || $zoom=='default' || !is_string($zoom))
			$this->ZoomMode=$zoom;
		else
			throw new Exception('Incorrect zoom display mode: '.$zoom);
		if($layout=='single' || $layout=='continuous' || $layout=='two' || $layout=='default')
			$this->LayoutMode=$layout;
		else
			throw new Exception('Incorrect layout display mode: '.$layout);
	}

	public function setCompression($compress)
	{
		//Set page compression
		if(function_exists('gzcompress'))
			$this->compress=$compress;
		else
			$this->compress=false;
	}

	public function setTitle($title)
	{
		//Title of document
		$this->title=$title;
	}

	public function setSubject($subject)
	{
		//Subject of document
		$this->subject=$subject;
	}

	public function setAuthor($author)
	{
		//Author of document
		$this->author=$author;
	}

	public function setKeywords($keywords)
	{
		//Keywords of document
		$this->keywords=$keywords;
	}

	public function setCreator($creator)
	{
		//Creator of document
		$this->creator=$creator;
	}

	public function aliasNbPages($alias='{nb}')
	{
		//Define an alias for total number of pages
		$this->AliasNbPages=$alias;
	}

	public function openDocument()
	{
		//Begin document
		$this->state=1;
	}

	public function closeDocument()
	{
		//Terminate document
		if($this->state==3)
			return;
		if($this->page==0)
			$this->AddPage();
		//Page footer
		$this->InFooter=true;
		$this->Footer();
		$this->InFooter=false;
		//Close page
		$this->endpage();
		//Close document
		$this->enddoc();
	}

	public function addPage($orientation='')
	{
		//Start a new page
		if($this->state==0)
			$this->openDocument();
		$family=$this->FontFamily;
		$style=$this->FontStyle.($this->underline ? 'U' : '');
		$size=$this->FontSizePt;
		$lw=$this->LineWidth;
		$dc=$this->DrawColor;
		$fc=$this->FillColor;
		$tc=$this->TextColor;
		$cf=$this->ColorFlag;
		if($this->page>0)
		{
			//Page footer
			$this->InFooter=true;
			$this->Footer();
			$this->InFooter=false;
			//Close page
			$this->endpage();
		}
		//Start new page
		$this->beginpage($orientation);
		//Set line cap style to square
		$this->out('2 J');
		//Set line width
		$this->LineWidth=$lw;
		$this->out(sprintf('%.2f w',$lw*$this->k));
		//Set font
		if($family)
			$this->setFont($family,$style,$size);
		//Set colors
		$this->DrawColor=$dc;
		if($dc!='0 G')
			$this->out($dc);
		$this->FillColor=$fc;
		if($fc!='0 g')
			$this->out($fc);
		$this->TextColor=$tc;
		$this->ColorFlag=$cf;
		//Page header
		$this->Header();
		//Restore line width
		if($this->LineWidth!=$lw)
		{
			$this->LineWidth=$lw;
			$this->out(sprintf('%.2f w',$lw*$this->k));
		}
		//Restore font
		if($family)
			$this->setFont($family,$style,$size);
		//Restore colors
		if($this->DrawColor!=$dc)
		{
			$this->DrawColor=$dc;
			$this->out($dc);
		}
		if($this->FillColor!=$fc)
		{
			$this->FillColor=$fc;
			$this->out($fc);
		}
		$this->TextColor=$tc;
		$this->ColorFlag=$cf;
	}

	public function Header()
	{
		//To be implemented in your own inherited class
	}

	public function Footer()
	{
		//To be implemented in your own inherited class
	}

	public function PageNo()
	{
		//Get current page number
		return $this->page;
	}

	public function setDrawColor($r,$g=-1,$b=-1)
	{
		//Set color for all stroking operations
		if(($r==0 && $g==0 && $b==0) || $g==-1)
			$this->DrawColor=sprintf('%.3f G',$r/255);
		else
			$this->DrawColor=sprintf('%.3f %.3f %.3f RG',$r/255,$g/255,$b/255);
		if($this->page>0)
			$this->out($this->DrawColor);
	}

	public function setFillColor($r,$g=-1,$b=-1)
	{
		//Set color for all filling operations
		if(($r==0 && $g==0 && $b==0) || $g==-1)
			$this->FillColor=sprintf('%.3f g',$r/255);
		else
			$this->FillColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
		$this->ColorFlag=($this->FillColor!=$this->TextColor);
		if($this->page>0)
			$this->out($this->FillColor);
	}

	public function setTextColor($r,$g=-1,$b=-1)
	{
		//Set color for text
		if(($r==0 && $g==0 && $b==0) || $g==-1)
			$this->TextColor=sprintf('%.3f g',$r/255);
		else
			$this->TextColor=sprintf('%.3f %.3f %.3f rg',$r/255,$g/255,$b/255);
		$this->ColorFlag=($this->FillColor!=$this->TextColor);
	}

	public function getStringWidth($s)
	{
		//Get width of a string in the current font
		$s=(string)$s;
		$cw=&$this->currentFont['cw'];
		$w=0;
		$l=strlen($s);
		for($i=0;$i<$l;$i++)
			$w+=$cw[$s{$i}];
		return $w*$this->FontSize/1000;
	}

	public function setLineWidth($width)
	{
		//Set line width
		$this->LineWidth=$width;
		if($this->page>0)
			$this->out(sprintf('%.2f w',$width*$this->k));
	}

	public function Line($x1,$y1,$x2,$y2)
	{
		//Draw a line
		$this->out(sprintf('%.2f %.2f m %.2f %.2f l S',$x1*$this->k,($this->h-$y1)*$this->k,$x2*$this->k,($this->h-$y2)*$this->k));
	}

	public function Rect($x,$y,$w,$h,$style='')
	{
		//Draw a rectangle
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='B';
		else
			$op='S';
		$this->out(sprintf('%.2f %.2f %.2f %.2f re %s',$x*$this->k,($this->h-$y)*$this->k,$w*$this->k,-$h*$this->k,$op));
	}

	public function AddFont($family,$style='',$file='')
	{
		//Add a TrueType or Type1 font
		$family=strtolower($family);
		if($file=='')
			$file=str_replace(' ','',$family).strtolower($style).'.php';
		if($family=='arial')
			$family='helvetica';
		$style=strtoupper($style);
		if($style=='IB')
			$style='BI';
		$fontkey=$family.$style;
		if(isset($this->fonts[$fontkey]))
			throw new Exception('Font already added: '.$family.' '.$style);
		include($this->getfontpath().$file);
		if(!isset($name))
			throw new Exception('Could not include font definition file');
		$i=count($this->fonts)+1;
		$this->fonts[$fontkey]=array('i'=>$i,'type'=>$type,'name'=>$name,'desc'=>$desc,'up'=>$up,'ut'=>$ut,'cw'=>$cw,'enc'=>$enc,'file'=>$file);
		if($diff)
		{
			//Search existing encodings
			$d=0;
			$nb=count($this->diffs);
			for($i=1;$i<=$nb;$i++)
			{
				if($this->diffs[$i]==$diff)
				{
					$d=$i;
					break;
				}
			}
			if($d==0)
			{
				$d=$nb+1;
				$this->diffs[$d]=$diff;
			}
			$this->fonts[$fontkey]['diff']=$d;
		}
		if($file)
		{
			if($type=='TrueType')
				$this->FontFiles[$file]=array('length1'=>$originalsize);
			else
				$this->FontFiles[$file]=array('length1'=>$size1,'length2'=>$size2);
		}
	}

	public function setFont($family,$style='',$size=0)
	{
		//Select a font; size given in points
		global $fpdf_charwidths;

		$family=strtolower($family);
		if($family=='')
			$family=$this->FontFamily;
		if($family=='arial')
			$family='helvetica';
		elseif($family=='symbol' || $family=='zapfdingbats')
			$style='';
		$style=strtoupper($style);
		if(strpos($style,'U')!==false)
		{
			$this->underline=true;
			$style=str_replace('U','',$style);
		}
		else
			$this->underline=false;
		if($style=='IB')
			$style='BI';
		if($size==0)
			$size=$this->FontSizePt;
		//Test if font is already selected
		if($this->FontFamily==$family && $this->FontStyle==$style && $this->FontSizePt==$size)
			return;
		//Test if used for the first time
		$fontkey=$family.$style;
		if(!isset($this->fonts[$fontkey]))
		{
			//Check if one of the standard fonts
			if(isset($this->CoreFonts[$fontkey]))
			{
				if(!isset($fpdf_charwidths[$fontkey]))
				{
					//Load metric file
					$file=$family;
					if($family=='times' || $family=='helvetica')
						$file.=strtolower($style);
					include($this->getfontpath().$file.'.php');
					if(!isset($fpdf_charwidths[$fontkey]))
						throw new Exception('Could not include font metric file');
				}
				$i=count($this->fonts)+1;
				$this->fonts[$fontkey]=array('i'=>$i,'type'=>'core','name'=>$this->CoreFonts[$fontkey],'up'=>-100,'ut'=>50,'cw'=>$fpdf_charwidths[$fontkey]);
			}
			else
				throw new Exception('Undefined font: '.$family.' '.$style);
		}
		//Select it
		$this->FontFamily=$family;
		$this->FontStyle=$style;
		$this->FontSizePt=$size;
		$this->FontSize=$size/$this->k;
		$this->currentFont=&$this->fonts[$fontkey];
		if($this->page>0)
			$this->out(sprintf('BT /F%d %.2f Tf ET',$this->currentFont['i'],$this->FontSizePt));
	}

	public function setFontSize($size)
	{
		//Set font size in points
		if($this->FontSizePt==$size)
			return;
		$this->FontSizePt=$size;
		$this->FontSize=$size/$this->k;
		if($this->page>0)
			$this->out(sprintf('BT /F%d %.2f Tf ET',$this->currentFont['i'],$this->FontSizePt));
	}

	public function AddLink()
	{
		//Create a new internal link
		$n=count($this->links)+1;
		$this->links[$n]=array(0,0);
		return $n;
	}

	public function setLink($link,$y=0,$page=-1)
	{
		//Set destination of internal link
		if($y==-1)
			$y=$this->y;
		if($page==-1)
			$page=$this->page;
		$this->links[$link]=array($page,$y);
	}

	public function Link($x,$y,$w,$h,$link)
	{
		//Put a link on the page
		$this->PageLinks[$this->page][]=array($x*$this->k,$this->hPt-$y*$this->k,$w*$this->k,$h*$this->k,$link);
	}

	public function Text($x,$y,$txt)
	{
		//Output a string
		$s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->escape($txt));
		if($this->underline && $txt!='')
			$s.=' '.$this->dounderline($x,$y,$txt);
		if($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->out($s);
	}

	public function AcceptPageBreak()
	{
		//Accept automatic page break or not
		return $this->AutoPageBreak;
	}

	public function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
	{
		//Output a cell
		$k=$this->k;
		if($this->y+$h>$this->pageBreakTrigger && !$this->InFooter && $this->AcceptPageBreak())
		{
			//Automatic page break
			$x=$this->x;
			$ws=$this->ws;
			if($ws>0)
			{
				$this->ws=0;
				$this->out('0 Tw');
			}
			$this->AddPage($this->currentOrientation);
			$this->x=$x;
			if($ws>0)
			{
				$this->ws=$ws;
				$this->out(sprintf('%.3f Tw',$ws*$k));
			}
		}
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$s='';
		if($fill==1 || $border==1)
		{
			if($fill==1)
				$op=($border==1) ? 'B' : 'f';
			else
				$op='S';
			$s=sprintf('%.2f %.2f %.2f %.2f re %s ',$this->x*$k,($this->h-$this->y)*$k,$w*$k,-$h*$k,$op);
		}
		if(is_string($border))
		{
			$x=$this->x;
			$y=$this->y;
			if(strpos($border,'L')!==false)
				$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,$x*$k,($this->h-($y+$h))*$k);
			if(strpos($border,'T')!==false)
				$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-$y)*$k);
			if(strpos($border,'R')!==false)
				$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',($x+$w)*$k,($this->h-$y)*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
			if(strpos($border,'B')!==false)
				$s.=sprintf('%.2f %.2f m %.2f %.2f l S ',$x*$k,($this->h-($y+$h))*$k,($x+$w)*$k,($this->h-($y+$h))*$k);
		}
		if($txt!=='')
		{
			if($align=='R')
				$dx=$w-$this->cMargin-$this->GetStringWidth($txt);
			elseif($align=='C')
				$dx=($w-$this->GetStringWidth($txt))/2;
			else
				$dx=$this->cMargin;
			if($this->ColorFlag)
				$s.='q '.$this->TextColor.' ';
			$txt2=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
			$s.=sprintf('BT %.2f %.2f Td (%s) Tj ET',($this->x+$dx)*$k,($this->h-($this->y+.5*$h+.3*$this->FontSize))*$k,$txt2);
			if($this->underline)
				$s.=' '.$this->dounderline($this->x+$dx,$this->y+.5*$h+.3*$this->FontSize,$txt);
			if($this->ColorFlag)
				$s.=' Q';
			if($link)
				$this->Link($this->x+$dx,$this->y+.5*$h-.5*$this->FontSize,$this->GetStringWidth($txt),$this->FontSize,$link);
		}
		if($s)
			$this->out($s);
		$this->lasth=$h;
		if($ln>0)
		{
			//Go to next line
			$this->y+=$h;
			if($ln==1)
				$this->x=$this->lMargin;
		}
		else
			$this->x+=$w;
	}

	public function MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0)
	{
		//Output text with automatic or explicit line breaks
		$cw=&$this->currentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
		$b=0;
		if($border)
		{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			}
			else
			{
				$b2='';
				if(strpos($border,'L')!==false)
					$b2.='L';
				if(strpos($border,'R')!==false)
					$b2.='R';
				$b=(strpos($border,'T')!==false) ? $b2.'T' : $b2;
			}
		}
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$ns=0;
		$nl=1;
		while($i<$nb)
		{
			//Get next character
			$c=$s{$i};
			if($c=="\n")
			{
				//Explicit line break
				if($this->ws>0)
				{
					$this->ws=0;
					$this->out('0 Tw');
				}
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
				continue;
			}
			if($c==' ')
			{
				$sep=$i;
				$ls=$l;
				$ns++;
			}
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
					if($i==$j)
						$i++;
					if($this->ws>0)
					{
						$this->ws=0;
						$this->out('0 Tw');
					}
					$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				}
				else
				{
					if($align=='J')
					{
						$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
						$this->out(sprintf('%.3f Tw',$this->ws*$this->k));
					}
					$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
					$b=$b2;
			}
			else
				$i++;
		}
		//Last chunk
		if($this->ws>0)
		{
			$this->ws=0;
			$this->out('0 Tw');
		}
		if($border && strpos($border,'B')!==false)
			$b.='B';
		$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
		$this->x=$this->lMargin;
	}

	public function Write($h,$txt,$link='')
	{
		//Output text in flowing mode
		$cw=&$this->currentFont['cw'];
		$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			//Get next character
			$c=$s{$i};
			if($c=="\n")
			{
				//Explicit line break
				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1)
				{
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
					if($this->x>$this->lMargin)
					{
						//Move to next line
						$this->x=$this->lMargin;
						$this->y+=$h;
						$w=$this->w-$this->rMargin-$this->x;
						$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
						$i++;
						$nl++;
						continue;
					}
					if($i==$j)
						$i++;
					$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
				}
				else
				{
					$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
					$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				if($nl==1)
				{
					$this->x=$this->lMargin;
					$w=$this->w-$this->rMargin-$this->x;
					$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				}
				$nl++;
			}
			else
				$i++;
		}
		//Last chunk
		if($i!=$j)
			$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j),0,0,'',0,$link);
	}

	public function Image($file,$x,$y,$w=0,$h=0,$type='',$link='')
	{
		//Put an image on the page
		if(!isset($this->images[$file]))
		{
			//First use of image, get info
			if($type=='')
			{
				$pos=strrpos($file,'.');
				if(!$pos)
					throw new Exception('Image file has no extension and no type was specified: '.$file);
				$type=substr($file,$pos+1);
			}
			$type=strtolower($type);
			$mqr=get_magic_quotes_runtime();
			set_magic_quotes_runtime(0);
			if($type=='jpg' || $type=='jpeg')
				$info=$this->parsejpg($file);
			elseif($type=='png')
				$info=$this->parsepng($file);
			else
			{
				//Allow for additional formats
				$mtd='_parse'.$type;
				if(!method_exists($this,$mtd))
					throw new Exception('Unsupported image type: '.$type);
				$info=$this->$mtd($file);
			}
			set_magic_quotes_runtime($mqr);
			$info['i']=count($this->images)+1;
			$this->images[$file]=$info;
		}
		else
			$info=$this->images[$file];
		//Automatic width and height calculation if needed
		if($w==0 && $h==0)
		{
			//Put image at 72 dpi
			$w=$info['w']/$this->k;
			$h=$info['h']/$this->k;
		}
		if($w==0)
			$w=$h*$info['w']/$info['h'];
		if($h==0)
			$h=$w*$info['h']/$info['w'];
		$this->out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',$w*$this->k,$h*$this->k,$x*$this->k,($this->h-($y+$h))*$this->k,$info['i']));
		if($link)
			$this->Link($x,$y,$w,$h,$link);
	}

	public function Ln($h='')
	{
		//Line feed; default value is last cell height
		$this->x=$this->lMargin;
		if(is_string($h))
			$this->y+=$this->lasth;
		else
			$this->y+=$h;
	}

	public function getX()
	{
		//Get x position
		return $this->x;
	}

	public function setX($x)
	{
		//Set x position
		if($x>=0)
			$this->x=$x;
		else
			$this->x=$this->w+$x;
	}

	public function getY()
	{
		//Get y position
		return $this->y;
	}

	public function setY($y)
	{
		//Set y position and reset x
		$this->x=$this->lMargin;
		if($y>=0)
			$this->y=$y;
		else
			$this->y=$this->h+$y;
	}

	public function setXY($x,$y)
	{
		//Set x and y positions
		$this->setY($y);
		$this->setX($x);
	}

	public function Output($name='',$dest='')
	{
		//Output PDF to some destination
		//Finish document if necessary
		if($this->state<3)
			$this->closeDocument();
		//Normalize parameters
		if(is_bool($dest))
			$dest=$dest ? 'D' : 'F';
		$dest=strtoupper($dest);
		if($dest=='')
		{
			if($name=='')
			{
				$name='doc.pdf';
				$dest='I';
			}
			else
				$dest='F';
		}
		switch($dest)
		{
			case 'I':
				//Send to standard output
				if(ob_get_contents())
					throw new Exception('Some data has already been output, can\'t send PDF file');
				if(php_sapi_name()!='cli')
				{
					//We send to a browser
					header('Content-Type: application/pdf');
					if(headers_sent())
						throw new Exception('Some data has already been output to browser, can\'t send PDF file');
					header('Content-Length: '.strlen($this->buffer));
					header('Content-disposition: inline; filename="'.$name.'"');
				}
				echo $this->buffer;
				break;
			case 'D':
				//Download file
				if(ob_get_contents())
					throw new Exception('Some data has already been output, can\'t send PDF file');
				if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
					header('Content-Type: application/force-download');
				else
					header('Content-Type: application/octet-stream');
				if(headers_sent())
					throw new Exception('Some data has already been output to browser, can\'t send PDF file');
				header('Content-Length: '.strlen($this->buffer));
				header('Content-disposition: attachment; filename="'.$name.'"');
				echo $this->buffer;
				break;
			case 'F':
				//Save to local file
				$f=fopen($name,'wb');
				if(!$f)
					throw new Exception('Unable to create output file: '.$name);
				fwrite($f,$this->buffer,strlen($this->buffer));
				fclose($f);
				break;
			case 'S':
				//Return as a string
				return $this->buffer;
			default:
				throw new Exception('Incorrect output destination: '.$dest);
		}
		return '';
	}

	/*******************************************************************************
	*                                                                              *
	*                              Protected methods                               *
	*                                                                              *
	*******************************************************************************/
	protected function doChecks()
	{
		//Check for locale-related bug
		if(1.1==1)
			throw new Exception('Don\'t alter the locale before including class file');
		//Check for decimal separator
		if(sprintf('%.1f',1.0)!='1.0')
			setlocale(LC_NUMERIC,'C');
	}

	protected function getFontPath()
	{
		if(!defined('FPDF_FONTPATH') && is_dir(dirname(__FILE__).'/font'))
			define('FPDF_FONTPATH',dirname(__FILE__).'/font/');
		return defined('FPDF_FONTPATH') ? FPDF_FONTPATH : '';
	}

	protected function putpages()
	{
		$nb=$this->page;
		if(!empty($this->AliasNbPages))
		{
			//Replace number of pages
			for($n=1;$n<=$nb;$n++)
				$this->pages[$n]=str_replace($this->AliasNbPages,$nb,$this->pages[$n]);
		}
		if($this->DefOrientation=='P')
		{
			$wPt=$this->fwPt;
			$hPt=$this->fhPt;
		}
		else
		{
			$wPt=$this->fhPt;
			$hPt=$this->fwPt;
		}
		$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
		for($n=1;$n<=$nb;$n++)
		{
			//Page
			$this->newobj();
			$this->out('<</Type /Page');
			$this->out('/Parent 1 0 R');
			if(isset($this->OrientationChanges[$n]))
				$this->out(sprintf('/MediaBox [0 0 %.2f %.2f]',$hPt,$wPt));
			$this->out('/Resources 2 0 R');
			if(isset($this->PageLinks[$n]))
			{
				//Links
				$annots='/Annots [';
				foreach($this->PageLinks[$n] as $pl)
				{
					$rect=sprintf('%.2f %.2f %.2f %.2f',$pl[0],$pl[1],$pl[0]+$pl[2],$pl[1]-$pl[3]);
					$annots.='<</Type /Annot /Subtype /Link /Rect ['.$rect.'] /Border [0 0 0] ';
					if(is_string($pl[4]))
						$annots.='/A <</S /URI /URI '.$this->textstring($pl[4]).'>>>>';
					else
					{
						$l=$this->links[$pl[4]];
						$h=isset($this->OrientationChanges[$l[0]]) ? $wPt : $hPt;
						$annots.=sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]>>',1+2*$l[0],$h-$l[1]*$this->k);
					}
				}
				$this->out($annots.']');
			}
			$this->out('/Contents '.($this->n+1).' 0 R>>');
			$this->out('endobj');
			//Page content
			$p=($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
			$this->newobj();
			$this->out('<<'.$filter.'/Length '.strlen($p).'>>');
			$this->putstream($p);
			$this->out('endobj');
		}
		//Pages root
		$this->offsets[1]=strlen($this->buffer);
		$this->out('1 0 obj');
		$this->out('<</Type /Pages');
		$kids='/Kids [';
		for($i=0;$i<$nb;$i++)
			$kids.=(3+2*$i).' 0 R ';
		$this->out($kids.']');
		$this->out('/Count '.$nb);
		$this->out(sprintf('/MediaBox [0 0 %.2f %.2f]',$wPt,$hPt));
		$this->out('>>');
		$this->out('endobj');
	}

	protected function putfonts()
	{
		$nf=$this->n;
		foreach($this->diffs as $diff)
		{
			//Encodings
			$this->newobj();
			$this->out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
			$this->out('endobj');
		}
		$mqr=get_magic_quotes_runtime();
		set_magic_quotes_runtime(0);
		foreach($this->FontFiles as $file=>$info)
		{
			//Font file embedding
			$this->newobj();
			$this->FontFiles[$file]['n']=$this->n;
			$font='';
			$f=fopen($this->getFontPath().$file,'rb',1);
			if(!$f)
				throw new Exception('Font file not found');
			while(!feof($f))
				$font.=fread($f,8192);
			fclose($f);
			$compressed=(substr($file,-2)=='.z');
			if(!$compressed && isset($info['length2']))
			{
				$header=(ord($font{0})==128);
				if($header)
				{
					//Strip first binary header
					$font=substr($font,6);
				}
				if($header && ord($font{$info['length1']})==128)
				{
					//Strip second binary header
					$font=substr($font,0,$info['length1']).substr($font,$info['length1']+6);
				}
			}
			$this->out('<</Length '.strlen($font));
			if($compressed)
				$this->out('/Filter /FlateDecode');
			$this->out('/Length1 '.$info['length1']);
			if(isset($info['length2']))
				$this->out('/Length2 '.$info['length2'].' /Length3 0');
			$this->out('>>');
			$this->putstream($font);
			$this->out('endobj');
		}
		set_magic_quotes_runtime($mqr);
		foreach($this->fonts as $k=>$font)
		{
			//Font objects
			$this->fonts[$k]['n']=$this->n+1;
			$type=$font['type'];
			$name=$font['name'];
			if($type=='core')
			{
				//Standard font
				$this->newobj();
				$this->out('<</Type /Font');
				$this->out('/BaseFont /'.$name);
				$this->out('/Subtype /Type1');
				if($name!='Symbol' && $name!='ZapfDingbats')
					$this->out('/Encoding /WinAnsiEncoding');
				$this->out('>>');
				$this->out('endobj');
			}
			elseif($type=='Type1' || $type=='TrueType')
			{
				//Additional Type1 or TrueType font
				$this->newobj();
				$this->out('<</Type /Font');
				$this->out('/BaseFont /'.$name);
				$this->out('/Subtype /'.$type);
				$this->out('/FirstChar 32 /LastChar 255');
				$this->out('/Widths '.($this->n+1).' 0 R');
				$this->out('/FontDescriptor '.($this->n+2).' 0 R');
				if($font['enc'])
				{
					if(isset($font['diff']))
						$this->out('/Encoding '.($nf+$font['diff']).' 0 R');
					else
						$this->out('/Encoding /WinAnsiEncoding');
				}
				$this->out('>>');
				$this->out('endobj');
				//Widths
				$this->newobj();
				$cw=&$font['cw'];
				$s='[';
				for($i=32;$i<=255;$i++)
					$s.=$cw[chr($i)].' ';
				$this->out($s.']');
				$this->out('endobj');
				//Descriptor
				$this->newobj();
				$s='<</Type /FontDescriptor /FontName /'.$name;
				foreach($font['desc'] as $k=>$v)
					$s.=' /'.$k.' '.$v;
				$file=$font['file'];
				if($file)
					$s.=' /FontFile'.($type=='Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
				$this->out($s.'>>');
				$this->out('endobj');
			}
			else
			{
				//Allow for additional types
				$mtd='_put'.strtolower($type);
				if(!method_exists($this,$mtd))
					throw new Exception('Unsupported font type: '.$type);
				$this->$mtd($font);
			}
		}
	}

	protected function putimages()
	{
		$filter=($this->compress) ? '/Filter /FlateDecode ' : '';
		reset($this->images);
		while(list($file,$info)=each($this->images))
		{
			$this->newobj();
			$this->images[$file]['n']=$this->n;
			$this->out('<</Type /XObject');
			$this->out('/Subtype /Image');
			$this->out('/Width '.$info['w']);
			$this->out('/Height '.$info['h']);
			if($info['cs']=='Indexed')
				$this->out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal'])/3-1).' '.($this->n+1).' 0 R]');
			else
			{
				$this->out('/ColorSpace /'.$info['cs']);
				if($info['cs']=='DeviceCMYK')
					$this->out('/Decode [1 0 1 0 1 0 1 0]');
			}
			$this->out('/BitsPerComponent '.$info['bpc']);
			if(isset($info['f']))
				$this->out('/Filter /'.$info['f']);
			if(isset($info['parms']))
				$this->out($info['parms']);
			if(isset($info['trns']) && is_array($info['trns']))
			{
				$trns='';
				for($i=0;$i<count($info['trns']);$i++)
					$trns.=$info['trns'][$i].' '.$info['trns'][$i].' ';
				$this->out('/Mask ['.$trns.']');
			}
			$this->out('/Length '.strlen($info['data']).'>>');
			$this->putstream($info['data']);
			unset($this->images[$file]['data']);
			$this->out('endobj');
			//Palette
			if($info['cs']=='Indexed')
			{
				$this->newobj();
				$pal=($this->compress) ? gzcompress($info['pal']) : $info['pal'];
				$this->out('<<'.$filter.'/Length '.strlen($pal).'>>');
				$this->putstream($pal);
				$this->out('endobj');
			}
		}
	}

	protected function putxobjectdict()
	{
		foreach($this->images as $image)
			$this->out('/I'.$image['i'].' '.$image['n'].' 0 R');
	}

	protected function putresourcedict()
	{
		$this->out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
		$this->out('/Font <<');
		foreach($this->fonts as $font)
			$this->out('/F'.$font['i'].' '.$font['n'].' 0 R');
		$this->out('>>');
		$this->out('/XObject <<');
		$this->putxobjectdict();
		$this->out('>>');
	}

	protected function putresources()
	{
		$this->putfonts();
		$this->putimages();
		//Resource dictionary
		$this->offsets[2]=strlen($this->buffer);
		$this->out('2 0 obj');
		$this->out('<<');
		$this->putresourcedict();
		$this->out('>>');
		$this->out('endobj');
	}

	protected function putinfo()
	{
		$this->out('/Producer '.$this->textstring('FPDF '.FPDF_VERSION));
		if(!empty($this->title))
			$this->out('/Title '.$this->textstring($this->title));
		if(!empty($this->subject))
			$this->out('/Subject '.$this->textstring($this->subject));
		if(!empty($this->author))
			$this->out('/Author '.$this->textstring($this->author));
		if(!empty($this->keywords))
			$this->out('/Keywords '.$this->textstring($this->keywords));
		if(!empty($this->creator))
			$this->out('/Creator '.$this->textstring($this->creator));
		$this->out('/CreationDate '.$this->textstring('D:'.date('YmdHis')));
	}

	protected function putcatalog()
	{
		$this->out('/Type /Catalog');
		$this->out('/Pages 1 0 R');
		if($this->ZoomMode=='fullpage')
			$this->out('/OpenAction [3 0 R /Fit]');
		elseif($this->ZoomMode=='fullwidth')
			$this->out('/OpenAction [3 0 R /FitH null]');
		elseif($this->ZoomMode=='real')
			$this->out('/OpenAction [3 0 R /XYZ null null 1]');
		elseif(!is_string($this->ZoomMode))
			$this->out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode/100).']');
		if($this->LayoutMode=='single')
			$this->out('/PageLayout /SinglePage');
		elseif($this->LayoutMode=='continuous')
			$this->out('/PageLayout /OneColumn');
		elseif($this->LayoutMode=='two')
			$this->out('/PageLayout /TwoColumnLeft');
	}

	protected function putheader()
	{
		$this->out('%PDF-'.$this->PDFVersion);
	}

	protected function puttrailer()
	{
		$this->out('/Size '.($this->n+1));
		$this->out('/Root '.$this->n.' 0 R');
		$this->out('/Info '.($this->n-1).' 0 R');
	}

	protected function enddoc()
	{
		$this->putheader();
		$this->putpages();
		$this->putresources();
		//Info
		$this->newobj();
		$this->out('<<');
		$this->putinfo();
		$this->out('>>');
		$this->out('endobj');
		//Catalog
		$this->newobj();
		$this->out('<<');
		$this->putcatalog();
		$this->out('>>');
		$this->out('endobj');
		//Cross-ref
		$o=strlen($this->buffer);
		$this->out('xref');
		$this->out('0 '.($this->n+1));
		$this->out('0000000000 65535 f ');
		for($i=1;$i<=$this->n;$i++)
			$this->out(sprintf('%010d 00000 n ',$this->offsets[$i]));
		//Trailer
		$this->out('trailer');
		$this->out('<<');
		$this->puttrailer();
		$this->out('>>');
		$this->out('startxref');
		$this->out($o);
		$this->out('%%EOF');
		$this->state=3;
	}

	protected function beginpage($orientation)
	{
		$this->page++;
		$this->pages[$this->page]='';
		$this->state=2;
		$this->x=$this->lMargin;
		$this->y=$this->tMargin;
		$this->FontFamily='';
		//Page orientation
		if(!$orientation)
			$orientation=$this->DefOrientation;
		else
		{
			$orientation=strtoupper($orientation{0});
			if($orientation!=$this->DefOrientation)
				$this->OrientationChanges[$this->page]=true;
		}
		if($orientation!=$this->currentOrientation)
		{
			//Change orientation
			if($orientation=='P')
			{
				$this->wPt=$this->fwPt;
				$this->hPt=$this->fhPt;
				$this->w=$this->fw;
				$this->h=$this->fh;
			}
			else
			{
				$this->wPt=$this->fhPt;
				$this->hPt=$this->fwPt;
				$this->w=$this->fh;
				$this->h=$this->fw;
			}
			$this->pageBreakTrigger=$this->h-$this->bMargin;
			$this->currentOrientation=$orientation;
		}
	}

	protected function endpage()
	{
		//End of page contents
		$this->state=1;
	}

	protected function newobj()
	{
		//Begin a new object
		$this->n++;
		$this->offsets[$this->n]=strlen($this->buffer);
		$this->out($this->n.' 0 obj');
	}

	protected function dounderline($x,$y,$txt)
	{
		//Underline text
		$up=$this->currentFont['up'];
		$ut=$this->currentFont['ut'];
		$w=$this->getStringWidth($txt)+$this->ws*substr_count($txt,' ');
		return sprintf('%.2f %.2f %.2f %.2f re f',$x*$this->k,($this->h-($y-$up/1000*$this->FontSize))*$this->k,$w*$this->k,-$ut/1000*$this->FontSizePt);
	}

	protected function parsejpg($file)
	{
		//Extract info from a JPEG file
		$a=GetImageSize($file);
		if(!$a)
			throw new Exception('Missing or incorrect image file: '.$file);
		if($a[2]!=2)
			throw new Exception('Not a JPEG file: '.$file);
		if(!isset($a['channels']) || $a['channels']==3)
			$colspace='DeviceRGB';
		elseif($a['channels']==4)
			$colspace='DeviceCMYK';
		else
			$colspace='DeviceGray';
		$bpc=isset($a['bits']) ? $a['bits'] : 8;
		//Read whole file
		$f=fopen($file,'rb');
		$data='';
		while(!feof($f))
			$data.=fread($f,4096);
		fclose($f);
		return array('w'=>$a[0],'h'=>$a[1],'cs'=>$colspace,'bpc'=>$bpc,'f'=>'DCTDecode','data'=>$data);
	}

	protected function parsepng($file)
	{
		//Extract info from a PNG file
		$f=fopen($file,'rb');
		if(!$f)
			throw new Exception('Can\'t open image file: '.$file);
		//Check signature
		if(fread($f,8)!=chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10))
			throw new Exception('Not a PNG file: '.$file);
		//Read header chunk
		fread($f,4);
		if(fread($f,4)!='IHDR')
			throw new Exception('Incorrect PNG file: '.$file);
		$w=$this->freadint($f);
		$h=$this->freadint($f);
		$bpc=ord(fread($f,1));
		if($bpc>8)
			throw new Exception('16-bit depth not supported: '.$file);
		$ct=ord(fread($f,1));
		if($ct==0)
			$colspace='DeviceGray';
		elseif($ct==2)
			$colspace='DeviceRGB';
		elseif($ct==3)
			$colspace='Indexed';
		else
			throw new Exception('Alpha channel not supported: '.$file);
		if(ord(fread($f,1))!=0)
			throw new Exception('Unknown compression method: '.$file);
		if(ord(fread($f,1))!=0)
			throw new Exception('Unknown filter method: '.$file);
		if(ord(fread($f,1))!=0)
			throw new Exception('Interlacing not supported: '.$file);
		fread($f,4);
		$parms='/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
		//Scan chunks looking for palette, transparency and image data
		$pal='';
		$trns='';
		$data='';
		do
		{
			$n=$this->freadint($f);
			$type=fread($f,4);
			if($type=='PLTE')
			{
				//Read palette
				$pal=fread($f,$n);
				fread($f,4);
			}
			elseif($type=='tRNS')
			{
				//Read transparency info
				$t=fread($f,$n);
				if($ct==0)
					$trns=array(ord(substr($t,1,1)));
				elseif($ct==2)
					$trns=array(ord(substr($t,1,1)),ord(substr($t,3,1)),ord(substr($t,5,1)));
				else
				{
					$pos=strpos($t,chr(0));
					if($pos!==false)
						$trns=array($pos);
				}
				fread($f,4);
			}
			elseif($type=='IDAT')
			{
				//Read image data block
				$data.=fread($f,$n);
				fread($f,4);
			}
			elseif($type=='IEND')
				break;
			else
				fread($f,$n+4);
		}
		while($n);
		if($colspace=='Indexed' && empty($pal))
			throw new Exception('Missing palette in '.$file);
		fclose($f);
		return array('w'=>$w,'h'=>$h,'cs'=>$colspace,'bpc'=>$bpc,'f'=>'FlateDecode','parms'=>$parms,'pal'=>$pal,'trns'=>$trns,'data'=>$data);
	}

	protected function freadint($f)
	{
		//Read a 4-byte integer from file
		$a=unpack('Ni',fread($f,4));
		return $a['i'];
	}

	protected function textstring($s)
	{
		//Format a text string
		return '('.$this->escape($s).')';
	}

	protected function escape($s)
	{
		//Add \ before \, ( and )
		return str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$s)));
	}

	protected function putstream($s)
	{
		$this->out('stream');
		$this->out($s);
		$this->out('endstream');
	}

	protected function out($s)
	{
		//Add a line to the document
		if($this->state==2)
			$this->pages[$this->page].=$s."\n";
		else
			$this->buffer.=$s."\n";
	}
}
?>