<?php
	
	class WPAdapter extends Database {
	
		public function __construct($conn) {
			$this->db = parent::__construct($conn);
		}
		
		public function getAllPosts() {
			return $this->query("SELECT * FROM wp_posts WHERE post_author = 2 AND post_status = 'publish' AND post_type = 'post' AND post_parent = 0 ORDER BY id DESC");
		}
		
		public function getFeaturedImage($post) {
			return $this->query("SELECT guid FROM wp_posts WHERE post_parent = $post AND post_type = 'attachment'");
		}
			
	}
?>