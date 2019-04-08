#!/usr/bin/env php
<?php
/**
 * @synopsis stack_ll.php [FILE]
 * 
 * @description Check for balanced brackets {} in FILE and print 
 * a warning with number of line, where was an error.
 */

require 'stack_ll.class.php';

if ($_SERVER['argc'] < 1){
	echo 'Please specify file name'. PHP_EOL;
	exit(1);
}

@$fileName = $_SERVER['argv'][1];

if ( !file_exists($fileName) || !is_readable($fileName) ){
	echo 'File not exists or not readable' . PHP_EOL;
	exit(1);
}

$stack = new \StackParse\StackParse;

$stack->parse($fileName);

$stack->display();