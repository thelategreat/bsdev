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
  function __construct()
  {
    parent::__construct();
  }

	/**
	 *
	 */
	function add_upload( $uuid, $data, $user, $title )
	{

		$this->db->set('uuid',  $uuid );
		$this->db->set('user', $user );
		$this->db->set('title', $data['orig_name'] );
		$this->db->set('caption', $title );
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
		// youtube thumbnail
		else if($purl['host'] == "www.youtube.com" || $purl['host'] == "youtube.com") {
			// TODO this needs more robust parsing
			$video_id = NULL;

			// check for id in query params: http://www.youtube.com/watch?v=yjXY9kl2ljQ
			$query = explode('&', $purl['query']);
			foreach( $query as $qelem ) {
				list($key, $value) = explode("=", $qelem);
				if( $key == 'v' ) {
					$video_id = $value;
					break;
				}
			}
			// check for id at tail: http://www.youtube.com/v/_sccg1CZzi4
			if( $video_id == NULL ) {
				$tmp = explode('/', $url);
				if( count($tmp) > 2 && $tmp[count($tmp)-2] == 'v') {
					$video_id = $tmp[count($tmp)-1];
				}
			}
			// if we got something
			if( $video_id != NULL ) {
				# use the v2 jsonc feed, much cleaner
				$meta_url = 'http://gdata.youtube.com/feeds/api/videos/' . $video_id . '?alt=jsonc&v=2';
				$curl = curl_init( $meta_url );
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_TIMEOUT, 10);
				$ret = curl_exec($curl);
				curl_close($curl);

				$data = json_decode( $ret );
				//echo '<pre>' . $ret . '</pre>';
				$this->db->set('caption',  $data->data->title . " by: " . $data->data->uploader );
				$this->db->set('thumbnail',  $data->data->thumbnail->sqDefault );
			}
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


	function remove_media( $uuid, $refs = false )
	{
		if( $refs ) {
			//select * from media_map where media_id = (select id from media where uuid = 'f3fd4750-af0f-3f31-aeaf-73658836d23e')
			$this->db->query("DELETE FROM media_map WHERE media_id = (SELECT id FROM media WHERE uuid = '$uuid')");
		}
		$this->db->where( 'uuid', $uuid );
		$this->db->delete('media');
	}

	function get_media_by_id ( $id ) {
		$this->db->where('id', $id);
		$result = $this->db->get('media')->row();

		return $result;
	}

	/**
	 * Gets media given a UUID or an array of media tags
	 */
	function get_media( $uuid = null, $stags = array(), $page = 1, $limit = 10 )
	{
		if (!$page) {
				$page = 1;
			}	

		$items = array();
		if( $uuid ) {
			// This is a single ID
			$sql = "SELECT * FROM media WHERE uuid = " . $this->db->escape($uuid);
		} else {
			// Find all the ids that have the specified tags or title containing that
			$sql = "
					SELECT
						distinct(media.id)
					FROM
						media_tags
					LEFT JOIN media_tag_map ON media_tag_map.media_tag_id = media_tags.id
					LEFT JOIN media ON media_tag_map.media_id = media.id
					WHERE
						TRUE";
			if (count($stags) > 0) {
				foreach ($stags as $it) {
					$tags[] = $this->db->escape($it);
				}
				$tags_str = implode(',', $tags);
				$sql .= " AND media_tags.NAME IN ({$tags_str}) ";
				foreach ($stags as $it) {
					$it = $this->db->escape_like_str($it);
					$sql .= " OR LOWER(TRIM(media.title)) LIKE '%{$it}%' ";
					$sql .= " OR LOWER(TRIM(media.caption)) LIKE '%{$it}%' ";
				}
			}
			$sql .= " ORDER BY media.created_on DESC";
		}

		$query = $this->db->query($sql);
		$results = $query->result();

		// Now we've got the items but we need to assign the tags to those images
		// which isn't a pretty thing - or maybe I'm just not smart enough
		$items = array();
		if ($results) {
			$ids = array();
			foreach ($results as $it) {
				if ($it->id == null) continue;
				$ids[] = $it->id;
			}


			if (count($ids) > 0) {

			$ids = implode(',', $ids);

			$sql = "SELECT * FROM media WHERE id IN ({$ids})";
			$query = $this->db->query($sql);
			$media_results = $query->result();

			// This isn't very efficient, but writing a joining query was proving a hassle
			// and this won't get called often enough to worry about it for the moment		
			if ($media_results) foreach ($media_results as $it) {
				$it->tags = implode(', ', $this->get_tags( 'media', $it->id) );
				$items[] = $it;
			}
			}
		}


		return $items;
	}

	function get_media_usage( $uuid )
	{
		$CI =& get_instance();
		$CI->load->model('films_model');
		$CI->load->model('articles_model');
		$CI->load->model('event_model');

		$sql = "SELECT path FROM media 
					LEFT JOIN media_map ON media.id = media_map.media_id 
					WHERE media.uuid = '$uuid'
					AND path IS NOT null";
		$query = $this->db->query( $sql ) ;
		$result = $query->result();

		$return = array();
		if ($result) {
			foreach ($result as $it) {
				
				$item = explode('/', $it->path);
				switch ($item[1]) {
					case 'article':
					case 'articles':
						$obj= $CI->articles_model->get_article($item[2]);
						if (!$obj) break;
						$obj->type = 'article';
						$obj->edit_url = '/admin/articles/edit/' . $item[2];
					break;
					case 'event':
					case 'events':
						$obj = $CI->event_model->get_event($item[2]);
						if (!$obj) break;
						$obj->type = 'event';
						$obj->edit_url = '/admin/event/edit_event/' . $item[2];
					break;
					case 'film':
					case 'films':
						$obj = $CI->films_model->get_film($item[2]);
						if (!$obj) break;
						$obj->type = 'film';					
						$obj->edit_url = '/admin/event/add_film/' . $item[2];
					break;
				}

				if (!isset($obj) || $obj == false) continue;
				$return[] = $obj;
			}
		}

		return $return;
	}

	
	/**
		Get the media for a particular path.  This will return all the files that are associated
		to the path
		@param Path name
		@param Media slot (optional - used to identify purpose of the media)
		@param Result limit
		@return List of file paths suitable to pass to the image helper
	*/
	function get_media_for_path( $path, $slot = false, $count = 0 )
	{
        if (substr($path,0,1) != '/') $path = '/' . $path;

        $query = "SELECT m.*, mm.slot, mm.id as map_id FROM media as m, media_map as mm WHERE mm.media_id = m.id AND mm.path = '$path' ";
        if ($slot) {
        	$query .= " AND mm.slot = '$slot' ";
        } 
        $query .= " ORDER BY mm.sort_order";
        if( $count > 0 ) {
			$query .= " LIMIT $count";
		}
		$files = array();
		$results = $this->db->query( $query );
		foreach( $results->result() as $row ) {
			$row->fname = $row->title;
			$row->url = $row->uuid;
			$row->author = $row->user;
			$row->date = $row->updated_on;
			$row->object_type = $row->type;
			if ($row->type != 'link') {
				$row->size = 'Missing';
				if( file_exists('media/' . $row->uuid )) {
					$row->size = pretty_file_size(filesize('media/' . $row->uuid ));
				}
			}

			switch($row->type) {
				case 'link':
					if (!isset($row->thumbnail) || !strlen($row->thumbnail)) {
						$row->thumbnail = 'media--logos--youtube';
					}
					break;
				default: 
					$row->thumbnail_fullpath = 'media/' . $row->uuid;
					$row->thumbnail = 'media--' . $row->uuid;
			}
			/*

			$info['id'] = $row->id;
			$info['fname'] = $row->title;
			$info['title'] = $row->title;
			$info['caption'] = $row->caption;
			$info['url'] =  $row->uuid;
			$info['author'] = $row->user;
			$info['type'] = $row->type;
			$info['uuid'] = $row->uuid;
			$info['date'] = $row->updated_on;
			$info['slot'] = $row->slot;
			$info['size'] = '';
			if( $info['type'] != 'link') {
				$info['size'] = '<p class="error">missing</p>';
					if( file_exists('media/' . $row->uuid )) {
						$info['size'] = pretty_file_size(filesize('media/' . $row->uuid ));
					}
			}

			switch( $info['type'] ) {
				case 'link':
				  if( isset($row->thumbnail) && strlen($row->thumbnail)) {
						$info['thumbnail'] =  $row->thumbnail;
				  } else {
						$info['thumbnail'] = "media--logos--youtube";
					}
					break;
				default:
					$info['thumbnail_fullpath'] = 'media/' . $info['uuid'];
					$info['thumbnail'] = 'media--' . $info['uuid'];
		  }

			$files[] = $info;
			*/

			$files[] = $row;
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

		if ($tags && $tags != null) $this->save_tags( 'media', $item->id, $tags );
	}

	function files_for_path( $path, $slot = 'general' )
	{
		$this->db->where('path', $path );
		$this->db->where('slot', $slot );
		return $this->db->get('media_map');
	}


    function add_media_for_path( $path, $uuid, $slot='general' )
	{
		$response = new stdClass();

		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		
		if( !$item || count($item) == 0 ) {
			$response->success = false;
			$response->message = "UUID $uuid not found";	
			return $response;
		}

		$this->db->where('media_id', $item->id);
		$this->db->where('path', $path);
		$result = $this->db->get('media_map');

		if ($result->num_rows() > 0) {
			$response->success = false;
			$response->message = "Media $item->id : $uuid is already assigned to this record.";
			return $response;
		}


		$query = "SELECT MAX(sort_order) as maxso FROM media_map WHERE path = '$path' AND slot = '$slot'";
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

		if ($this->db->affected_rows() > 0) {
			$response->success = true;
			$response->message = '';
		} else {
			$response->success = false;
			$response->message = 'Unknown error';
		}

		return $response;
		//log_message('debug','foo');
	}

	/**
		Change the sort order of media in the media map -
		the sort order isn't really used for anything much at the time of this writing, but it's a holdover from 
		the old code and there's no reason not to keep it working.
		@param Diretion
		@param The path in the article table
		@param Slot - not used here but a holdover that we'll keep in case there was a good reason
		@param UUID of media
	*/
	function move( $dir, $path, $slot, $uuid )
	{
		$table = 'media_map';

		$this->db->where('uuid', $uuid);
		$item = $this->db->get('media')->row();
		$crit = "slot = '$slot' AND path = '$path'";
		$crit = "path = '$path'"; 

		$sql = "SELECT id, sort_order FROM $table WHERE $crit AND media_id = $item->id";
		$item = $this->db->query($sql)->row();

		if( $item ) {
			if( $dir == 'up' ) {
				$sql = "SELECT id, sort_order FROM $table WHERE $crit AND sort_order < $item->sort_order ORDER BY sort_order DESC LIMIT 1";
				$swap = $this->db->query($sql)->row();
			} else {
				$sql = "SELECT id, sort_order FROM $table WHERE $crit AND sort_order > $item->sort_order ORDER BY sort_order ASC LIMIT 1";
				$swap = $this->db->query($sql)->row();
			}
			if( $swap ) {
				$this->db->query("UPDATE $table SET sort_order = $swap->sort_order WHERE id = $item->id");
				$this->db->query("UPDATE $table SET sort_order = $item->sort_order WHERE id = $swap->id");
			}
		}
	}
}
