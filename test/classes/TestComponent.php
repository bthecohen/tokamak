<?php
use Tokamak\Dom\Component;


class TestComponent extends Component
{
	protected function render()
	{
		$head = $this->appendElement('head');
		$head->appendElement('title', null, $this->data['title']);
		$head->appendElement('meta', array('charset' => $this->dom->encoding));
	}
}