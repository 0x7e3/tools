<?php
/**
 * Class StackParse - check for balanced brackets {} in file.
 * @requirement php > 5.3
 * @license GNU GPL
 */
namespace StackParse;

/**
* StackParse
*/
class StackParse
{
	/**
     * @const char
     */
    const OPEN_BRACKETS = '{';

    /**
     * @const char
     */
    const CLOSE_BRACKETS = '}';

	/**
     * @var array[]
     */
	private $errorLines;

	/**
     * @var \SplStack
     */
	private $stack;

	/**
     * @var integer
     */
	private $count;
	
	function __construct()
	{
		$this->errorLines = array();
		$this->stack = new \SplStack;
		$this->count = 0;
	}

    /**
     * manipulation with stack
     * @see    \SplStack
     * @param  char $char 			must be character of brackets
     * @param  int $lineNumber 		current line
     * @return int 					if stack is empty return 1, else 0
     */
	private function stackParse($char = '', $lineNumber = 0)
	{
		if ( ($char == '') || ($lineNumber == 0) ){ return false; }

		if ($char == self::OPEN_BRACKETS){
			$this->stack->push(array('value' => self::OPEN_BRACKETS, 'line' => $lineNumber));
			return 0;
		}

		if ($char == self::CLOSE_BRACKETS){ 
			if ($this->stack->isEmpty()){ 
				return 1; 
			} else {
				try{
					$this->stack->pop();
				}catch(RuntimeException $e){
					return 1;
				}
			}
		}

		return 0;
	}

	/**
     * check stack (in end)
     */
	private function checkStack()
	{
		while (!($this->stack->isEmpty())){
			try{
				$delete = $this->stack->pop();
				array_push($this->errorLines, $delete['line']);
				$this->count++;
			}catch(RuntimeException $e){};
		}
	}

	/**
     * main function: parse the file and check stack (in end)
     * @param  string $fileName 	filename for parsing
     */
	public function parse($fileName)
	{
		$fp = fopen($fileName, 'r');
		if (!$fp){
			echo "Can't open file ".$fileName.PHP_EOL;
			exit(1);
		}

		$lineNumber = 1;
		$char = '';
		while (!feof($fp)){

			$char = fgetc($fp);
			
			$result = $this->stackParse($char, $lineNumber);
			
			if ($result == 1){ 
				array_push($this->errorLines, $lineNumber);
				$this->count++;
			}

			if ($char == PHP_EOL){ $lineNumber++; }
		}

		fclose($fp);

		$this->checkStack();
	}

	/**
     * display result
     * @return int 		number of errors
     */
	public function display()
	{
		if ($this->count == 0){ 
			echo "File is correct: brackets are balanced!".PHP_EOL;
			return 0; 
		}

		asort($this->errorLines);

		foreach ($this->errorLines as $lineNumber){
			echo "Error on line: ".$lineNumber.PHP_EOL;
		}

		echo "Number of errors: ".$this->count.PHP_EOL;

		return $this->count;
	}
}