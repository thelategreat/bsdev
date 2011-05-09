<?php

/* --------------------------------------------------------------------------
 * General info
 *-------------------------------------------------------------------------- */
$config['site_name'] = "Bookshelf";

/* --------------------------------------------------------------------------
 * General Flags
 *-------------------------------------------------------------------------- */
// the hashing used for passwords one of 'plain | none | sha1 | md5'. default is sha1
$config['password_hash_type'] = 'sha1';
// the salt length
$config['password_salt_length'] = 16;

/* --------------------------------------------------------------------------
 * Pager
 *-------------------------------------------------------------------------- */
$config['list_page_size'] = 15;

/* --------------------------------------------------------------------------
 * Emailer
 *-------------------------------------------------------------------------- */
$config['email_from'] = 'system@bookshelf.ca';
$config['email_from_name'] = 'Bookshelf System (Do Not Reply)';


/* --------------------------------------------------------------------------
 * Image info
 *-------------------------------------------------------------------------- */
$config['max_image_size'] = 120;  // kbytes
$config['max_image_width'] = 1024;
$config['max_image_height'] = 768;

// image list pager
$config['image_browser_page_size'] = 5;

/* --------------------------------------------------------------------------
 * Comments
 *-------------------------------------------------------------------------- */
$config['allow_comments'] = true;

?>
