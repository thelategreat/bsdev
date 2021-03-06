<?php if (!defined('BASEPATH')) exit('No direct script access allowed');   
  
/**
 * Auth class
 *
 * TODO: this whole class needs some refactoring. 
 *
 * @package default
 * @author J Knight
 **/
class Auth {  
  
    var $CI = null;
 		var $login_page = "/admin/login";
  
    /**
     * CTOR
     *
     * @return void
     **/
    function Auth()  
    {  
        $this->CI =& get_instance();  
          
        $this->CI->load->library('session');  
        $this->CI->load->database();  
        $this->CI->load->helper('url');  

				log_message('debug', 'Auth class initialized');
    }  
  
    /**
     * Handle a login request
     *
     * @return void
     **/
    function process_login($login = NULL)  
    {  
      // A few safety checks  
      // Our array has to be set  
      if(!isset($login))  
          return FALSE;  
            
      //Our array has to have 2 values  
      //No more, no less!  
      if(count($login) != 2)  
          return FALSE;  
            
      $username = $login[0];  
      $password = $login[1];  
        
      // Query time  
      //$query = $this->CI->db->query("SELECT * FROM users WHERE username = " 
      //  . $this->CI->db->escape($username) . ' AND passwd = ' 
      //  . $this->CI->db->escape($this->hash_password($password)));

      $query = $this->CI->db->query("SELECT * FROM users WHERE username = " . $this->CI->db->escape($username));

			log_message('debug', "Auth: request login with user: '$username' pass: '$password'");

      if ($query->num_rows() == 1)  
      { 
        $user = $query->row();  
        $login_ok = false;

        if( strlen($user->passwd) == 40 ) {
          // this is the old sha1 scheme, strlen:40
          if( $this->hash_password_old( $password ) == $user->passwd ) {
            $login_ok = true; 
          }
        } else {
          // this is the current storage scheme, strlen:64
          $salt_len = $this->CI->config->item('password_salt_length');
          if( !$salt_len ) {
            $salt_len = 16;
          }
          $salt = substr($user->passwd, 0, $salt_len );
          $hash = $this->hash_password( $password, $salt ); 
          if( $hash === $user->passwd ) {
            $login_ok = true;
          }
        }
        
        //
        if( $login_ok ) {
          $this->CI->session->set_userdata('logged_user', $username );
          $this->CI->session->set_userdata('logged_user_id', $user->id );
          // set the role
          $this->CI->db->where('id', $user->role_id );
          $query = $this->CI->db->get('user_roles');
          if( $query->num_rows() == 1 ) {
            $role = $query->row();
            $this->CI->session->set_userdata('logged_user_role', $role->role );            
          } else {
            $this->CI->session->set_userdata('logged_user_role', '' );                        
          }
          // we regen the password hash with every login
          $new_hash = $this->hash_password( $password ); 
          $this->CI->db->query("UPDATE users SET last_login = NOW(), passwd = '${new_hash}' WHERE id = " . $user->id );
          return TRUE;  
        } else {
          // bad password
					log_message('error', "Auth: failed login with passwd: '$username' pass: '$password'");
          //log_message('error', "Auth: hash: ${hash} db: " . $user->passwd );
          return FALSE;
        }
      }  
      else   
      {  
          // No existing user.  
					log_message('error', "Auth: failed login with user: '$username' pass: '$password'");
          return FALSE;  
      }      
    }  

    /**
     * This is dumb
     *
     * @return void
     **/
    function restrict($logged_out = FALSE)  
    {  
        // If the user is logged in and he's trying to access a page  
        // he's not allowed to see when logged in,  
        // redirect him to the index!  
        if ($logged_out && $this->logged_in())  
        {  
            redirect($this->login_page); 
        } 
         
        // If the user isn' logged in and he's trying to access a page 
        // he's not allowed to see when logged out,  
        // redirect him to the login page!  
        if ( ! $logged_out && ! $this->logged_in())   
        {  
            $this->CI->session->set_userdata('redirected_from', $this->CI->uri->uri_string()); // We'll use this in our redirect method.  
            redirect($this->login_page); 
        } 
    } 
     
