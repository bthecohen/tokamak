<?php
namespace Tokamak\Dom;

use DOMDocument;
use DOMNode;
use SplQueue;
use Closure;

/**
 * Class Node
 * @package Tokamak\Dom
 * An abstraction over a DOMNode, providing a simple API for
 * building document and component templates by implementing
 * the render method.
 * @throws \RuntimeException
 */
abstract class Node
{
	/**
	 * @var string Tokamak will look for components in this namespace if they are not
	 * defined elsewhere.
	 */
	protected static $COMPONENT_NAMESPACE = '\Tokamak\Dom\Components\\';

	/**
	 * @var DOMDocument The underlying DOMDocument instance.
	 * Normally created by Tokamak\Dom\Document instance.
	 */
	protected $dom;

	/**
	 * @var SplQueue<DOMNode> Queue of dom nodes to be appended to the parent node.
	 */
	protected $domNodes;

	/**
	 * Append one Node instance as a child of another.
	 * Must be implemented differently for Element and Component,
	 * because the former is a single element that can have children,
	 * whereas the latter can represent a list of multiple
	 * top-level elements and components. Meanwhile, Document
	 * appends children to the top-level DOMDocument instance.
	 * @param Node $node An Element or Component to be appended.
	 * @return Node Returns the child node for method chaining.
	 */
	abstract public function append(Node $node);

	/**
	 * Syntactic sugar for constructing and then appending a new Element.
	 * Abstracts away the need to pass the ancestor DOMDocument to the child.
	 * @param string $name The element name ('div', 'a', 'body', etc.)
	 * @param array $attributes Associative array of the element's attributes and their values.
	 *                          For "class", the value can also be an array of class names.
	 * @param string $content The text content of the element.
	 * @param Closure $callback A callback closure to be executed within the context of the child node.
	 *                          Allows a callback-chaining style for building the DOM tree.
	 * @return Element Returns the child Element for method chaining.
	 */
	public function appendElement($name, array $attributes = null, $content = '',  Closure $callback = null){
		$child = $this->append(new Element($this->dom, $name, $attributes, $content));
		if(isset($callback)){
			$boundCallback = $callback->bindTo($child, $child);
			$boundCallback();
		}
		return $child;
	}

	/**
	 * Syntactic sugar for constructing and then appending a new Component.
	 * Abstracts away the need to pass the ancestor DOMDocument to the child.
	 * If a user-defined Component class of the requested name is not found,
	 * will look in the Tokamak\Dom\Components namespace for a built-in component
	 * class.
	 * @param string $name  The fully-qualified class name of a user-defined Component,
	 *                      or the name of a Component class in the Tokamak\Dom\Components namespace.
	 * @param array $data   Array of arbitrary data/state to be passed to the component
	 * @return Component    Returns the child Element for method chaining.
	 * @param Closure $callback A callback closure to be executed within the context of the child node.
	 *                          Allows a callback-chaining style for building the DOM tree.
	 * @throws \RuntimeException Thrown if $name does not refer to a defined Component class.
	 */
	public function appendComponent($name, array $data = null, Closure $callback = null)
	{
		if (is_a($name, 'Tokamak\Dom\Component', true)){
			// The specified $name is a subclass of Component
			$component = new $name($this->dom, $data);
		} else if (is_a(self::$COMPONENT_NAMESPACE . $name, 'Tokamak\Dom\Component', true)){
			// The requested component name is a built-in Component
			$qualifiedName = self::$COMPONENT_NAMESPACE . $name;
			$component =  new $qualifiedName($this->dom, $data);
		} else {
			throw new \RuntimeException("Component $name not defined.");
		}
		$child =  $this->append($component);

		if(isset($callback)){
			$boundCallback = $callback->bindTo($child, $child);
			$boundCallback();
		}

		return $child;
	}

	/**
	 * Inject an DOMDocument element as the ancestor of this node.
	 * @param DOMDocument $dom
	 */
	public function setDom(DOMDocument $dom)
	{
		$this->dom = $dom;
	}

	/**
	 * Get the underlying DOMDocument ancestor instance.
	 * @return DOMDocument
	 */
	public function getDom()
	{
		return $this->dom;
	}

	/**
	 * Add a DOMNode to the queue of nodes that will
	 * be appended to this node's parent.
	 * @param DOMNode $node
	 */
	protected function addDomNode(DOMNode $node)
	{
		if(!isset($this->domNodes)){
			$this->domNodes = new SplQueue();
		}
		$this->domNodes->enqueue($node);
	}

	/**
	 * Remove a DOMNode from the queue of nodes
	 * to be appended to the parent and return it.
	 * @return DOMNode
	 */
	public function getDomNode()
	{
		if($this->hasDomNodes()){
			$node = $this->domNodes->dequeue();
			return $node;
		} else {
			return null;
		}
	}

	/**
	 * Does this instance have remaining DOMNode instances
	 * to be appended to the parent?
	 * @return bool
	 */
	public function hasDomNodes()
	{
		if(!isset($this->domNodes)){
			return false;
		}
		return !$this->domNodes->isEmpty();
	}

	/**
	 * Called by constructor.
	 * Must be implemented to define the DOM structure
	 * of an element or component. Works by adding
	 * nodes to the domNodes queue, either explicitly
	 * or via calls to Node::append.
	 * @return void
	 */
	abstract protected function render();
}