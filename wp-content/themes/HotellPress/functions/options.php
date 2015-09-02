<?php

class options
{
	private static $optionOption = 'tgt_room_option'; // option name
	private $optionsList; // options array
	private static $sOptions; // options object of the class.
	
	private function __construct()
	{
		$this->optionsList = get_option(self::$optionOption);
		if ( !is_array( $this->optionsList ) )
		{
			$this->optionsList = array();
		}
	}
	
	public static function getInstance()
	{
		if ( empty ( self::$sOptions ) )
		{
			self::$sOptions = new options();
		}
		
		return self::$sOptions;
	}
	
	/*
	 * Insert new options into database
	 * @name insert
	 * @param array $args ( option_name , option_type, option_options, can_seacrh, activated )
	 */
	public function insert($args)
	{
		$default = array('name' => '', 'default_price' => '');
		$params = wp_parse_args( $args , $default );		
		
			$this->optionsList[] = $params;
			update_option( self::$optionOption, $this->optionsList );			
					
			return true;
		
	}
	
	/*
	 * Delete a option by a given id
	 */
	public function deleteOption($id)
	{
		if ( !empty( $this->optionsList[$id] ) )
		{
			unset( $this->optionsList[$id] );
			update_option( self::$optionOption, $this->optionsList );			
			return true;
		}
		else
			return false;
	}
	
/*
	 * Update a option by a given id
	 */
	public function updateoption($id,$option,$value)
	{
		if ( !empty( $this->optionsList[$id] ) )
		{			
			$this->optionsList[$id][$option] = $value;	
				
			update_option( self::$optionOption, $this->optionsList );			
			return true;
		}
		else
			return false;
	}
	
	/*
	 * Get options array
	 */
	public function getOptions()
	{
		return $this->optionsList;
	}	
	
	
	
}


?>