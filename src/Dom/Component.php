<?php
namespace Tokamak\Dom;
use DOMDocument;
use DOMNode;
use Closure;

/**
 * Class Component
 * @package Tokamak\Dom
 * A component is a reusable/composable template for a DOM subtree.
 * Could be used to define a widget, page partial, etc. It behaves
 * slightly differently from an Element in that it can have multiple
 * elements at its top level; i.e., it has no explicit root node.
 * When a component is appended to an element, its top-level elements
 * are all added to the parent element.
 * If a component is appended to another component,
 * its top-level elements become siblings to the other component's,
 * effectively merging the two components and appending them to a common parent.
 * @todo: add selectors to make it easy to access descendant nodes within a component.
 */
abstract class Component extends Node {

	/**
	 * @var array the data that is passed into the component
	 */
	protected $data;

	/**
	 * @var DOMNode;
	 */
	protected $parentNode;

	/**
	 * Accepts array of data/state and builds the component's
	 * DOM structure via the implemented render method.
	 * @param DOMDocument $dom  The ancestor DOMDocument instance.
	 * @param array $data       Array of arbitrarty data/state passed to the component.
	 */
	public function __construct(DOMDocument $dom, array $data = null){
		$this->dom = $dom;

		$this->render($data);
	}

	/**
	 * Since a Component can have multiple top-level elements,
	 * the semantics of "appending" are ambiguous. In this implementation,
	 * calling "append" on a component instance will append a node to
	 * the parent element as a sibling after this component.
	 * @param Node $child
	 * @return Node
	 */
	public function append(Node $child){
		while($child->hasDomNodes()){
			if(isset($this->parentNode)){
				// Component has already rendered, and append is being called
				// as part of a chained method call. Append node(s) directly
				// to parent element's DOMNode.
				$this->parentNode->appendChild($child->getDomNode());
			} else {
				// Add the dom nodes to the queue for the parent element to append.
				$this->addDomNode($child->getDomNode());
			}
			if(isset($callback)){
				$child->renderCallback($callback);
			}
		}

		return $child;
	}

	/**
	 * Set a parent DOMNode.
	 * Once this instance has already been appended by its parent,
	 * any subsequent chained method calls to append must be
	 * appended directly to the parent's underlying DOMNode.
	 * @param DOMNode $parent
	 */
	public function setParentNode(DOMNode $parent){
		$this->parentNode = $parent;
	}

}