     /**
      * Restrict to a role
      *
			* can take an array of roles or a single role as a string
      */
     function restrict_role( $roles )
     {
			 if( is_array($roles)) {
				$ok = false;
				foreach( $roles as $role ) {
					if( $this->CI->session->userdata('logged_user_role') == $role ) {
						return;
					}
				}
			}
			else {
				if( $this->CI->session->userdata('logged_user_role') == $roles ) {
					return;
      	}
			}
			
			if( $this->logged_in() ) {
				show_error('You do not have permission to view that resource');
			} else {
	  		redirect($this->login_page);				
			}
     }
     
		/**
		 * Check against database if a user has permissions to view a resource
		 *
		 * TODO: document the db end of this
		 *
		 * Return boolean
		 */
		function restrict_role_db( $do_redirect = true, $redir_path = null )
		{
			if( !$this->logged_in() ) {
				if( $do_redirect ) {
					if( $redir_path != null ) {
						$this->CI->session->set_flashdata('login_redir', $redir_path );
					}
					redirect($this->login_page);
				} else {
					return false;
				}
			}
			
			// admin role can do anything
			if( $this->CI->session->userdata('logged_user_role') == 'admin' )
				return TRUE;
			
			$user = $this->CI->session->userdata('logged_user');
			
			$q =<<<ELF
				SELECT u.id, u.username, ur.role, r.route, rrm.allow FROM 
						users as u, 
						user_roles as ur,
						route_role_map as rrm,
						routes as r
					WHERE
						r.id = rrm.route_id AND
						rrm.role_id = ur.id AND
						u.role_id = ur.id AND
						u.username = '$user'
ELF;

			$res = $this->CI->db->query($q);
			$uri = trim(uri_string());
			// get rid of the trailing slash if there is one
			if( substr($uri, -1) == '/' )
				$uri = substr($uri, 0, -1);				
			//echo '<br/>--&gt;' . $uri;
			
			if( $res->num_rows()) {
				foreach( $res->result() as $row ) {
					//echo '<br/>[' . $row->route . "]";
					if( preg_match( '#^' . $row->route . '$#', $uri) && $row->allow == 1 ) {
						return TRUE;
					}
				}
			}
			return FALSE;
		}

    /** 
     * 
     * Checks if a user is logged in 
     * 
     * @access  public 
     * @return  boolean 
     */  
    function logged_in() 
    { 
        if ($this->CI->session->userdata('logged_user') == FALSE)  
        {  
            return FALSE;  
        }  
        else   
        {  
            return TRUE;  
        }  
    }

    /**
     * Smoke the session
     *
     * @return void
     **/
    function logout()   
    {  
			$this->CI->session->unset_userdata('logged_user');
			$this->CI->session->unset_userdata('logged_user_id');
			$this->CI->session->unset_userdata('logged_user_role');
      $this->CI->session->sess_destroy();
      return TRUE;  
    }
        
    function redirect( $url )  
    {  
        redirect($url);  
    }

		function username()
		{
			return $this->CI->session->userdata('logged_user', NULL );
		}

    function userid()
		{
			return $this->CI->session->userdata('logged_user_id', NULL );
		}


    function hash_password( $pass, $salt = null )
    {
      $salt_len = $this->CI->config->item('password_salt_length');
      if(!$salt_len) {
        $salt_len = 16;
      }
      if( $salt === null ) {
        $salt = substr(md5(uniqid(mt_rand(), true)), 0, $salt_len ); 
      } //else {
        //$salt = substr($salt, 0, $salt_len);
      //}
      return $salt . hash('sha256', $salt . $pass);
    }

		/**
		 * Return the hashed password based on the algorithm specified in
		 * the config via PASSWORD_HASH key.
		 * Possible algorithms are:
		 * - sha1 (default)
		 * - md5
		 * - none | plain
		 *
		 * @returns a value as a string (hex)
		 */
		function hash_password_old( $password )
		{
			$passtype = $this->CI->config->item('password_hash_type');
			$cpasswd = 'NULL';

			switch( $passtype ) {
				case 'none':
				case 'plain':
				$cpasswd = $password;
				break;
				case 'md5':
				$cpasswd = md5($password);
				break;
				default:
				$cpasswd = sha1($password);
			}

			return $cpasswd;
		}

}
