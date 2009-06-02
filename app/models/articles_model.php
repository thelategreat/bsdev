<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class articles_model extends Model
{

  function articles_model()
  {
    parent::Model();
  }

	function get_article_list()
	{
		$q =<<<EOF
SELECT a.id, title, ac.category, updated_on, author, ast.status 
	FROM articles as a, article_categories as ac, article_statuses as ast
	WHERE a.category = ac.id AND a.status = ast.id
EOF;
		
		return $this->db->query( $q );
	}

	function category_select( $default = 0 )
	{
		$s = '<select name="category">';
		$res = $this->db->query("SELECT * FROM article_categories ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->category . '</option>';
		}
		$s .= '</select>';
		
		return $s;
	}

	function status_select( $default = 0 )
	{
		$s = '<select name="status">';
		$res = $this->db->query("SELECT * FROM article_statuses ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->status . '</option>';
		}
		$s .= '</select>';
		
		return $s;		
	}

}