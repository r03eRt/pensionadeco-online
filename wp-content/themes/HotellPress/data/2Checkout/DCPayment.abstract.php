<?php
/**
 * Enter description here ...
 * 
 */
abstract class DCPayment {
	private $errors = array();
	private $fields = array();
	private $options = array();
	/**
	 * 
	 * set, get, insert errors 
	 */
	/**
	 * 
	 * if respone from Payment gateway is valided, return array that no items
	 * else return array contain errors with key and value
	 */
	public function getErrors(){
		return $this->errors;
	}
	protected function setErrors( $errors ){
		return $this->errors = (array)$errors;
	}
	protected function addError ( $key, $val ){
		$this->errors[$key] = $val;
	}
	/**
	 * 
	 * get, set, insert hidden field to HTML code
	 */
	protected function getFields(){
		return $this->fields;
	}
	protected function setFields( $fields ){
		return $this->fields = $fields;
	}
	protected function addField( $name, $value){
		$this->fields[$name] = $value;
	}
	/**
	 * get, set options 
	 */
	protected function getOptions(){
		return $this->options;
	}
	protected function setOptions( $options ){
		return $this->options = $options;
	}
	protected function addOption( $key, $value ){
		$this->options[$key] = $value;
	}
	protected function getOption( $key ){
		return $this->options[$key];
	}
	/**
	 * 
	 * Set data for payment gateway
	 * @param array $pData, contain infomation about products, each item is equivalent a product
	 * @param array $options
	 * @param array $extFields
	 */
	abstract public function setData( $pData, $options, $extFields = array() );
	/**
	 * 
	 * return HTML submit to 2Checkout gateway
	 */
	public function generalHTML(){		
		$count = count( $this->errors );
		$test = $this->getOption( 'test_mod' );
		
		if ( $count > 0){			
			if ($test){
				echo '<pre>';
				print_r ( $this->errors );
				echo '</pre>';
			}
			return;
		}
		
		$ops = & $this->getOptions();
		$link = $ops['action'];
		$sButton = $ops['submit']; //html of submit button
		$fields = & $this->getFields();
		$form = $this->getOption( 'form' );
		
		echo '<form action="'. $link .'" method="post" name="'.$form.'" id="'.$form.'">';
		foreach ( $fields as $k => $v )
			echo '<input type="hidden" name="'. $k .'" value="'. $v .'" />';
		echo $sButton;
		echo '</form>'; 
	} 
}  