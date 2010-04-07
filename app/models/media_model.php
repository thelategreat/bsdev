<?php
/**
 * Media Model Class
 *
 * @package  Bookself
 * @subpackage  Media
 * @category  Models
 * @author  J Knight 
 */

include_once('tag_model.php');

class media_model extends Tag_Model
{

	/**
	 *
	 */
  function media_model()
  {
    parent::Model();
  }
	
	/**
	 *
	 */
	function add_upload( $uuid, $data, $user )
	{
				
		$this->db->set('uuid',  $uuid );
		$this->db->set('user', $user );
		$this->db->set('title', $data['orig_name'] );
		$this->db->set('type', $data['file_ext'] );
		$this->db->set('created_on', 'NOW()', false );
		$this->db->set('updated_on', 'NOW()', false );
		
		$this->db->insert('media');
		return $uuid;
	}

	
	/**
	 *
	 */
	function add_link( $uuid, $url, $user )
	{
		$purl = parse_url( $url );
		
		// get vimeo meta data
		if( $purl['host'] == "www.vimeo.com" || $purl['host'] == "vimeo.com" ) {
			$json_url = 'http://www.vimeo.com/api/oembed.json?url='.rawurlencode($url);
			$curl = curl_init( $json_url );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			$ret = curl_exec($curl);
			curl_close($curl);		
			$data = json_decode( $ret );
						
			$this->db->set('caption',  $data->title . " by: " . $data->author_name );
			$this->db->set('thumbnail',  $data->thumbnail_url );
		}
				
		$this->db->set('uuid',  $uuid );
		$this->db->set('user', $user );
		$this->db->set('title', $url );
		$this->db->set('type', "link" );
		$this->db->set('created_on', 'NOW()', false );
		$this->db->set('updated_on', 'NOW()', false );
		
		$this->db->insert('media');
		return $uuid;
	}


	/**
	 *
	 */
	function remove_upload( $uuid )
	{
		$this->db->where( 'uuid', $uuid );
		$this->db->delete('media');
	}
	
	/**
	 *
	 */
	function get_media( $uuid = null, $stags = array(), $page = 1, $limit = 10 )
	{
		/*
		SELECT distinct media.* from media, media_tag_map, media_tags 
			WHERE media_tag_map.media_tag_id = media_tags.id 
				AND media_tag_map.media_id = media.id 
				AND media_tags.name IN ('blue', 'groovy');
				
			- or -

			select distinct media.*
				from media join media_tag_map on media_tag_map.media_id = media.id
					join media_tags on media_tag_map.media_tag_id = media_tags.id
						AND media_tags.name IN ('blue', 'groovy');			
		*/
		$items = array();
		if( $uuid ) {
			$this->db->where('uuid', $uuid );
		}
		
		// searching tags
		if( count( $stags )) {
			// NOTE: The proper behaviour here requires a patch to CI's
			// system/database/DB_active_rec.php
			$this->db->join('media_tag_map', 'media_tag_map.media_id = media.id' );
			$this->db->join('media_tags', 'media_tag_map.media_tag_id = media_tags.id');
			$this->db->where_in( 'media_tags.name', $stags );
			$this->db->or_like('caption',$stags[0]);
			$this->db->distinct();
			// seems like CI get involved here and checks the field names, so annoying.
			// so we cant do db->select( 'media.*' )
			$this->db->select('media.id, uuid, title, type, created_on, updated_on, user, caption, description, license');
			
		}
		
		$this->db->offset( ($page - 1) * $limit );
		$this->db->limit( $limit );
		$this->db->from('media');
		$results = $this->db->get();
		//show_error( $this->db->last_query());
		
		foreach( $results->result() as $row ) {
			$row->tags = $this->get_tags( 'media', $row->id );
			$items[] = $row;
		}
		
		return $items;
	}
	
	function get_media_usage( $uuid )
	{
		$query = "select path from media as m, media_map as mm where m.id = mm.media_id and m.uuid = '$uuid'";
		$res = $this->db->query( $query );
		return $res;
	}
	
