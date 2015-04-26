<?php
use Tokamak\Dom\Component;

/**
 * Class Head
 * An example component representing the <head> section of an HTML document.
 */
class Head extends Component {
	protected function render(){
		$head = $this->appendElement('head');
		$head->appendElement('title', null, $this->data['title']);
		$head->appendElement('meta', array('charset' => $this->dom->encoding));
	}
}