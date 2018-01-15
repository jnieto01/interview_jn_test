<?php


class Ajax {

	private $db;

	public function __construct($db_name) {
		$this->db = $db_name;
	}

	public function addList($data) {	 
		// get the higest order to incress by one
		$sql = "SELECT MAX(sort_order) AS order_val FROM items_list";
		$query = $this->db->query($sql);
		while($row = $query->fetch_assoc()){
			$maxsortorder = $row['order_val'];
		}
		++$maxsortorder;

		// insert de new item
		$sql = "INSERT INTO items_list (image, description, sort_order)
		VALUES ('" . $data['image'] . "','" . $data['description'] . "','" . $maxsortorder . "')"; 
		$this->db->query($sql);
	
		// get ID of this new item
		return 	$this->db->getLastId(); 
	}	
		
	public function editList($data) {	
		
		if ($data['image'] == ''){
			$sql = "UPDATE items_list SET description = '" . $data['description']  . "' WHERE id = '" . (int)$data['id'] . "'";								
		}
		else{
			$sql = "UPDATE items_list SET image = '" . $data['image'] . "', `description` = '" . $data['description']  . "' WHERE id = '" . (int)$data['id'] . "'";					
		}

		$this->db->query($sql);
	}	

	public function deleteList($id) {
		//get the sort_order from id
		$query = $this->db->query("SELECT sort_order AS order_val FROM items_list WHERE id = '" . (int)$id . "'");
		while($row = $query->fetch_assoc()){
			$sort_order = $row['order_val'];
		}		
		// delete the id register
		$this->db->query("DELETE FROM items_list WHERE id = '" . (int)$id . "'");		
		//decress all sort_order by one from the delete resegiter
		// keep the sort_order with right numbers
		$this->db->query("UPDATE items_list SET sort_order = IFNULL(sort_order,0) - 1   WHERE sort_order > '" . (int)$sort_order . "'");
	}	

	public function getItems() {
		$query = $this->db->query("SELECT * FROM items_list ORDER BY sort_order ASC");
        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                $result[] = $row;
            }
        }else{
            $result = FALSE;
        }
        return $result;
	}

	public function getItemsTotal() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM items_list");
		while($row = $query->fetch_assoc()){
			return $row['total'];
		}	
		return 0;	
	}

	public function sortList($data) {
		//Sort just the items between ini and end in the list	
	
		// get id from the main item
		$query = $this->db->query("SELECT id FROM items_list WHERE sort_order ='" . $data['ini'] . "'");
        if($query->num_rows > 0){
            while($row = $query->fetch_assoc()){
                $id = $row['id'];
			}
			
			//---- move-----
			if ($data['ini'] < $data['end']){
			//--- direction down ------
				// ---up the items (decress order by one)
				$this->db->query("UPDATE items_list SET sort_order = IFNULL(sort_order,0) - 1  WHERE sort_order > '" . (int)$data['ini'] . "' AND sort_order <= '" . (int)$data['end'] . "'");				}
			else{
			//--- direction up ------
				// ---down the items (incress order by one)
				$this->db->query("UPDATE items_list SET sort_order = IFNULL(sort_order,0) + 1   WHERE sort_order >= '" . (int)$data['end'] . "' AND sort_order < '" . (int)$data['ini'] . "'");				
			}

			//---- set ------
			$this->db->query("UPDATE items_list SET sort_order ='" . (int)$data['end'] . "' WHERE id ='" . $id . "'");							
        }
	}

}