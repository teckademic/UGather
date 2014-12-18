<?php
	
	// function filled_out
	// ensures form is completely filled out
	function filled_out($form_vars) 
	{
	  // test that each variable has a value
	  foreach ($form_vars as $key => $value) 
	  {
		 if ((!isset($key)) || ($value == '')) 
		 {
			return false;
		 }
	  }
	  return true;
	} // end function filled_out

	// function valid_email
	// ensures email address is valid
	function valid_email($address) 
	{
		// check an email address is possibly valid
		if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)) 
		{
			return true;
		} 
		else 
		{
			return false;
		}
	} // end function valid_email
	
	// function hasSpecialCharacters
	// checks to see if unallowed special characters (only a-z A-Z 0-9 _ allowed)
	// have been input - true if yes, false if not
	function hasSpecialCharacters($inputString)
	{
		// check & not user
		if (preg_match( "@[^a-z0-9_]+@i", $inputString))
		{
			return true;
		} // end if
		else
		{
			return false;
		} // end else
	} // end function hasSpecialCharacters
	
	// function hasSpecialCharacters2
	// checks to see if unallowed special characters (only a-z A-Z 0-9 - ' . , and space allowed)
	// have been input - true if yes, false if not
	function hasSpecialCharacters2($inputString)
	{
		// check & not user
		if (preg_match( "@[^a-z0-9-'., ]+@i", $inputString))
		{
			return true;
		} // end if
		else
		{
			return false;
		} // end else
	} // end function hasSpecialCharacters2
?>