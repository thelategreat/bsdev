<?php
/*
  This file is part of the Talon Toolkit
  Copyright (C) 2008  J. Knight <jim@talonedge.com> and
  contributors.

  Talon is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  Talon is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * OptParser - Command Line Inteface
 *
 * Parses command line options in an ordered way.
 *
 * @package default
 * @author J Knight
 * @copyright 2001-2008 J. Knight
 * @version 1.0
 **/
class OptParser
{
  protected $__iterndx = 0;
  protected $__blurb;
  
  /**
   * CTOR
   *
   * @param string the help blurb to output before the options are
   * @return void
   **/
  function __construct( $blurb = NULL)
  {
    $this->__blurb = $blurb;
    $this->options = array();
    $this->parsed = array();
  }
    
  /**
   * Print out a help screen
   *
   * @return void
   **/
  public function help( $msg = NULL )
  {
    if( $msg ) {
      print $msg . "\n";
    }
    
    if( $this->__blurb ) {
      print $this->__blurb . "\n";
    }
     
    print "where OPTIONS are:\n";
    for( $i = 0; $i < count($this->options); $i++ ) {
      $s = "";
      $s .= "  -" . $this->options[$i]['short'];
      $s .= ", --" . $this->options[$i]['long'];
      if( $this->options[$i]['takes_arg']) {
        $s .= " ARG";
      }
      printf("  %-25s %s\n", $s, $this->options[$i]['desc']);
    }
  }
      
  /**
   * Add an option
   *
   * @return void
   **/
  public function add_option( $short, $long, $desc, $arg_required = FALSE )
  {
    $opt = array();
    $opt['short'] = $short;
    $opt['long'] = $long;
    $opt['desc'] = $desc;
    $opt['value'] = NULL;
    $opt['used'] = FALSE;
    $opt['takes_arg'] = $arg_required;
    $this->options[] = $opt;
  }
    
  /**
   * Parses the arguments
   *
   * @throws exceptions on unknown opt or opt without required param
   * @return a useless array
   **/
  public function parse( $args )
  {
    array_shift( $args );
    //$args = join( $args, ' ' );

    //preg_match_all('/ (--\w+ (?:[= ] [^-]+ [^\s-] )? ) | (-\w+) | (\w+) /x', $args, $match );
    //$args = array_shift( $match );    

    $this->parsed = array(
      'input'    => array(),
      'commands' => array(),
      'flags'    => array()
    );
    
    $lastarg = NULL;
    
    foreach ( $args as $arg ) {
      // Is it a command? (prefixed with --)
      if ( substr( $arg, 0, 2 ) === '--' ) {
        // error out if we are waiting for an arg to the last opt
        if( $lastarg !== NULL ) {
          throw new Exception("Expected argument for: -" . $this->options[$lastarg]['short'] . "\n");      
        }
        $arg = substr( $arg, 2 );
        $value = preg_split( '/[= ]/', $arg, 2 );
        $com   = substr( array_shift($value), 2 );
        $value = join($value);
        if( ($opt = $this->valid_opt($arg)) === FALSE ) {
          throw new Exception("Unknown option: --$arg\n");
        }
        $this->options[$opt]['used'] = TRUE;
        if( $this->options[$opt]['takes_arg']) {
          $lastarg = $opt;
        }
        $this->parsed['commands'][$com] = !empty($value) ? $value : true;
        continue;
      }

      // Is it a flag? (prefixed with -)
      if ( substr( $arg, 0, 1 ) === '-' ) {
        // error out if we are waiting for an arg to the last opt
        if( $lastarg !== NULL ) {
          throw new Exception("Expected argument for: -" . $this->options[$lastarg]['short'] . "\n");      
        }
        $arg = substr( $arg, 1 );
        if( ($opt = $this->valid_opt($arg)) === FALSE ) {
          throw new Exception("Unknown option: -$arg\n");
        }
        $this->options[$opt]['used'] = TRUE;
        if( $this->options[$opt]['takes_arg']) {
          $lastarg = $opt;
        }
        $this->parsed['flags'][] = substr( $arg, 1 );
        continue;
      }
      
      if( $lastarg !== NULL ) {
        $this->options[$lastarg]['value'] = $arg;
        $lastarg = NULL;
      }
      else {
        $this->parsed['input'][] = $arg;
      }
    }
    
    // error out if we are waiting for an arg to the last opt
    if( $lastarg !== NULL ) {
      throw new Exception("Expected argument for: -" . $this->options[$lastarg]['short'] . "\n");      
    }
    if( $this->used_opt('help')) {
      $this->help();
      die;
    }
    return $this->parsed;
  }
  
  /**
   * Return boolean if an option was used
   *
   * @return void
   * @author J Knight
   **/
  public function used_opt( $option )
  {
    for( $i = 0; $i < count($this->options); $i++ ) {
      if( $this->options[$i]['long'] == $option || $this->options[$i]['short'] == $option ) {
        if( $this->options[$i]['used']) {
          return true;
        }
      }
    }
    return false;
  }
    
  public function opt_value( $option )
  {
    for( $i = 0; $i < count($this->options); $i++ ) {
      if( $this->options[$i]['long'] == $option || $this->options[$i]['short'] == $option ) {
        if( $this->options[$i]['used']) {
          return $this->options[$i]['value'];
        }
      }
    }
    return NULL;    
  }
    
  /**
   * Return the options we parsed
   *
   * @return an array of parsed options
   * @author J Knight
   **/
  public function opts()
  {
    $opts = array();
    for( $i = 0; $i < count($this->options); $i++ ) {
      if( $this->options[$i]['used']) {
        $opts[] = $this->options[$i];
      }
    }
    return $opts;
  }
  
  /**
   * Return the args we parsed
   *
   * @return an array of arguments not associated with options
   * @author J Knight
   **/
  public function args()
  {
    return $this->parsed['input'];
  }

  /**
   * Check is this is a valid opt
   *
   * @return void
   **/
  private function valid_opt( $opt )
  {
    for( $i = 0; $i < count($this->options); $i++ ) {
      if( $this->options[$i]['short'] == $opt || $this->options[$i]['long'] == $opt) {
        return $i;
      }
    }
    return FALSE;
  }
    
}

?>