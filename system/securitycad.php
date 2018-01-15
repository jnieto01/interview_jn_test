<?php

class SecurityCad{

	public function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	public function maxLenght($data) {
		if (strlen($data) <= 300){
			return true;
		}
		return false;
	}	
	  
}


?>