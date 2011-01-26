<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class articles_model extends Model
{

  function articles_model()
  {
    parent::Model();
  }

	function get_article_list( $category = NULL, $page = 1, $limit = NULL )
	{
		$q =<<<EOF
SELECT a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, 
			 owner, ast.status, gt.name as group_name 
	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
	WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id
EOF;

	if( $category ) {
		$q .= " AND (ac.category = " . $this->db->escape($category) . " OR ac.category = 'General')";
	}
		
	$q .= " ORDER BY publish_on DESC, title ASC ";
		
		if( $limit ) {
			$q .= " LIMIT $limit";
			$q .= " OFFSET " . ($page - 1) * $limit;
		}
		
		return $this->db->query( $q );
	}

	function get_published_article_list( $group = NULL, $limit = NULL, $page = 1 )
	{
		$q =<<<EOF
SELECT a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, ast.status, gt.name as `group`
	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
	WHERE a.category = ac.id AND a.status = ast.id AND a.status = 3 AND gt.id = a.group AND publish_on <= NOW() 
EOF;

			if( $group ) {
				$q .= " AND (a.group = $group)";
			}

			$q .= " ORDER BY publish_on DESC";
			
			if( $limit ) {
				$q .= " LIMIT $limit";				
				$q .= " OFFSET " . ($limit * ($page-1));
			}

			//echo '<pre>' . $q . '</pre><br/>';

			return $this->db->query( $q );		
	}

  function get_published_articles( $group, $limit = NULL, $page = 1 )
  {
    if( $group <= 0 ) {
      $group = 1;
    }

    $q =<<<SQL
select a.id, a.title, fnStripTags(a.body) as body, a.excerpt, ac.category, a.publish_on, a.author, ast.status, gt2.name as `group`
	from articles as a, group_tree as gt, group_tree as gt2, article_categories as ac, article_statuses as ast
	where
		a.group = gt2.id and
		gt2.lft between gt.lft and gt.rgt and
		a.category = ac.id and
		a.status = ast.id and
		a.status = 3 and
		gt.id = $group
SQL;

    $q .= " ORDER BY a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    $ret = $this->db->query( $q );
    return $ret;
  }


  function get_published_articles_al( $group, $limit = NULL, $page = 1 )
  {
    $q =<<<SQL
    select a.id, title, fnStripTags(body) as body, excerpt, ac.category, publish_on, author, ast.status, gt.name as `group`
    from articles as a, group_tree as gt, article_categories as ac, article_statuses as ast
      where a.group = gt.id
        and a.category = ac.id
        and a.status = ast.id
        and a.status = 3
        and (gt.id = $group OR gt.parent_id = $group)
SQL;

    $q .= " ORDER BY a.publish_on DESC";

    if( $limit ) {
      $q .= " LIMIT $limit";
      $q .= " OFFSET " . ($limit * ($page-1));
    }

    $ret = $this->db->query( $q );
    return $ret;
  }

	function get_article( $id )
	{
		$q =<<<EOF
SELECT a.id, title, body, ac.category, updated_on, author, ast.status, publish_on, gt.name as `group` 
	FROM articles as a, article_categories as ac, article_statuses as ast, group_tree as gt
	WHERE a.category = ac.id AND a.status = ast.id AND a.group = gt.id 
EOF;
	
		$q .= " AND a.id = " . $this->db->escape(intval($id));
		return $this->db->query( $q );
		
	}

	function category_select( $default = 0 )
	{
		$s = '<select name="category" id="category-sel">';
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

	function group_select( $default = 0 )
	{
		$s = '<select name="group" id="group-sel">';
		$res = $this->db->query("SELECT * FROM article_groups ORDER BY id");
		foreach( $res->result() as $row ) {
			$s .= '<option value="' . $row->id . '" ';
			if( $default == $row->id ) {
				$s .= " selected ";
			}
			$s .= '>' . $row->group . '</option>';
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

	function priority_select( $default = 2 )
	{
		$priorities = array( "1" => "high", "2" => "med", "3" => "low");
		$s = '<select name="display_priority">';
		foreach( $priorities as $k => $v ) {
			$s .= '<option value="' . $k . '" ';
			if( $default == $k ) {
				$s .= " selected ";
			}
			$s .= '>' . $v . '</option>';
		}
		$s .= '</select>';
		
		return $s;				
	}

}