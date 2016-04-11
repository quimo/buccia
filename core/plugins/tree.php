<?php
	class Tree extends Database {
	
		/*
			CREATE TABLE IF NOT EXISTS `accessi_categorie` (
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`id_parent` int(11) NOT NULL DEFAULT '0',
				`nome` varchar(255) NOT NULL,
				`visible_to_members` tinyint(1) NOT NULL DEFAULT '0',
				`visibility` tinyint(1) NOT NULL DEFAULT '1',
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

			INSERT INTO `accessi_categorie` (`id`, `id_parent`, `nome`, `visible_to_members`, `visibility`) VALUES
			(1, 0, 'Amministrazione', 0, 1),
			(2, 0, 'Consulenza', 1, 1),
			(3, 0, 'Produzione', 1, 1),
			(4, 0, 'Design', 0, 1),
			(5, 0, 'Materiali', 0, 1),
			(6, 0, 'HR', 0, 1),
			(7, 1, 'Amministrazione2', 0, 1),
			(8, 1, 'Amministrazione3', 0, 1),
			(9, 8, 'Amministrazione3-1', 0, 1),
			(10, 7, 'Amministrazione2-1', 0, 1),
			(11, 7, 'Amministrazione2-2', 0, 1);
			
			--------------------- demo ------------------------
			
			include 'core/bc_conf.php';
			include 'core/bc_conn.php';
			include 'core/bc_db.php';
			include 'core/plugin/tree.php';

			$conn = new bcConn();
			$tree = new Tree($conn->get());
			echo $tree->getTreeAsSelect('nome','myselect',array('nome','visibility'));
			echo $tree->getTreeAsUl('nome','mymenu',1,array('nome','visibility'));
		*/
	
		const TREE_TABLE = 'accessi_categorie';
		const TREE_BRANCH_PARENT_ID = 'id_parent';
		const TREE_LEVEL_SEPARATOR = '&nbsp;&raquo;&nbsp;';
		const TREE_TARGET_PAGE = 'target_page.php';
		const TREE_FIELD_SEPARATOR = ' ';
		const TREE_CLASS_LI_PREFIX = 'leaf';
		const TREE_CLASS_UL_PREFIX = 'branch';
		const TREE_CLASS = 'tree';
		
		private $config;
	
		public function __construct($conn) {
			$this->db = parent::__construct($conn);
			$this->config = new bcConf();
		}
		
		/* 	
			TREE
			ritorna l'albero dei contenuti come stringa di testo
		*/
		
		public function getTree($order='',$fields) {
			$data = $this->getRoots($order);
			if ($data) {
				$html = '';
				for ($i = 0; $i < count($data); $i++) {
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= $this->getBranchChildren($data[$i]['id'],$order,$fields);
				}
				return $html;
			}	
			return false;
		}
		
		private function getBranchChildren($id,$order,$fields) {	
			$order = ($order) ? "ORDER BY $order" : '';
			$data = $this->query("SELECT * FROM ".Tree::TREE_TABLE." WHERE ".Tree::TREE_BRANCH_PARENT_ID." = $id");
			if ($data) {
				$html = '';
				for ($i = 0; $i < count($data); $i++) {
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= $this->getBranchChildren($data[$i]['id'],$order,$fields);
				}
				return $html;
			}
		}
		
		/* 	------------------------------ */
			
		/* 	
			TREE as UL
			ritorna l'albero dei contenuti come lista non ordinata
		*/
		
		public function getTreeAsUL($order='',$id='',$target=0,$fields, $where = '',$tree_class='') {
			$data = $this->getRoots($order);
			if ($data) {
				$id = ($id) ? "id=\"$id\"" : '';
				$html = "<ul $id class=\"".Tree::TREE_CLASS."\">";
				for ($i = 0; $i < count($data); $i++) {
					$html .= "<li class=\"".$tree_class."\" id=\"".$tree_class."_{$data[$i]['id']}\"><span>";
					if ($target) $html .= "<a href=\"".$this->config->getBaseUrl('baseurl').Tree::TREE_TARGET_PAGE."?id={$data[$i]['id']}\">";
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= "</span>";
					if ($target) $html .= "</a>";
					$html .= $this->getBranchChildrenAsLI($data[$i]['id'],$order,$target,$fields,$where,$tree_class);
					$html .= "</li>";
				}
				$html .= "</ul>";
				return $html;
			}	
			return false;
		}
		
		private function getBranchChildrenAsLI($id,$order,$target,$fields,$where,$tree_class) {	
			$order = ($order) ? "ORDER BY $order" : '';
			$andwhere = ($where) ? $where : 1; 
			$data = $this->query("SELECT * FROM ".Tree::TREE_TABLE." WHERE ".Tree::TREE_BRANCH_PARENT_ID." = $id AND $andwhere");
			if ($data) {
				$html = "<ul id=\"".Tree::TREE_CLASS_UL_PREFIX."_{$id}\" class=\"".Tree::TREE_CLASS_UL_PREFIX."\">";
				for ($i = 0; $i < count($data); $i++) {
					$html .= "<li class=\"".$tree_class."\" id=\"".$tree_class."_{$data[$i]['id']}\"><span>";
					if ($target) $html .= "<a href=\"".$this->config->getBaseUrl('baseurl').Tree::TREE_TARGET_PAGE."?id={$data[$i]['id']}\">";
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= "</span>";
					if ($target) $html .= "</a>";
					$html .= $this->getBranchChildrenAsLI($data[$i]['id'],$order,$target,$fields,$where,$tree_class);
					$html .= "</li>";
				}
				$html .= "</ul>";
				return $html;
			}
		}
		
		/* 	------------------------------ */
		
		
		
		
		
		/* 	
			TREE as SELECT
			ritorna l'albero dei contenuti come select
		*/
		
		public function getTreeAsSelect($order='',$id='',$fields, $class='', $where = '', $selected='',$default=false) {
			$data = $this->getRoots($order,$where);
			if ($data) {
				$select_id = ($id) ? "id=\"$id\"" : '';
				$select_name = ($id) ? "name=\"$id\"" : '';
				$html = "<select $select_id $select_name class=\"".Tree::TREE_CLASS." $class\">";
				if ($default) $html .= "<option value=\"\">-</option>";
				for ($i = 0; $i < count($data); $i++) {
					$html .= "<option";
					if ($selected && $selected == $data[$i]['id']) $html .= " selected=\"selected\"";
					$html .= " value=\"{$data[$i]['id']}\" class=\"".Tree::TREE_CLASS_LI_PREFIX."\" id=\"".Tree::TREE_CLASS_LI_PREFIX."_{$data[$i]['id']}\">";
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= "</option>";
					$html .= $this->getBranchChildrenAsOption($data[$i]['id'],$order,$fields,$where,$selected);
				}
				$html .= '</select>';
				return $html;
			}	
			return false;
		}
		
		private function getBranchChildrenAsOption($id,$order,$fields,$where,$selected) {	
			$order = ($order) ? "ORDER BY $order" : '';
			$andwhere = ($where) ? $where : 1; 
			$data = $this->query("SELECT * FROM ".Tree::TREE_TABLE." WHERE ".Tree::TREE_BRANCH_PARENT_ID." = $id AND $andwhere");
			if ($data) {
				$html = "<optgroup id=\"".Tree::TREE_CLASS_UL_PREFIX."_{$id}\" class=\"".Tree::TREE_CLASS_UL_PREFIX."\">";
				for ($i = 0; $i < count($data); $i++) {
					$html .= "<option";
					if ($selected && $selected == $data[$i]['id']) $html .= " selected=\"selected\"";
					$html .= " value=\"{$data[$i]['id']}\" class=\"".Tree::TREE_CLASS_LI_PREFIX."\" id=\"".Tree::TREE_CLASS_LI_PREFIX."_{$data[$i]['id']}\">";
					$html .= $this->concatenateFields($data[$i],$fields);
					$html .= "</option>";
					$html .= $this->getBranchChildrenAsOption($data[$i]['id'],$order,$fields,$where,$selected);
				}
				$html .= "</optgroup>";
				return $html;
			}
		}
		
		/* 	------------------------------ */
		
		
		
		function getParentId($id) {
			$dummy = $this->query("SELECT ".Tree::TREE_BRANCH_PARENT_ID." FROM ".Tree::TREE_TABLE." WHERE id = $id");
			return $dummy[0][Tree::TREE_BRANCH_PARENT_ID];
		}
		
		function getAncestor($id) {
			$branch = $this->getBranch($id);
			if ($branch[0]['parent_id'] == 0) {
				return $branch;
			} else {
				$this->getAncestor($branch[0]['parent_id']);
			}
		}
		
		function getAllParentsId($id,&$parents='') {
			$parent_id = $this->getParentId($id);
			if ($parent_id) {
				$parents[] = $parent_id;
				$this->getAllParentsId($parent_id,$parents);
				return $parents;
			}	
			return false;
		}
		
		function getChildrenId($id) {
			$dummy = $this->query("SELECT id FROM ".Tree::TREE_TABLE." WHERE ".Tree::TREE_BRANCH_PARENT_ID." = $id");
			if ($dummy) {
				$children = array();
				for ($i = 0; $i < count($dummy); $i++) {
					$children[] = $dummy[$i]['id'];
				}
				return $children;
			}	
			return false;
		}
		
		function getAllChildrenId($id,&$allchildren='') {
			$children = $this->getChildrenId($id);
			if ($children) {
				for ($i = 0; $i < count($children); $i++) {
					$allchildren[] = $children[$i];
					$this->getAllChildrenId($children[$i],$allchildren);
				}	
				return $allchildren;
			}
			return false;
		}
		
		
		private function concatenateFields($data,$fields) {
			$html = '';
			foreach ($fields as $value) {
				$html .= $data[$value].Tree::TREE_FIELD_SEPARATOR;
			}
			$html = substr($html,0,-1);
			return $html;
		}
		
		public function getRoots($order='',$where='') {
			$order = ($order) ? "ORDER BY $order" : '';
			$andwhere = ($where) ? $where : 1;
			return $this->query("SELECT * FROM ".Tree::TREE_TABLE." WHERE ".Tree::TREE_BRANCH_PARENT_ID." = 0 AND $andwhere $order");
		}
	
		public function getBranch($id) {
			return $this->query("SELECT * FROM ".Tree::TREE_TABLE." WHERE id = $id LIMIT 1");
		}
	}
?>	