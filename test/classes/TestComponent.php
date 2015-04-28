<?php
use Tokamak\Dom\Component;


class TestComponent extends Component
{
	protected function render($data)
	{
		$head = $this->appendElement('head');
		$head->appendElement('title', null, $data['title']);
		$head->appendElement('meta', array('charset' => $this->dom->encoding));
	}
}