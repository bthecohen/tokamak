<?php
include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/ExampleDocument.php');

$template = new ExampleDocument(array("title" => "Example"));
$template->setFormatOutput(true);
$html =  $template->toString();

echo $html;