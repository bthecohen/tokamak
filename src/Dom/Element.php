<?php
namespace Tokamak\Dom;

use DOMDocument;
use Closure;

/**
 * Class Element
 * @package Tokamak\Dom
 * A Node implementation representing a standard DOM element.
 */
class Element extends Node
{

	/**
	 * @var string  The element name ('div', 'a', 'body', etc.)
	 */
	protected $name;

	/**
	 * @var array   Associative array of the element's attributes and their values.
	 *              For "class", the value can also be an array of class names.
	 */
	protected $attributes;

	/**
	 * @var string  The text content of the element.
	 */
	protected $content;

	/**
	 * @var \DOMElement The underlying DOMElement object
	 */
	protected $node;

	/**
	 * Construct a DOM element and add it to the "domNodes" queue to be appended to the parent
	 * @param DOMDocument $dom The underlying DOMDocument
	 * @param string $name The element name ('div', 'a', 'body', etc.)
	 * @param array $attributes  Associative array of the element's attributes and their values.
	 *                           For "class", the value can also be an array of class names.
	 * @param string $content   The text content of the element.
	 */
	public function __construct(DOMDocument $dom, $name, array $attributes = null, $content = '')
	{
		$this->dom = $dom;
		$this->name = $name;
		$this->content = $content;
		$this->attributes = $attributes;

		// build the DOM subtree of child elements and components
		$this->render();
	}

	/**
	 * Builds the \DOMElement instance and adds it to the domNodes queue
	 * @param mixed|null $data
	 */
	protected function render($data = null)
	{
		$element = $this->dom->createElement($this->name, $this->content);

		if (!empty($this->attributes)) {
			foreach ($this->attributes as $attribute => $value) {
				if (is_array($value)) {
					if ($attribute !== "class") {
						throw new \InvalidArgumentException('Attributes except for "class" must be scalar values.');
					} else {
						$value = implode(" ", $value);
					}
				}
				$element->setAttribute($attribute, $value);
			}
		}

		$this->addDomNode($element);

		$this->node = $element;
	}

	/**
	 * Append another node (element or component) to this element.
	 * Returns the child element for method chaining.
	 * @param Node $child
	 * @return Node The child element, returned for method chaining.
	 */
	public function append(Node $child)
	{
		while ($child->hasDomNodes()) {
			$this->node->appendChild($child->getDomNode());
		}
		// Set the parent DOMNode of the component,
		// so that any remaining fluent method calls
		// can append directly to the node.
		if($child instanceof Component){
			$child->setParentNode($this->node);
		}

		return $child;
	}
}