	function get_media_for_path( $path, $slot = 'general' )
	{
		$files = array();
		$results = $this->db->query("SELECT m.* FROM media as m, media_map as mm WHERE mm.media_id = m.id AND mm.path = '$path' AND mm.slot = '$slot' ORDER BY mm.sort_order");
		foreach( $results->result() as $row ) {
			$info['fname'] = $row->title;
			$info['url'] =  $row->uuid;
			$info['author'] = $row->user;
			$info['type'] = $row->type;
			$info['uuid'] = $row->uuid;
			$info['date'] = $row->updated_on;
			$info['size'] = '';
			if( $info['type'] != 'link') {
				$info['size'] = '<p class="error">missing</p>';
					if( file_exists('media/' . $row->uuid )) {
						$info['size'] = pretty_file_size(filesize('media/' . $row->uuid ));
					}
			}
			
			switch( $info['type'] ) {
				case 'link':
				  if( isset($info['thumbnail']) && strlen($info['thumbnail'])) {
						//$info['thumbnail'] = '<img src="' . $info['thumbnail'] . '" width="70" />';											
				  } else {
						$info['thumbnail'] = "/media/logos/youtube.jpg";					
					}
					break;
				default:
					$info['thumbnail'] = '/media/' . $info['uuid'];
		  }
						
			$files[] = $info;
		}
		return $files;
	}
	
	/**
	 *
	 */
	function update_media( $uuid, $meta, $tags )
	{
		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		
		$this->db->where( 'uuid', $uuid );
		$this->db->update('media', $meta );
		
		$this->save_tags( 'media', $item->id, $tags );
	}
		
	function files_for_path( $path, $slot = 'general' )
	{
		$this->db->where('path', $path );
		$this->db->where('slot', $slot );
		return $this->db->get('media_map');
	}
		
	function add_media_for_path( $path, $uuid, $slot='general' )
	{
		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		//if( count($item) == 0 ) {
		//	return;
		//}
		
		$query = "SELECT max(sort_order) as maxso FROM media_map WHERE path = '$path' AND slot = '$slot'";
		//echo $query;
		$so = $this->db->query($query)->row();
		if( $so ) {
			$max = $so->maxso;
		} else {
			$max = 0;
		}
		//$data = array('path' => $path, 'media_id' => $item->id );
		$this->db->set('path', $path );
		$this->db->set('slot', $slot );
		$this->db->set('media_id', $item->id );
		$this->db->set('sort_order', $max + 1 );
		$this->db->insert('media_map');
		//log_message('debug','foo');
	}
		
	function move( $dir, $path, $slot, $uuid )
	{
		$table = 'media_map';
		
		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		$crit = "slot = '$slot' AND path = '$path'";
		
		//echo "SELECT id, sort_order FROM $table WHERE $crit";
		$item = $this->db->query("SELECT id, sort_order FROM $table WHERE $crit AND media_id = $item->id")->row();
		if( $item ) {
			if( $dir == 'up' ) {
				//echo "SELECT id, sort_order FROM $table WHERE $crit AND sort_order < $item->sort_order ORDER BY sort_order DESC LIMIT 1";
				$swap = $this->db->query("SELECT id, sort_order FROM $table WHERE $crit AND sort_order < $item->sort_order ORDER BY sort_order DESC LIMIT 1")->row();
			} else {
				//echo "SELECT id, sort_order FROM $table WHERE $crit AND sort_order > $item->sort_order ORDER BY sort_order DESC LIMIT 1";
				$swap = $this->db->query("SELECT id, sort_order FROM $table WHERE $crit AND sort_order > $item->sort_order ORDER BY sort_order ASC LIMIT 1")->row();
			}
			if( $swap ) {
				//echo $swap->id;
				$this->db->query("UPDATE $table SET sort_order = $swap->sort_order WHERE id = $item->id");
				$this->db->query("UPDATE $table SET sort_order = $item->sort_order WHERE id = $swap->id");
			}
		}
	}
}