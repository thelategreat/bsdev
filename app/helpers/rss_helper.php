<?php

/*
$feed = new Feeder( Feeder::$RSS2 );
$feed->setTitle('fugu');
$feed->setLink('http://bleh.com');
$feed->setDescription('yadda yadda');
$item = $feed->addItem('foo', 'link foo', 'now', 'yadda yadda');
$item->foo = "foo";
$item = $feed->addItem('foo2', 'link foo2', 'now2', 'yadda yadda2');

echo $feed->generate();
*/

class Feeder
{
	public static $RSS2 = 0;
	
	protected $feed_type = 0;
	protected $feed_title = null;
	protected $feed_link = null;
	protected $feed_description = null;
	
	protected $items = array();
	
	function __construct( $type )
	{
		$this->feed_type = $type;
	}
	
	function setTitle( $title )
	{
		$this->feed_title = $title;
	}

	function setLink( $link )
	{
		$this->feed_link = $link;
	}

	function setDescription( $desc )
	{
		$this->feed_description = $desc;
	}
	
	function addItem( $title, $link, $date, $description )
	{
		$item = new StdClass();
		$item->title = $title;
		$item->link = $link;
		$item->date = $date;
		$item->description = $description;
		$this->items[] = $item;
		return $item;
	}
	
	function generate()
	{
		$EOL = "\n";
		
		header("Content-Type: application/xml; charset=ISO-8859-1");
		
		$s = '<?xml version="1.0" encoding="UTF-8" ?>' . $EOL;
		$s .= '<!-- feeder v0.9.2 -->' . $EOL;
		$s .= '<rss version="2.0">' . $EOL;
		$s .= '  <channel>' . $EOL;
		$s .= '    <title>'.htmlspecialchars($this->feed_title).'</title>' . $EOL;
		$s .= '    <link>'.$this->feed_link.'</link>' . $EOL;
		$s .= '    <description>'.htmlspecialchars($this->feed_description).'</description>' . $EOL;
		$s .= '    <generator>Jimi\'s PHP+RSS Alpha Dog Nightmare Fudginator v0.9.2b</generator>' . $EOL;
		$s .= '    <language>English</language>' . $EOL;
		
		foreach( $this->items as $item ) {
			$s .= '    <item>' . $EOL;
			$s .= '      <title>' . htmlspecialchars($item->title) . '</title>' . $EOL;
			$s .= '      <link>'.$item->link.'</link>' . $EOL;
			//$s .= '      <date>'.$item->date.'</date>' . $EOL;
			$s .= '      <description>'.htmlspecialchars($item->description).'</description>' . $EOL;
			if( isset( $item->foo )) {
				$s .= '<foo>' . $item->foo . '</item>';
			}
			$s .= '    </item>' . $EOL;						
		}
		
		$s .= '  </channel>' . $EOL;
		$s .= '</rss>' . $EOL;
		
		echo $s;		
	}
	
}

?>