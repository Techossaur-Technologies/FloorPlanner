<?php
final class sidebar
{
	private $baseurl="";
	private $menu="";
	private $submenu = "";

	private $conn;

	private $menulist = array();

	function __construct($menu, $submenu)
	{
		$this->menu = $menu;
		$this->submenu = $submenu;
	}


	// ========== Start private methods==============//
	private function connect(){
		$this->conn = new PDO("mysql:host=localhost;dbname=db_floorplanner", "dipankar", "dipankar@123");
	}

	// Function for generating menu list //
	private function getmenulist(){
		$tmp = array(); // holder of all the menu informations //

		$dept 	 = '1';
		$menus   = $this->getMenuByDept($dept,$_SESSION['user_type']);   // Getting the department assigned menu id //
		$parents = $this->getParentMenus($menus,$_SESSION['user_type']); // Getting the parent_id of the assigned menus //

		foreach($parents as $pmenu){

			$menu_id = $pmenu;
			$result = $this->conn->prepare("SELECT * FROM `menu` WHERE menu_id = :menu_id");
			$result->execute(['menu_id' => $menu_id]);

			while ( $data = $result->fetch(PDO::FETCH_ASSOC) ) {
				$tmp_sub    = array();
				$in_values = implode(',', $menus);
				$result_sub = $this->conn->prepare("SELECT * FROM `menu` WHERE parent_id = ".$data['menu_id']." AND menu_id IN(".$in_values.")");
				$result_sub->execute();
				while ( $data_sub = $result_sub->fetch(PDO::FETCH_ASSOC) ) {
					$tmp_sub[] = $data_sub;
				}

				// Building the main menu array //
				$tmp[] = array(
						  'name' => $data['menu_name'],
						  'icon'=> $data['icon'],
						  'submenu' => $tmp_sub
						 );
			}
		}

		$this->menulist = $tmp;

	}

	private function getMenuByDept($dept, $type){

		$parents = array();

		if($type == "Admin"){

			$parent_id    = 0;
			$admin_access = 'yes';
			$result       = $this->conn->prepare('SELECT menu_id FROM `menu` WHERE parent_id != :parent_id AND admin_access = :admin_access');
			$result->execute(['parent_id' => $parent_id, 'admin_access' => $admin_access]);

			while ( $data = $result->fetch(PDO::FETCH_ASSOC) ) {
				$parents[] = $data['menu_id'];
			}
		}
		if($type == "Client"){

			$parent_id     = 0;
			$client_access = 'yes';
			$result        = $this->conn->prepare('SELECT menu_id FROM `menu` WHERE parent_id != :parent_id AND client_access = :client_access');
			$result->execute(['parent_id' => $parent_id, 'client_access' => $client_access]);

			while ( $data = $result->fetch(PDO::FETCH_ASSOC) ) {
				$parents[] = $data['menu_id'];
			}
		}

		return $parents;
	}

	private function getParentMenus($menu_arr, $type){

		$arr = array();
		if($type == "Admin"){

			$parent_id    = 0;
			$admin_access = 'yes';
			$result       = $this->conn->prepare('SELECT menu_id FROM `menu` WHERE parent_id = :parent_id AND admin_access = :admin_access  ORDER BY menu_id ASC');
			$result->execute(['parent_id' => $parent_id, 'admin_access' => $admin_access]);

			while ( $data = $result->fetch(PDO::FETCH_ASSOC) ) {
				$arr[] = $data['menu_id'];
			}
		}
		if($type == "Client"){

			$parent_id     = 0;
			$client_access = 'yes';
			$result        = $this->conn->prepare('SELECT menu_id FROM `menu` WHERE parent_id = :parent_id AND client_access = :client_access  ORDER BY menu_id ASC');
			$result->execute(['parent_id' => $parent_id, 'client_access' => $client_access]);

			while ( $data = $result->fetch(PDO::FETCH_ASSOC) ) {
				$arr[] = $data['menu_id'];
			}
		}

		return $arr;
	}

	// ========== End private methods==============//

	// ========== Start public methods==============//
	public function initConnection()
	{
		$this->connect();
	}

	public function set_baseUrl($url)
	{
		$this->baseurl = $url;
	}

	public function createMenu()
	{
		// Generating menu array //
		$this->getmenulist();
		echo '<aside class="main-sidebar"><section class="sidebar">';
		echo '<ul class="sidebar-menu">';
		echo '<li class="header"></li>';
		$i = 0;
		foreach($this->menulist as $menu )
		{
			$li_active = '';
			$li_class  = 'treeview';
			if($this->menu == $i)
			{
				$li_class  = 'active treeview';
				$ulclass   = ' class = "active treeview-menu"';
				$flag      = true;
				$li_active =  '<a href="#"><i class="fa '.$menu['icon'].'"></i> <span>'.$menu['name'].'</span> <i class="fa fa-angle-left pull-right"></i>
				</a>';
			}else{
				$ulclass   = ' class = "treeview-menu"';
				$li_active =  '<a href="#"><i class="fa '.$menu['icon'].'"></i> <span>'.$menu['name'].'</span> <i class="fa fa-angle-left pull-right"></i>
				</a>';
				$flag      = false;
			}

			echo '<li class="'.$li_class.'">'.$li_active;
			//echo '<li class="'.$li_class.'">'.$li_active.'<span>'.$menu['name'].'</span><i class="fa fa-angle-left pull-right"></i></a>';
			$subarr = $menu['submenu']; // Select The submenu list array key //
			$pin    = 0;

			// Start Inner UL //
			echo '<ul'.$ulclass.'>';
			foreach($subarr as $submenu_info)
			{
				// Start inner LI tag //
				$liclass = '';
				// $liclass = ($flag == true and $this->submenu == $pin++) ? 'current' : '';
				//$liclass = (basename($_SERVER['PHP_SELF']) ==  $submenu_info['menu_page']) ? 'active' : 'active';
				if( (basename($_SERVER['PHP_SELF']) ==  $submenu_info['menu_page']) || $this->submenu == $submenu_info['menu_id']){
					$liclass = 'active';
				}


				if(!empty($submenu_info['menu_page'])){

					echo '<li class="'.$liclass.'"><a href="'.$this->baseurl.$submenu_info['menu_page'].'">'.htmlentities($submenu_info['menu_name']).'</a></li>';

				}else{
					echo '<li class="'.$liclass.'"><a href="'.$this->baseurl.$submenu_info['menu_page'].'">'.htmlentities($submenu_info['menu_name']).'</a></li>';
				}

			}
			echo '</ul>';
			$i++;
		}

		echo '</ul>';
		echo '</section></aside>';
	}

	// ========== End public methods==============//

} //  end class //
?>