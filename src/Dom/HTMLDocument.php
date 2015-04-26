<?php
namespace Tokamak\Dom;

/**
 * Class HTMLDocument
 * @package Tokamak\Dom
 * An HTML document template.
 */
abstract class HTMLDocument extends Document {
	public static $doctype = array("html", null, null);

	public function __construct (array $data = null, array $doctype = null){
		if(!isset($doctype)){
			$doctype = static::$doctype;
		}

		parent::__construct($data, $doctype);
	}

	/**
	 * Render the document to an HTML string
	 * @return string
	 */
	public function __toString()
	{
		return $this->toHTML();
	}
	/**
	 * Renders the document to an HTML string.
	 * @return string
	 */
	protected function toHTML(){
		return $this->dom->saveHTML();
	}
}