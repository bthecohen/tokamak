<?php
namespace Tokamak\Dom;

use DOMDocument;
use Closure;

/**
 * Class Document
 * @package Tokamak\Dom
 * Represents the root node of a DOM.
 * Extend this class to create a template class for
 * a particular document.
 */
abstract class Document extends Node {

	/**
	 * @var string The XML version of the document. Not used in HTML5 docs.
	 */
	public static $version = '1.0';

	/**
	 * @var string The character encoding of the document.
	 */
	public static $encoding = 'UTF-8';


	/**
	 * The underlying DOMDocument instance over which this library provides an abstraction.
	 * @var DOMDocument
	 */
	protected $dom;

	/**
	 * Initialize the document by passing it data/state.
	 * @param mixed $data
	 * @param array $doctype
	 */
	public function __construct($data = null, array $doctype = null){
		$imp = new \DOMImplementation();

		// Optionally, create a <!DOCTYPE> tag
		if(is_array($doctype)){
			$dtd = $imp->createDocumentType($doctype[0], $doctype[1], $doctype[2]);
			$this->dom = $imp->createDocument(null, null, $dtd);
		} else {
			$this->dom = $imp->createDocument(null, null);
		}

		$this->dom->encoding = static::$encoding;
		$this->dom->version = static::$version;
		$this->dom->formatOutput = true;

		// build the DOM
		$this->render($data);
	}

	/**
	 * Set whether the string output should be
	 * formatted with newLines.
	 * @param bool $bool
	 */
	public function setFormatOutput($bool){
		$this->dom->formatOutput = $bool;
	}

	/**
	 * Append a Node instance to the top-level DOM root.
	 * @param Node $child An Element or Component.
	 * @return Node Returns the child for method-chaining.
	 */
	public function append(Node $child){
		while($child->hasDomNodes()){
			$childNode = $child->getDomNode();
			$this->dom->appendChild($childNode);
		}
		// Set the parent DOMNode of the component,
		// so that any remaining fluent method calls
		// can append directly to the node.
		if($child instanceof Component){
			$child->setParentNode($this->dom);
		}

		return $child;
	}

	/**
	 * Alias for "magic" __toString method.
	 * @return string
	 */
	public function toString(){
		return $this->__toString();
	}
	/**
	 * Render a string representation of the document.
	 * @return string
	 */
	abstract public function __toString();

}