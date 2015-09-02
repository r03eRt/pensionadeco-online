<?php

class Fields
{
	private static $fieldOption = 'tgt_room_fields'; // option name
	private $fieldsList; // fields array
	private static $sFields; // Fields object of the class.
	
	private function __construct()
	{
		$this->fieldsList = get_option(self::$fieldOption);
		if ( !is_array( $this->fieldsList ) )
		{
			$this->fieldsList = array();
		}
	}
	
	public static function getInstance()
	{
		if ( empty ( self::$sFields ) )
		{
			self::$sFields = new Fields();
		}
		
		return self::$sFields;
	}
	
	/*
	 * Insert new fields into database
	 * @name insert
	 * @param array $args ( field_name , field_type, field_options, can_seacrh, activated )
	 */
	public function insert($args)
	{
		$default = array('field_name' => '', 'field_type' => '', 'field_options' => '', 'can_search' => 1, 'activated' => 1 );
		$params = wp_parse_args( $args , $default );		
		
			$this->fieldsList[] = $params;
			update_option( self::$fieldOption, $this->fieldsList );			
					
			return true;
		
	}
	
	/*
	 * Delete a field by a given id
	 */
	public function deleteField($id)
	{
		if ( !empty( $this->fieldsList[$id] ) )
		{
			unset( $this->fieldsList[$id] );
			update_option( self::$fieldOption, $this->fieldsList );			
			return true;
		}
		else
			return false;
	}
	
/*
	 * Update a field by a given id
	 */
	public function updateField($id,$field,$value)
	{
		if ( !empty( $this->fieldsList[$id] ) )
		{			
			$this->fieldsList[$id][$field] = $value;	
				
			update_option( self::$fieldOption, $this->fieldsList );			
			return true;
		}
		else
			return false;
	}
	
	/*
	 * Get fields array
	 */
	public function getFields()
	{
		return $this->fieldsList;
	}
	
	
	/**
	 * get fields for search page
	 */
	public function getSearchFields()
	{
		$result = array();
		foreach ( $this->fieldsList as $id => $field )
		{
			if ( $field['can_search'] && $field['activated'] )
			{
				$result[$id] = $field;
			}
		}
		return $result;
	}
	
	/**
	 * get fields actived
	 */
	public function getActivatedFields()
	{
		$result = array();
		foreach ( $this->fieldsList as $id => $field )
		{
			if ( $field['activated'] )
			{
				$result[$id] = $field;
			}
		}
		return $result;
	}
}

//$test = Fields::getInstance();
//$test->insert( array('field_name' => '', 'field_type' => '', 'field_options' => '', 'can_search' => 1, 'activated' => 1 ) );

?>