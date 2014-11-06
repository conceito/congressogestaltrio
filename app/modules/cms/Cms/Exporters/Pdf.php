<?php namespace Cms\Exporters;

use HTML2PDF;

abstract class Pdf
{

	protected $ci;

	protected $mainTemplate = 'template/pdf';

	protected $headerText = '';

	protected $footerText = '';

	protected $arrayContents = array();

	protected $contentHtml = '';

	protected $savePath = '';

	protected $debug = false;

	/**     *
	 *  I : send the file inline to the browser (default).
	 * The plug-in is used if available.
	 * The name given by name is used when one selects the "Save as" option on the link generating the PDF.
	 *  D : send to the browser and force a file download with the name given by name.
	 *  F : save to a local server file with the name given by name.
	 *  S : return the document as a string. name is ignored.
	 *  FI: equivalent to F + I option
	 *  FD: equivalent to F + D option
	 *  true  => I
	 *  false => S
	 */
	protected $outputMode = 'I';


	/**
	 * @var string
	 */
	private $orientation;
	/**
	 * @var string
	 */
	private $format;
	/**
	 * @var string
	 */
	private $lang;
	/**
	 * @var bool
	 */
	private $unicode;
	/**
	 * @var string
	 */
	private $encoding;
	/**
	 * @var array
	 */
	private $margin;

	/**
	 * @var string
	 */
	private $font = 'Arial';

	/**
	 * @var array
	 */
	private $outputtedFiles = array();


	function __construct(
		$orientation = 'P', $format = 'A4', $lang = 'pt', $unicode = true, $encoding = 'UTF-8',
		$margin = array(0, 0, 0, 0)
	)
	{
		$this->ci = &get_instance();

		$this->setSavePath(fisic_path() . $this->ci->config->item('upl_arqs'));

		$this->orientation = $orientation;
		$this->format      = $format;
		$this->lang        = $lang;
		$this->unicode     = $unicode;
		$this->encoding    = $encoding;
		$this->margin      = $margin;
	}

	public function setArrayContent($arrayOfContents = array())
	{
		if (!is_array($arrayOfContents))
		{
			$arrayOfContents = array($arrayOfContents);
		}

		$this->arrayContents = $arrayOfContents;
	}

	public function getArrayContent()
	{
		return $this->arrayContents;
	}

	/**
	 * @param string $contentHtml
	 */
	public function setContentHtml($contentHtml)
	{
		$this->contentHtml = $contentHtml;
	}

	/**
	 * @return string
	 */
	public function getContentHtml()
	{
		return $this->contentHtml;
	}

	public function getPdfContent()
	{
		$asArray = $this->getArrayContent();
		if (!empty($asArray))
		{
			return $this->parseArrayContent();
		}

		return $this->renderPage($this->getContentHtml());
	}


	/**
	 * bootstrap role process
	 *
	 * @param string $pdfName
	 */
	public function render($pdfName = 'pdf')
	{
		$pdf = new HTML2PDF($this->orientation, $this->format, $this->lang, $this->unicode, $this->encoding,
			$this->margin);
		if ($this->debug)
		{
			$pdf->setModeDebug();
		}

		// store file
		$this->addOutputtedFile($this->getSavePath() . $pdfName . '.pdf');
		$pdf->setDefaultFont($this->getFont());
		$pdf->writeHTML($this->getPdfContent(), false);

		// move to destination folder
		if (strpos($this->outputMode, 'F') !== false)
		{
			$pdf->Output($this->getSavePath().$pdfName . '.pdf', $this->outputMode);
		}
		else
		{
			$pdf->Output($pdfName . '.pdf', $this->outputMode);
		}

		unset($pdf);
	}

	/**
	 * gets the page body and mount the page template
	 * @param string $pageBody
	 */
	public function renderPage($pageBody = '')
	{
		$v['pageBody'] = $pageBody;
		$page          = $this->ci->load->view($this->mainTemplate, $v, true);

		return $page;
	}

	/**
	 * loop through array of pages
	 * render page against template
	 *
	 * @return string
	 */
	private function parseArrayContent()
	{
		$html = '';

		foreach ($this->getArrayContent() as $page)
		{
			$html .= $this->renderPage($page);
		}

		return $html;
	}

	/**
	 * @param string $savePath
	 */
	public function setSavePath($savePath)
	{
		$this->savePath = $savePath;
	}

	/**
	 * return fisic path
	 * @return string
	 */
	public function getSavePath()
	{
		return fisic_path() . rtrim($this->savePath, '/') . '/';
	}

	/**
	 * @param boolean $debug
	 */
	public function debug($debug = true)
	{
		$this->debug = $debug;
	}


	/**
	 * Send the document to a given destination: string, local file or browser.
	 * Dest can be :
	 *  I : send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
	 *  D : send to the browser and force a file download with the name given by name.
	 *  F : save to a local server file with the name given by name.
	 *  S : return the document as a string. name is ignored.
	 *  FI: equivalent to F + I option
	 *  FD: equivalent to F + D option
	 *  true  => I
	 *  false => S
	 *
	 * @param mixed $outputMode
	 */
	public function setOutputMode($outputMode = 'I')
	{
		$this->outputMode = $outputMode;
	}

	/**
	 * @return string
	 */
	public function getFont()
	{
		return $this->font;
	}

	/**
	 * @param string $font
	 */
	public function setFont($font)
	{
		$this->font = $font;
	}

	/**
	 * @return array
	 */
	public function getOutputtedFiles()
	{
		return $this->outputtedFiles;
	}

	/**
	 * @param array $outputtedFiles
	 */
	public function addOutputtedFile($outputtedFiles)
	{
		$this->outputtedFiles[] = $outputtedFiles;
	}

}