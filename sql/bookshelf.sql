-- MySQL dump 10.13  Distrib 5.1.41, for apple-darwin9.5.0 (i386)
--
-- Host: localhost    Database: bookshelf
-- ------------------------------------------------------
-- Server version	5.1.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clicks` int(11) DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `blurb` varchar(256) DEFAULT NULL,
  `title` varchar(128) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `owner` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` VALUES (1,0,'2010-09-25 00:00:00','2010-09-22 00:00:00','http://bookshelf.ca','Visit the bookshelf','With The Grain','2010-09-22 09:50:59','admin'),(2,0,'2010-09-30 00:00:00','2010-09-23 00:00:00','',NULL,'Creativity on Demand','2010-09-22 16:54:08','admin'),(3,0,'2011-09-29 00:00:00','2010-10-01 00:00:00','http://www.melindaburns.ca',NULL,'Melinda Burns','2010-09-22 16:55:00','admin'),(4,0,'2010-09-29 00:00:00','2010-09-22 00:00:00','http://greensweep.ca',NULL,'Green Sweep','2010-09-22 16:55:35','admin'),(5,0,'2010-10-29 00:00:00','2010-10-01 00:00:00','',NULL,'Ouderkirk & Taylor','2010-09-22 17:23:30','admin');
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_categories`
--

LOCK TABLES `article_categories` WRITE;
/*!40000 ALTER TABLE `article_categories` DISABLE KEYS */;
INSERT INTO `article_categories` VALUES (1,'General'),(2,'Books'),(3,'eBar'),(4,'Cinema'),(5,'On The Town'),(6,'What up');
/*!40000 ALTER TABLE `article_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_groups`
--

DROP TABLE IF EXISTS `article_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_groups`
--

LOCK TABLES `article_groups` WRITE;
/*!40000 ALTER TABLE `article_groups` DISABLE KEYS */;
INSERT INTO `article_groups` VALUES (1,'General'),(3,'Specific');
/*!40000 ALTER TABLE `article_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `article_statuses`
--

DROP TABLE IF EXISTS `article_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `article_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `article_statuses`
--

LOCK TABLES `article_statuses` WRITE;
/*!40000 ALTER TABLE `article_statuses` DISABLE KEYS */;
INSERT INTO `article_statuses` VALUES (1,'Draft'),(2,'Pending Review'),(3,'Published');
/*!40000 ALTER TABLE `article_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `excerpt` text,
  `body` text NOT NULL,
  `author` varchar(256) DEFAULT NULL,
  `category` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) DEFAULT NULL,
  `tags` varchar(512) DEFAULT NULL,
  `publish_on` timestamp NULL DEFAULT NULL,
  `owner` varchar(255) DEFAULT NULL,
  `group` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'Johnny Depp Opens Up Private Bahamian Getaway','Johnny Depp took the July issue of Vanity Fair to his private Bahamian island where he reminisced about Marlon Brando, watched \'Tropic Thunder\' and talked about his island getaway','<p><img style=\"float: left;\" title=\"Depp\" src=\"/media/9911a77b-8c0a-244c-4ddc-9f83afc832e9\" alt=\"Johnny Depp\" width=\"150\" height=\"74\" /></p>\n<p>Johnny Depp took the July issue of&nbsp;<a href=\"http://www.vanityfair.com/\">Vanity Fair</a>&nbsp;to his private Bahamian island where he reminisced about Marlon Brando, watched \'Tropic Thunder\' and talked about his island getaway.</p>\n<p>The whole article is NOT online, but highlights from the press release include Depp saying of Tom Cruise in \"Thunder\": \"That\'s the best I\'ve ever seen Cruise.\"</p>\n<p>When he told Marlon Brando he was buying an island in 1994, the icon got practical, saying \"What\'s the elevation? How protected are you?\" Brando, according to Depp, was being sensible, focused, and paternal. \"With hurricanes and all, he just didn\'t want me to make a mistake.\"</p>\n<p>He uses the island as a retreat, \"I can come down here and disappear. I spent the Christmas season here with Vanessa and the kids.\"</p>\n<p>The whole interview is available in the hard copy of the magazine, available June 3 in NY and LA.</p>\n<p><br /><strong>THE FULL PRESS RELEASE:</strong><br />NEW YORK, N.Y.--Johnny Depp shows Vanity Fair contributing editor Douglas Brinkley around his 45-acre private Bahamian island, Little Hall\'s Pond Cay, and tells Brinkley that the island \"is my decompression. It\'s my way of trying to return to normalcy.... Escapism is survival to me.\"<br /><br />When Brinkley asks Depp if there is any Hollywood icon he still hopes to spend time with, he says, \"I already met her. Elizabeth Taylor.\" Depp once attended dinner with Taylor and found her to be \"the best old-school dame I\'ve ever met. A regular, wonderful person. Billy Bob Thornton and Steve Martin were also there. Boy, did I take to her. For dinner she ordered liver and onions and just smothered them with salt. I admired that. She\'s an astonishingly great broad.\"<br /><br />Little Hall\'s Pond has six different beaches--named after Depp\'s partner, Vanessa Paradis, and their children, Lily Rose and Jack, as well as his mentors Hunter S. Thompson and Marlon Brando--each with a personality and cove of its own, and one patch of water deemed \"Heath\'s Place\" after the late actor Heath Ledger. There are several small residences, all solar-powered, and transportation consists of a fleet of green golf carts.</p>\n<p>\"I don\'t think I\'d ever seen any place so pure and beautiful,\" Depp tells Brinkley of the island. \"You can feel your pulse rate drop about 20 beats. It\'s instant freedom. And that rare beast--simplicity--can be had. And a little morsel of anonymity.... Whenever I was getting frustrated about being \'novelty boy\' and making movies, I told myself, Calm down. I can come down here and disappear. I spent the Christmas season here with Vanessa and the kids. You can feed hot dogs to the nurse sharks in the Exumas--but it\'s best to not swim when doing it.\"<br /><br />Depp spent much of the last year in Chicago filming Public Enemies, and tells Brinkley that it has become his favorite American city. \"Everybody [in Chicago] treated me normal. They\'d say, \'Hey, Johnny,\' then left me alone.... I visited the Art Institute and the Chicago Music Exchange. I loved looking out the car window at all those incredible neighborhoods and architecture.\"<br /><br />Depp laments the political correctness of modern Hollywood, telling Brinkley he pines for the old iconoclasts: \"Where is our generation of Dean Martins and Frank Sinatras? And the Georgie Jessels and Walter Brennans? I want Tiny Tim and Bix Beiderbecke back.\"<br /><br />Of Tom Cruise\'s performance as studio head Les Grossman in last summer\'s Tropic Thunder, Depp says, \"That\'s the best I\'ve ever seen Cruise.\" When asked if Cruise\'s portrayal reminds Depp of any Hollywood executives, he says, \"All of them.\"<br /><br />Whenever Depp gets bored or can\'t sleep, he paints. \"When I can focus on something like guitar or painting, I do,\" he says. \"I started painting people I admire, like Kerouac, Bob Dylan, Nelson Algren, Marlon Brando, Patti Smith, my girl, my kids. I painted Hunter a couple of times. Keith Richards. What I love to do is paint people\'s faces, y\'know, their eyes. Because you want to find that emotion, see what\'s going on behind their eyes.\"<br /><br />Depp talks about his two late mentors, Marlon Brando and Hunter S. Thompson, each of whom imparted his share of wisdom. He recalls a conversation he had with Brando in 1994, when he was poised to purchase Little Hall\'s Pond, but instead of expressing outright enthusiasm, Brando--who once lived on the French Polynesian atoll of Tetiaroa--asked a series of pragmatic questions: \"What\'s the elevation? How protected are you?\" Brando, according to Depp, was being sensible, focused, and paternal. \"With hurricanes and all, he just didn\'t want me to make a mistake.\"<br /><br />Depp says what he misses about Thompson \"isn\'t the Too Much Fun Club stuff. It was his steady advice. His radar detector was spot-on. He knew instantly if he didn\'t like somebody.\" Depp says the beach he named after Hunter on his island is \"the most savage and exposed of all the beaches. Gonzo Beach is pure Hunter.\"<br /><br />Talking to Brinkley about his future on the island, Depp says: \"Nobody is going to ever ruin the Land and Sea Park. It\'s like a rare gem, a diamond. I look forward to my kids growing up on the island, spending months out of the year here ... learning about sea life and how to protect sea life ... and their kids growing up here, and so on.... Theoretically, this place can add years to your life.\" Then he quotes the old adage: \"Money doesn\'t buy you happiness. But it buys you a big enough yacht to sail right up to it.\"<br /><br />The July issue of Vanity Fair hits newsstands in New York and Los Angeles on June 3 and nationally on June 9.</p>\n<p>&nbsp;</p>','admin',3,'2009-06-01 23:53:32','2009-06-01 23:53:32',3,'','2010-09-23 04:00:00','admin',1),(2,'Murdoch: Mobile Devices Mean No More Free Content','Rupee spins the world','<p>Amid&nbsp;<a href=\"http://correspondents.theatlantic.com/james_warren/2009/05/shhhh_newspaper_publishers_are_quietly_holding_a_very_very_important_conclave_today_will_you_soon_be.php\">reports</a>&nbsp;that the largest American newspaper publishers met yesterday to discuss how to save their industry, comes an&nbsp;<a href=\"http://www.huffingtonpost.com/2009/05/28/murdochs-newspaper-predic_n_208821.html\">interview</a>&nbsp;with Rupert Murdoch on the future of newspapers. Murdoch sees the rise of mobile devices for reading as leading to the end of free newspaper content:</p>\n<blockquote>\n<p>&ldquo;Newspapers may look very different. Instead of an analog product printed on paper, you may get it on a panel which will be mobile, which will receive the whole newspaper over the air, and be updated every hour or two. All of these things are possible.</p>\n<p>&ldquo;You&rsquo;re going to have to pay for your favorite newspaper on the Web,&rdquo; he added. Free content online &ldquo;is going to stop. Newspapers will be selling subscriptions on the Web. The whole thing [premium content] will be there. The Web as it is today will be vastly improved, they&rsquo;ll be much in them and you&rsquo;ll pay for them.&rdquo;</p>\n</blockquote>\n<p>&nbsp;</p>','Some Guy',5,'2009-06-02 04:59:55','2009-06-02 04:59:55',3,'ebooks, nutbar','2010-04-10 04:00:00','admin',3),(4,'The Parabolist','As a mystery novel, The Parabolist\'s greatest mystery just may be what the devil the parabolist is. Up front, the author Nicholas Ruddick supplies us with a few answers.','<p><strong>By Nicholas Rudduck - Doubleday, hadrcover, $29.95<br /></strong></p>\n<p>As a mystery novel, <em>The Parabolist</em>\'s greatest mystery just may be what the devil the parabolist is. Up front, the author Nicholas Ruddick supplies us with a few answers.</p>','admin',2,'2010-04-07 18:51:37','2010-04-07 18:51:37',3,'','2010-09-23 04:00:00','admin',1),(6,'Obama\'s Wars','A book by veteran Washington Post journalist Bob Woodward, Obama\'s Wars, portrays President Barack Obama’s national security team as having been deeply divided over Afghan policy during much of the past 20 months.','<p>WASHINGTON - The Obama administration pushed back Wednesday against a new book that describes bitter infighting among the president&rsquo;s aides who helped craft his Afghan war strategy, with some doubting it can succeed.</p>\n<p>The book by veteran Washington Post journalist Bob Woodward, &ldquo;Obama&rsquo;s Wars,&rdquo; portrays President Barack Obama&rsquo;s national security team as having been deeply divided over Afghan policy during much of the past 20 months even as U.S. public support for the war has waned.</p>\n<p>Though it won&rsquo;t be in bookstores until Monday, excerpts published in major newspapers helped generate considerable buzz in Washington and across the blogosphere and could fuel skepticism among lawmakers who control military funding.</p>\n<p>A senior administration official downplayed the internal rifts detailed by Woodward, saying &ldquo;the debates in the book are well known because the policy review process was covered so exhaustively,&rdquo; and defended Obama&rsquo;s handling of the matter.</p>\n<p>&ldquo;The president comes across in the review and throughout the decision-making process as a commander in chief who is analytical, strategic, and decisive, with a broad view of history, national security, and his role,&rdquo; the official said.</p>\n<p>The book comes out five weeks before pivotal congressional elections, but any significant impact appears unlikely since Afghanistan has drawn little attention on the campaign trail with voters mostly focused on economic concerns.</p>\n<p><span> </span></p>\n<p>Still, Woodward &mdash; who has made a career of putting presidents on the hotseat &mdash; paints what is not always a pretty picture of the inner workings of Obama&rsquo;s war council, including a number of scathing personal comments by aides about each other.</p>\n<p>Obama, in excerpts of the book, is shown at odds with top military commanders, particularly Admiral Mike Mullen, the chairman of the Joint Chiefs of Staff, and General David H. Petraeus, during a 2009 policy review when they wanted to add more troops in Afghanistan than he wanted to send.</p>\n<p>Obama, who is said to have pressed military advisers for an exit plan that they never gave him, eventually decided on a 30,000-troop buildup but included a pledge to start drawing down forces in July 2011.</p>\n<p>&ldquo;I can&rsquo;t lose the whole Democratic Party,&rdquo; Obama is quoted as saying. He campaigned for the presidency on a promise to shift the military focus from Iraq to Afghanistan but is also mindful of the political risks of getting bogged down there.</p>\n<p>DOUBTS ON STRATEGY</p>\n<p>Richard Holbrooke, Obama&rsquo;s special envoy for Afghanistan and Pakistan, is quoted saying of the president&rsquo;s strategy, &ldquo;It can&rsquo;t work.&rdquo; Lieutenant General Douglas Lute, the president&rsquo;s White House adviser on Afghanistan, is described as believing that the president&rsquo;s review did not &ldquo;add up&rdquo; to the decision he made.</p>\n<p>Asked about those doubts, the administration official said, &ldquo;Everybody on the president&rsquo;s team signed off on the Afghan strategy, and is focused on implementing it.&rdquo;</p>\n<p>The Pentagon responded cautiously. &ldquo;All I can say at this point is that the department is fully focused on the mission at hand in Afghanistan and implementing the president&rsquo;s strategy there,&rdquo; said Marine Colonel David Lapan, a Pentagon spokesman.</p>\n<p>The White House gave Woodward, an investigative journalist who rose to fame reporting on the Watergate scandal that led to the resignation of President Richard Nixon in 1974, broad access to officials and documents despite the risk of a less-than-glowing assessment in his book.</p>\n<p>The book describes Obama as a &ldquo;professorial president&rdquo; who assigned &ldquo;homework&rdquo; to advisers but bristled at what he saw as military commanders&rsquo; attempts to force his hand.</p>\n<p>Among excerpts reported by Times and Washington Post:</p>\n<p>* Vice President Joe Biden called Holbrooke &ldquo;the most egotistical bastard I&rsquo;ve ever met.&rdquo;</p>\n<p>* A variety of administration officials expressed scorn for Obama&rsquo;s national security adviser, James Jones, while he referred to some of the president&rsquo;s aides as &ldquo;the water bugs.&rdquo;</p>\n<p>* Petraeus, now the Afghanistan commander, told a senior aide he disliked talking with David Axelrod, the president&rsquo;s senior adviser, because he was &ldquo;a complete spin doctor.&rdquo;</p>\n<p>* Defense Secretary Robert Gates worried Jones would be succeeded by his deputy, Thomas Donilon, who he thought would be a &ldquo;disaster.&rdquo;</p>\n<p>The Times said the book also discloses that the Central Intelligence Agency has a 3,000-man &ldquo;covert army&rdquo; in Afghanistan comprised mostly of Afghans who capture and kill Taliban fighters and seek support in tribal areas.</p>\n<p>The Times also said the book discloses the United States has intelligence showing Afghan President Hamid Karzai suffers from manic-depression and is on medication for the disease.</p>\n<p>&nbsp;</p>','Matt Spetalnick, REUTERS',1,'2010-09-23 14:31:03','2010-09-23 14:31:03',3,'','2010-09-23 04:00:00','admin',1);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `db_migration_info`
--

DROP TABLE IF EXISTS `db_migration_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_migration_info` (
  `migration` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `db_migration_info`
--

LOCK TABLES `db_migration_info` WRITE;
/*!40000 ALTER TABLE `db_migration_info` DISABLE KEYS */;
INSERT INTO `db_migration_info` VALUES (4);
/*!40000 ALTER TABLE `db_migration_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_on` datetime NOT NULL,
  `updated_on` datetime NOT NULL,
  `submitter_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `dt_start` datetime NOT NULL,
  `dt_end` datetime NOT NULL,
  `body` text,
  `rating` varchar(255) DEFAULT NULL,
  `category` varchar(128) DEFAULT '',
  `audience` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'2009-03-02 13:53:30','2009-03-02 13:53:30',1,'event, the first','greenroom','2009-03-02 01:50:00','2009-03-02 02:00:00',NULL,NULL,'',NULL),(2,'2009-03-02 14:16:44','2009-03-02 14:16:44',1,'event, the second','greenroom','2009-03-02 02:15:00','2009-03-02 03:50:00',NULL,NULL,'',NULL),(3,'2009-03-02 14:29:13','2009-03-02 14:29:13',1,'Rachel Getting Married','cinema','2009-03-01 13:30:00','2009-03-01 14:30:00',NULL,NULL,'',NULL),(4,'2009-03-02 14:29:54','2009-03-02 14:29:54',1,'The Reader','cinema','2009-03-01 16:00:00','2009-03-01 17:30:00',NULL,NULL,'',NULL),(5,'2009-03-02 14:30:36','2009-03-02 14:30:36',1,'Doubt','cinema','2009-03-01 19:30:00','2009-03-01 21:00:00',NULL,NULL,'',NULL),(6,'2009-03-02 14:31:18','2009-03-02 14:31:18',1,'Doubt','cinema','2009-03-02 19:30:00','2009-03-02 21:00:00',NULL,NULL,'',NULL),(7,'2009-03-02 14:32:00','2009-03-02 14:32:00',1,'Doubt','cinema','2009-03-03 18:30:00','2009-03-03 20:00:00',NULL,NULL,'',NULL),(8,'2009-03-02 14:32:22','2009-03-02 14:32:22',1,'The Reader','cinema','2009-03-03 20:30:00','2009-03-03 22:00:00',NULL,NULL,'',NULL),(9,'2009-03-02 14:33:43','2009-03-02 14:33:43',1,'Doubt','cinema','2009-03-04 14:00:00','2009-03-04 15:30:00',NULL,NULL,'',NULL),(10,'2009-03-02 14:34:09','2009-03-02 14:34:09',1,'The Reader','cinema','2009-03-04 18:30:00','2009-03-04 20:00:00',NULL,NULL,'',NULL),(11,'2009-03-02 14:34:33','2009-03-02 14:34:33',1,'Doubt','cinema','2009-03-04 21:00:00','2009-03-04 22:30:00',NULL,NULL,'',NULL),(12,'2009-03-02 14:42:20','2009-03-02 14:42:20',1,'Doubt','cinema','2009-03-05 19:30:00','2009-03-05 21:00:00',NULL,NULL,'',NULL),(13,'2009-03-02 14:43:28','2009-03-02 14:43:28',1,'Doubt','cinema','2009-03-06 19:00:00','2009-03-06 20:30:00',NULL,NULL,'',NULL),(14,'2009-03-02 14:44:07','2009-03-04 14:53:25',1,'Milk','cinema','2009-03-06 21:15:00','2009-03-06 22:45:00','Using flashbacks from a statement recorded late in life and archival footage for atmosphere, this film traces Harvey Milk\'s career from his 40th birthday to his death. He leaves the closet and New York, opens a camera shop that becomes the salon for San Francisco\'s growing gay community, and organizes gays\' purchasing power to build political alliances. He runs for office with lover Scott Smith as his campaign manager. Victory finally comes on the same day Dan White wins in the city\'s conservative district. The rest of the film sketches Milk\'s relationship with White and the 1978 fight against a statewide initiative to bar gays and their supporters from public school jobs.',NULL,'',NULL),(15,'2009-03-02 14:44:39','2009-03-02 14:44:39',1,'Doubt','cinema','2009-03-07 19:00:00','2009-03-07 20:30:00',NULL,NULL,'',NULL),(16,'2009-03-02 14:45:01','2009-03-04 14:52:52',1,'Milk','cinema','2009-03-07 21:15:00','2009-03-07 23:00:00','',NULL,'',NULL),(17,'2009-03-02 15:48:31','2009-03-02 15:48:31',1,'The Reader','cinema','2009-02-26 18:30:00','2009-02-26 20:00:00',NULL,NULL,'',NULL),(18,'2009-03-02 15:49:00','2009-03-02 15:49:00',1,'Waltz with Bashir','cinema','2009-02-26 21:00:00','2009-02-26 22:30:00',NULL,NULL,'',NULL),(19,'2009-03-02 19:38:48','2009-03-02 19:38:48',1,'Doubt','cinema','2009-03-08 13:30:00','2009-03-08 14:30:00',NULL,NULL,'',NULL),(20,'2009-03-02 19:39:21','2009-03-02 19:39:21',1,'Milk','cinema','2009-03-08 16:00:00','2009-03-08 17:30:00',NULL,NULL,'',NULL),(21,'2009-03-02 19:39:42','2009-03-02 19:39:42',1,'Milk','cinema','2009-03-08 19:30:00','2009-03-08 21:00:00',NULL,NULL,'',NULL),(22,'2009-03-02 19:40:15','2009-03-02 19:40:15',1,'Milk','cinema','2009-03-09 19:30:00','2009-03-09 21:00:00',NULL,NULL,'',NULL),(23,'2009-03-02 19:40:40','2009-03-02 19:40:40',1,'Milk','cinema','2009-03-10 18:30:00','2009-03-10 20:00:00',NULL,NULL,'',NULL),(24,'2009-03-02 19:41:08','2009-03-02 19:41:08',1,'Necessities of Life','cinema','2009-03-10 21:00:00','2009-03-10 22:30:00',NULL,NULL,'',NULL),(25,'2009-03-02 19:41:57','2009-03-02 19:41:57',1,'Necessities of Life','cinema','2009-03-11 19:00:00','2009-03-11 20:30:00',NULL,NULL,'',NULL),(26,'2009-03-02 19:42:18','2009-03-02 19:42:18',1,'Milk','cinema','2009-03-11 21:00:00','2009-03-11 22:30:00',NULL,NULL,'',NULL),(27,'2009-03-02 19:42:42','2009-03-02 19:42:42',1,'Milk','cinema','2009-03-12 19:30:00','2009-03-12 21:00:00',NULL,NULL,'',NULL),(28,'2009-03-02 19:45:54','2009-03-02 19:45:54',1,'Cafe Scientifique','ebar','2009-03-03 19:00:00','2009-03-03 21:00:00',NULL,NULL,'',NULL),(29,'2009-03-02 19:46:44','2009-03-02 19:46:44',1,'Cafe Scientifique','ebar','2009-04-07 19:00:00','2009-04-07 21:00:00',NULL,NULL,'',NULL),(30,'2009-03-04 14:11:19','2009-03-04 14:11:19',1,'Frost/Nixon','cinema','2009-03-13 18:30:00','2009-03-13 20:00:00',NULL,NULL,'',NULL),(31,'2009-03-04 14:15:29','2009-03-04 14:15:29',1,'The Wrestler','cinema','2009-03-13 21:15:00','2009-03-13 23:00:00',NULL,NULL,'',NULL),(32,'2009-09-04 11:39:05','2009-09-04 11:39:05',1,'My Sisters Keeper','cinema','2009-09-01 19:00:00','2009-09-01 20:30:00','',NULL,'',NULL),(33,'2009-09-04 11:44:00','2009-09-04 11:44:00',1,'Taking Woodstock','cinema','2009-09-01 21:15:00','2009-09-01 23:00:00','',NULL,'',NULL),(34,'2009-09-04 11:46:47','2009-09-04 11:46:47',1,'Taking Woodstock','cinema','2009-09-04 19:00:00','2009-09-04 20:30:00','',NULL,'',NULL),(35,'2009-09-04 11:49:06','2009-09-04 11:49:06',1,'Hangover','cinema','2009-09-04 21:30:00','2009-09-04 23:00:00','',NULL,'',NULL),(36,'2009-09-04 12:17:46','2009-09-04 12:17:46',1,'Taking Woodstock','cinema','2009-09-05 19:00:00','2009-09-05 20:30:00','',NULL,'',NULL),(37,'2009-09-04 12:18:20','2009-09-04 12:18:20',1,'Hangover','cinema','2009-09-05 21:30:00','2009-09-05 23:00:00','',NULL,'',NULL),(38,'2009-09-04 12:19:45','2009-09-04 12:19:45',1,'Closed - Labour Day','other','2009-09-07 09:00:00','2009-09-07 23:00:00','',NULL,'',NULL),(39,'2009-09-04 12:20:50','2009-09-04 12:20:50',1,'Taking Woodstock','cinema','2009-09-08 19:00:00','2009-09-08 20:30:00','',NULL,'',NULL),(40,'2009-09-04 12:21:29','2009-09-04 12:21:29',1,'Away We Go','cinema','2009-09-08 21:30:00','2009-09-08 23:00:00','',NULL,'',NULL),(41,'2009-09-04 12:24:20','2009-09-04 12:24:20',1,'Moon','cinema','2009-09-13 21:00:00','2009-09-13 22:30:00','',NULL,'',NULL),(42,'2009-12-15 21:19:45','2009-12-15 21:19:45',1,'eat me','cinema','2009-12-15 09:00:00','2009-12-15 12:00:00','<p>blah</p>',NULL,'music',NULL),(50,'2010-03-16 12:56:00','2010-03-16 13:55:34',1,'The Stone of Destiny','cinema','2010-03-16 14:15:00','2010-03-16 17:30:00','<p>Guaranteed to ignite sparks of nationalist pride, The Stone of Destiny is a humorous telling of events from Scotland\'s recent, and ancient past. The titular stone in question is a three-hundred-pound piece of sandstone known as Scotland\'s Stone of Scone. Taken from Scotland by England\'s Edward I in 1296, the stone sat for centuries in Westminister Abbey, a symbol of England\'s domination. The stone remained an embarrassing reminder of the England\'s centuries-long rule over Scotland until 1950, when an earnest group of students, led by Ian Hamilton, plotted and carried-out a grand heist to remove the stone from the Abbey and return it to its rightful home. &nbsp;Charlie Cox, Kate Mara and Billy Boyd star, with a wee dram of support from Robert Carlyle, Brenda Fricker and Peter Mullen.</p>',NULL,'film','general (all ages)'),(49,'2010-03-11 10:43:20','2010-03-11 11:24:49',1,'Foobar','cinema','2010-03-11 09:00:00','2010-03-11 10:00:00','<p>this is some text</p>\n<p>blah blah blah</p>',NULL,'film','young adult'),(48,'2010-03-11 10:34:03','2010-03-11 12:06:25',1,'Testing','greenroom','2010-03-11 11:00:00','2010-03-11 13:00:00','',NULL,'reading','children'),(51,'2010-03-17 03:51:42','2010-03-17 03:51:42',1,'Sunshine Cleaning','cinema','2010-03-17 04:00:00','2010-03-17 05:00:00','<p>Some critics directed their attention to the grotesque gallows humour and quirky family affectations in Sunshine Cleaning. And yes, the film contains some gag-worthy moments as the sisters, Rose (Amy Adams) and Norah (Emily Blunt) embark on a new career as crime scene and trauma clean-up specialists. And yes, Alan Arkin plays the odd but lovable, curmudgeonly, grandfather, which draws inevitable comparisons to Little Miss Sunshine. And yet, behind this well-scrubbed veneer is a touching story of two sisters who eventually find peace with each other and their pasts after facing the death and grieving of others. The death of a loved one, particularly a parent, brings much sorrow, but as the title implies, with some physical (and emotional) elbow grease, we can re-discover the sunshine.</p>',NULL,'film','general'),(52,'2010-03-17 04:01:38','2010-03-17 04:13:30',1,'The Reader','cinema','2010-03-17 04:00:00','2010-03-17 03:00:00','<p>With an Oscar-worthy performance from Kate Winslett, The Reader warrants an encore presentation. It is the story of a cerebral and steamy love affair between &nbsp;Hanna (Kate Winslet) and Michael (David Kross/Ralph Fiennes). The older (mid-30s) Hanna invites the teenage Michael (Kross) into her bed, where the consumation of physical passion follows Michael\'s reading to Hanna. Ms Winslet is brilliant (as always) as Hanna.</p>',NULL,'film','general (all ages)'),(53,'2010-03-25 10:04:46','2010-03-25 10:04:46',1,'Creation','cinema','2010-03-25 17:45:00','2010-03-25 19:35:00','<p>Jon Amiel\'s film focuses on a brief and emotionally intense period in the years preceding Charles Darwin\'s publication of On The Origin of Species. While assembling his field notes and forming the theories that will become one of the most important scientific books ever written, Darwin\'s 10-year-old daughter Annie dies. Creation places Darwin in the middle of a nasty bit of business, grieving his beloved daughter, trying to appease his wife Emma, a devout churchgoer, and facing his parish neighbours who view him as a heretic. This is a portrait of one man\'s inner struggle. Starring Paul Bettany, Jennifer Connelly, Jeremy Northam and Martha West.</p>',NULL,'film','general'),(54,'2010-03-26 21:27:55','2010-03-26 21:27:55',1,'Cooking with Stella','cinema','2010-04-01 18:45:00','2010-04-01 20:30:00','<p>Cooking with Stella began as a scriptwriting collaboration between director Dilip and his sister, renowned scriptwriter and director Deepa Mehta (Water). It takes the upstairs-downstairs, class-skewering comedy style to India where Maya (Lisa Ray), Canada\'s newly-appointed High Commissioner, and her husband Michael (Don McKellar) have just arrived at their diplomatic residence in New Delhi. Michael, a trained chef, has chosen to stay home with their young daughter, and hopes to improve his knowledge of Indian cuisine during their posting abroad. The cross-cultural comedy steams up as head housekeeper Stella (Seema Biswas), considers the non-traditional roles of these new Westerners and grapples with having a man in her kitchen. In English and Hindi with subtitles.</p>',NULL,'film','general'),(55,'2010-03-26 21:28:55','2010-03-26 21:28:55',1,'Nine','cinema','2010-03-31 18:45:00','2010-03-31 20:45:00','<p>Oscar-winning (for Chicago) director, Rob Marshall updates the 1982 Broadway musical focused on the life and loves of an Italian film director as captured in Fredrico Fellini\'s film 8 1/2. If that sounds slightly confusing, think of it another way: This is one sexy musical, with eye-popping visuals and an amazing cast that features Daniel Day-Lewis, Judi Dench, Penelope Cruz, Marion Cotillard, Fergie, Nicole Kidman, Kate Hudson and Sophia Loren.</p>',NULL,'film','general'),(56,'2010-03-27 07:49:51','2010-03-27 07:49:51',1,'The Horse Boy','cinema','2010-04-10 15:30:00','2010-04-10 17:00:00','<p>This film is an emotionally gripping companion piece to Rupert Isaacson\'s bestselling book. Mr Isaacson is a journalist and former horse trainer living in Texas with his wife, Kristin Neff (a psychology prof.), and their six-year-old son, Rowan, who is autistic. Their story begins with a visit to a nearby ranch where Rowan makes an uncanny connection to his neighbour\'s horse with instantly calming results. As any parent of an autistic child might, Isaacson responds to this event with research, dreaming of a cure or anything that might bridge the gap to his child. Eventually, he finds a shamanistic horse tribe in Upper Mongolia that he believes may possess the power to help his son. Together, the family embarks on the incredible journey that fuels this powerful and unique travelogue. For anyone remotely interested in the study of children with special needs, Autism or the therapeutic power of horses, The Horse Boy is Highly Recommended.</p>',NULL,'film','general'),(57,'2010-03-27 17:48:00','2010-03-27 17:48:00',1,'Creation','cinema','2010-04-11 17:00:00','2010-04-11 18:50:00','<p>Jon Amiel\'s film focuses on a brief and emotionally intense period in the years preceding Charles Darwin\'s publication of On The Origin of Species. While assembling his field notes and forming the theories that will become one of the most important scientific books ever written, Darwin\'s 10-year-old daughter Annie dies. Creation places Darwin in the middle of a nasty bit of business, grieving his beloved daughter, trying to appease his wife Emma, a devout churchgoer, and facing his parish neighbours who view him as a heretic. This is a portrait of one man\'s inner struggle. Starring Paul Bettany, Jennifer Connelly, Jeremy Northam and Martha West.</p>',NULL,'film','general'),(58,'2010-03-27 17:48:35','2010-04-11 14:29:17',1,'Creation','cinema','2010-04-11 19:15:00','2010-04-11 21:15:00','<p>Jon Amiel\'s film focuses on a brief and emotionally intense period in the years preceding Charles Darwin\'s publication of On The Origin of Species. While assembling his field notes and forming the theories that will become one of the most important scientific books ever written, Darwin\'s 10-year-old daughter Annie dies. Creation places Darwin in the middle of a nasty bit of business, grieving his beloved daughter, trying to appease his wife Emma, a devout churchgoer, and facing his parish neighbours who view him as a heretic. This is a portrait of one man\'s inner struggle. Starring Paul Bettany, Jennifer Connelly, Jeremy Northam and Martha West.</p>',NULL,'film','general (all ages)');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `films`
--

DROP TABLE IF EXISTS `films`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `films` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `director` varchar(255) DEFAULT NULL,
  `country` varchar(128) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `running_time` int(11) DEFAULT NULL,
  `description` text,
  `rating` varchar(128) DEFAULT NULL,
  `imdb_link` varchar(128) DEFAULT NULL,
  `ttno` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `films`
--

LOCK TABLES `films` WRITE;
/*!40000 ALTER TABLE `films` DISABLE KEYS */;
INSERT INTO `films` VALUES (1,'Australia','Baz Luhrman','Australia',2008,164,'Baz Luhrman has created a sprawling epic as vast, picturesque and rich in detail as the island nation itself. As usual, Luhrman paints his historical portrait with broad, brightly coloured brushstrokes. Nicole Kidman stars as Lady Sarah Ashley, recently arrived from England; and Hugh Jackman plays Drover, the fully-sculpted embodiment of Down Under manliness. Together, they lead a miles-long cattle drive, befriend a young Aboriginal boy (thus confronting Australia\'s embarrassing history of racial policies), and assist fellow countrymen in coping with the Japanese bombing of Darwin in 1942. Australia is an awesome visual spectacle filled with history, romance and a wind-blown grandeur that begs comparisons to Gone With the Wind.','PG','http://www.imdb.com/title/tt0455824/',NULL),(2,'Religulous','Larry Charles','US',2008,101,'Bill Maher\'s experience and skill as a stand-up comic is brilliantly showcased in this inquiring road trip filmed by Borat..., director Larry Charles. From charismatic Christians speaking in tongues, to gay Muslim activists, to Mormons, Catholics and Jews, Mr Maher finds fertile ground to dig up religious-based comedy. Sure to offend the easily-offended, Religulous offers many knee-slapping moments at the expense of the earnest, the morally superior and the downright delusional practitioners of most of the world\'s various religions.','14A','http://www.imdb.com/title/tt0815241/',NULL),(3,'The Secret Life of Bees','Gina Prince-Bythewood','US',2008,110,'Based on Sue Monk Kidd\'s bestselling novel, The Secret Life of Bees is set in South Carolina during the 1960s. Lily Owens (Dakota Fanning) is a sensitive young girl, still mourning the death of her mother, who flees the family farm and her overbearing father (Paul Bettany). Lily and her caregiver, Rosaleen (Jennifer Hudson) discover the home of the beekeeping Boatwright sisters August (Queen Latifah), May (Sophie Okonedo) and June (Alicia Keys), where they find shelter and tolerance. But their idyllic hive of acceptance is disrupted when Lily\'s father and others of the outside, bigoted world come crashing in. Luckily, the analogy of the beehive withstands the onslaught and the matriarchal clan is able to overcome. The stellar cast truly shines! This film is as heartwarming as a hot cup of tea with a healthy dollop of sweet honey.','14A','http://www.imdb.com/title/tt0416212/',NULL),(4,'Eel Girl','Paul Campion','New Zealand',2008,5,'<p>In a secure military laboratory, a Scientist has become obsessed with the strange half-eel, half-human creature he\'s studying. When she beckons him to her, it\'s the call of a siren...</p>','G','http://www.imdb.com/title/tt1209317/',NULL),(5,'Unagi','Shohei Imamura','Japan',1998,117,'White-collar worker Yamashita finds out that his wife has a lover visiting her when he\'s away, suddenly returns home and kills her... ','','http://www.imdb.com/title/tt0120408/',NULL),(6,'Revolutionary Road','Sam Mendes','US/UK',2008,118,'<p>Based on Richard Yates\' brilliant 1961 novel, Revolutionary Road is set in Connecticut, 1955. The newlywed Wheelers, April (Kate Winslet) and Frank (Leonardo DiCaprio), straddle the divide between the dreams of their youth and the comfortable future that awaits them in post-war, suburban America. It\'s a whiskey-laced tour down a boulevard of (about to be) broken dreams. Kate and Leo provide titanic amounts of emotional magic, this time under the assured hand of director Sam Mendes (director of the Oscar-winning film, American Beauty). As the Globe and Mail\'s Liam Lacy\'s opines, \"Revolutionary Road is a better movie than American Beauty because it doesn\'t rely on such blatant caricatures\"</p>','14A','',NULL),(7,'Act of God','Jennifer Baichwal','Canada',2009,76,'<p>Opening Toronto\'s Hot Docs festival, Act of God is an exploration of the idea of chance. Ms Baichwal chose the highly charged subject of people struck by lightning to enter into a thoughtful exploration of the supposed randomness, yet specific feeling of what is fated when lightning strikes. Her subjects include scientists who strive to measure the physical elements, author Paul Auster who as a teenager was almost electrocuted when a person nearby was struck and killed by lightning, and members of a Mexican town that lost five people to lightning during a Catholic festival. This study of things metaphysical seems to flow organically from Baichwal\'s previous feature, Manufactured Landscapes, which examined the photographic works of Edward Burtynsky.</p>','PG','',NULL),(8,'Adoration','Atom Egoyan','Canada',2009,101,'<p>Mr Egoyan\'s latest film begins in a high school classroom where the teacher (Arsin&eacute;e Khanjian) reads a news article about a terrorist hiding a bomb in his pregnant girlfriend\'s luggage. She asks her students to create works that address the many issues present in the news story. One student, Sami (Noam Jenkins), whose parents are deceased, creates a story that grows out of proportion once it is posted online. In his early films, such as Speaking Parts, Mr Egoyan explored the connection between technology and human communication (then it was the burgeoning video tech) and now the internet provides ample fodder/bandwidth for Mr Egoyan\'s 12th feature.</p>\n<div><br /></div>\n<p>&nbsp;</p>\n<p>&nbsp;</p>','Subject to Classification','',NULL),(9,'Adventureland','Greg Mottola','US',2009,107,'<p>&nbsp;</p>\n<p>Kristen Stewart (Twilight) and Jesse Eisenberg star in this smart, romantic love story set in the Adventureland amusement park during the early 1980s. Without the brash humour of his previous film (Superbad), Mottola balances the subtle discoveries of young love with the outrageous antics that abound during summer jobs at the carnival. Adventureland exposes the scams of carney games, the perils of teenage drinking and eating corndogs and the hurdles along the road from boyhood to manhood, without resorting to crude, gross-out humour. And all that with a pulsing 1980s soundtrack! Bill Hader, Kristen Wiig and Ryan Reynolds standout among the midway-full ensemble. Recommended for its heart, humour and big hair.</p>\n<div><br /></div>\n<p>&nbsp;</p>','14A','',NULL),(10,'Anvil: The True Story of Anvil','Sacha Gervasi','Canada',2009,80,'<p>Anvil is the story of a remarkable friendship, thwarted dreams and the spirit of perserverence. In 1973, teenagers Steve Kudlow and Robb Reiner formed the band Anvil, with the de rigeur dreams of becoming heavy metal superstars. By the early 1980s the band\'s album, Metal on Metal lifted Anvil to the cusp of success and garnered respect and praise from the likes of Guns N\' Roses, Metallica and Motorhead. But the hand of fate pointed to the aforementioned bands and bypassed Anvil altogether. Sasha Gervasi\'s engaging documentary explores the glory days, many years of toiling at \"regular\" jobs and a recent, also ill-fated tour of Eastern Europe. Like a non-fiction version of This is Spinal Tap, Anvil: The True Story of Anvil has its tragicomic moments, but the film\'s heart comes from Robb and Steve\'s resilience and the enduring power of their friendship.</p>','14A','',NULL),(11,'Before Tomorrow','Marie-Helene Cousineau, Madeline Piujuq Ivalu','Canada',2009,93,'<p>Winner of the prize for Best Canadian Film at TIFF \'08, Before Tomorrow is a product of Atanarjuat producers Zacharias Kunuk and Norman Cohn teaming-up with directors Cousineau and Ivalu, who spent years developing a media collective in the far north. As an Inuit grandmother passes on knowledge and love to her grandson through storytelling, the arrival of explorers from Europe is still just a rumour. Before Tomorrow is the story of people at one with their physical surroundings. It succeeds because the directors\' grassroots approach to filmmaking naturally melds the characters\' lives with their landscape. Inuktitut with subtitles.</p>','PG','',NULL),(12,'The Brothers Bloom','Rian Johnson','US',2009,109,'<p>In Rian Johnson\'s follow-up to Brick (his highly-acclaimed feature film debut), we meet the brothers, Stephen (Mark Ruffalo) and Bloom (Adrian Brody). The orphaned brothers were raised by a consumate con man (Maximilian Schell) and as adults they travel the globe, plying their underhanded skills. The nervous Bloom wants to cease their errant ways but the ever-scheming Stephen convinces him to do one last scam, setting their sights on Penelope (Rachel Weisz), an attractive, wealthy and slightly wacky heiress from New Jersey. The elaborate heist sends the trio on a globetrotting trail that leads to romance, deception, lies, the occassional errant explosion, and all the plot twists one expects in a good caper film. Hop on, hang on and enjoy the ride on this zany train.</p>','Subject to Classification','',NULL),(13,'The Celluloid Closet','Robert Epstein, Jeffrey Friedman','US',1996,101,'<p>Presented in collaboration with and in celebration of Pride Week, The Celluloid Closet throws open many of the doors that had previously hidden the lives and loves of various Hollywood stars. Lily Tomlin narrates the film that combines archival footage with extensive interviews to describe the social politics and passions behind the scenes for many in the movie biz. Tony Curtis, Susie Bright, Gore Vidal, Whoopi Goldberg and Armistead Maupin are just a few of the contributors to this film that British critic George Perry called, \"...fascinating, insightful and often funny\".</p>','14A','',NULL),(14,'Duplicity','Tony Gilroy','US',2009,124,'<p>Following his highly-successful directing debut (Michael Clayton), Tony Gilroy joins forces with Julia Roberts and Clive Owen for this breezily entertaining cross, double-cross, triple-cross caper film. Owen plays Ray, a former British agent for MI6 and Roberts is Claire, ex-CIA. They each trade their government spy guises for private sector espionage roles of guarding, or attempting to steal the billion dollar research secrets of competing chemical companies. Alternating between a tense, suspenseful thriller and romantic games of one-upmanship, Duplicity is recommended as two hours of slick and playful (PG for sexy) entertainment.</p>','PG','',NULL),(15,'Gomorrah','Matteo Garrone','IT',2009,137,'<p>Grand Prix Winner at Cannes 2008 and laden with immense praise from international critics, Gomorrah, is based on Roberto Saviano\'s bestselling book about organized crime in Naples. There, the local mafia is known as the Camorra, and the film\'s title slyly plays with a Biblical reference. Unlike Francis Ford Coppola\'s highly-romantized Godfather Trilogy of the 1970s, Gomorrah presents a bald, unsparing vision of a society run by petty thieves and heavily-armed thugs, who make violence and threats their currency. This creates a sense of fear and reprisal that permeates the lives of everyone. This is a harsh but compelling portrait that would make anyone think twice (or more) about joining their ranks. Italian with subtitles.</p>','14A','http://www.imdb.com/title/tt0929425/','tt0929425'),(16,'Hunger','Steve McQueen','UK',2009,96,'<p>British visual artist Steve McQueen makes a powerful and disturbingly bold film debut with Hunger, a raw and painfully brutal portrait of people and events in the Maze Prison, outside of Belfast in 1981. The troubles in Northern Ireland had reached a fevered and bloody pitch where prison employees inspected their cars before driving to and from work and the IRA inmates refused prison uniforms, demanding to be treated as political prisoners, not convicts. The prison\'s H-Block was notorious for its squalid conditions and and the vicious treatment of the inmates. H-Block leader Bobby Sands and fellow prisoners also refused to bathe or eat in protest of Britain\'s involvement in Ireland and their own treatment in prison. The hunger strike received international attention, exposing the Thatcher Government\'s complicity in barbarous torture, but also cost Bobby Sands his life. Michael Fassbender is unforgettable in his all-consuming portrait of the martyred Bobby Sands. Mr McQueen\'s skills as a visual artist imbue Hunger with such emotional intensity and disturbing images that many casual filmgoers should take note.</p>','18A','',NULL),(17,'The International','Tom Tykwer','US/DE/UK',2009,118,'<p>Tom (Run, Lola, Run) Twyker\'s latest film stars Clive Owen and Naomi Watts, who play a pair of British and American sleuths investigating the questionable business practices of a large international bank. Inspired by a Pakistan-based institution that was shutdown in the early 1990s for funding illegal arms and drug dealers and backing international terrorist organizations, The International presents these slick-suited bankers as nefarious villians. Tykwer takes us on a whirlwind tour of exotic urban locales such as, Berlin, Milan, Istanbul, Lyon and eventually to Manhattan for the film\'s climactic finale within the spiral design of the Guggenheim Museum. A scene &nbsp;Roger Ebert suggests, \"that even Hitchcock might have envied\".</p>','14A','',NULL),(18,'Last Chance Harvey','Joel Hopkins','US/UK',2009,92,'<p>Harvey (Dustin Hoffman) briefly meets Kate (Emma Thompson) at London\'s Heathrow Airport enroute to his daughter\'s wedding. Having been a longtime absentee dad, Harvey finds the wedding plans somewhat difficult, his daughter, Susan (New Waterford Girl\'s Liane Balaban) wants her mother\'s new husband to give her away. Harvey is soon back at Heathrow, throwing back scotches when Kate, and fate, charitably intercedes. This is a romantic comedy about middle-age, and last (or at least second) chances. Back by popular demand!</p>','PG','',NULL),(19,'Lemon Tree','Etz Limon, Eran Riklis','Israel/DE/FR',2009,106,'<p>Lemon Tree is a sensitive, dramatic allegory of the ongoing Israeli-Palestinian strife. Hiam Abbass (most recently in The Visitor) plays Salma, a widow who lives alone, tending the small lemon grove that has for decades provided her family with a modest income. When the Israeli Minister of Defense moves into the opulent house neighbouring on Salma\'s land, the lemon trees are deemed a security risk and must be destroyed. Her livelihood threatened and with no recourse, Salma takes the issue to the Israeli Supreme Court. While the ensuing legal proceedings drag on, the Israelis surround the grove with a security fence and prohibit Salma from tending the trees. As the trees wither, the Defense Minister\'s wife, Mira, gradually warms to Salma\'s plight and a silent but compassionate empathy develops. Lemon Tree is a quiet but powerful portrait of those living in the shadow of military might. Recommended. Hebrew and Arabic with subtitles.</p>','PG','',NULL),(20,'Mamma Mia!','Phyllida Lloyd','US',2008,108,'<p>The hugely successful musical based on the the hit songs of ABBA is a big screen event! In the Greek Isles, a beautiful young couple prepares to marry, while Meryl Streep, Julie Walters, Christine Baranski, Pierce Brosnan, Stellan Skarsgard and Colin Firth drink wine and remember their youth. What could be more fun? Just one screening for Mother\'s Day. Come just for the movie or do Brunch &amp; a Movie! Call for reservations: 519-821-3311 x-126.</p>','PG','',NULL),(21,'The Necessities of Life, (Ce Qu\'il Faut Pour Vivre)','Benoit Pilon','Canada',2008,102,'<p>A tuberculosis epidemic swept across Canada\'s north during the 1940s and 1950s, and the film opens with scenes of local natives being ushered onto a boat for medical testing. &nbsp;Tivii (The Fast Runner\'s Natar Ungalaaq) receives the news that he has tested positive for TB. With the threat of infecting others in his village, Tivii reluctantly accepts orders to be shipped south for treatment. He arrives at a sanatorium in Quebec City, surrounded by trees and buildings that bear no points of reference for the Arctic hunter and he shares no common language. Eventually Tivii meets a young Native boy, who is able to translate and he takes a fatherly role, telling stories and sharing his carving skills. Recommended for it\'s smart and sensitive treatment of a difficult period in Canadian history, Necessities of Life justifiably won four Canadian Film Awards (Genies), including Best Director, Actor and Screenplay. French and Inuktitut with subtitles.</p>','PG','',NULL),(22,'One Week','Michael McGowan','Canada',2008,94,'<p>Blindsided by the news that he is terminally ill, Ben Tyler (Joshua Jackson) faces off with himself in the looking glass: he\'s a 30-something teacher playing it safe, about to marry his complacent sweetheart Samantha (Last Chance Harvey\'s Liane Balaban), and unsatisfied with his routines. Mortality is the kick in the pants that catalyzes Ben\'s awakening, as he hops on a vintage motorcycle and takes to the Trans-Canada highway, looking for adventure and peace. With Samantha urging him to return to treatment, Ben meets a series of road prophets (played by a who\'s who of talent from the Canadian music scene), and makes a pilgrimage to the Great White North\'s roadside attractions. A lovely second effort from the director of 2004\'s Saint Ralph, One Week provides ample material for the deep thinkers and the tourists among us.</p>','PG','',NULL),(23,'Pontypool','Bruce McDonald','Canada',2009,95,'<p>Maverick director Bruce McDonald\'s (Hard Core Logo) latest feature is a bare-bones take on the zombie movie. Stephen McHattie is great as the gravel-throated Grant Mazzy, a talk radio host operating out of a church basement studio in Pontypool, ON. Grant and his producer Sydney (Lisa Houle) receive reports of people running naked through the streets, missing body parts and an overall sense of mounting violence. Secure, but trapped in their isolated studio, Mazzy and Sydney try to decipher the strange events, but without any visual contact to the outside world they are left only to speculate about possible causes&acirc;&euro;&brvbar;. Back by popular demand!</p>','14A','',NULL),(24,'The Pool','Chris Smith','US/India',2009,98,'<p>An American director from Milwaukee takes a story by an Iowan writer and sets the film in the Panjim region of India, resulting in one of the best reviewed films of 2009. The Pool is a simple but complex story of Venkatesh, a teenager, who along with his younger buddy, Jhangir, discover and fall under the spell of a swimming pool. From his treetop perch Venkatesh stares in awe, amazed that someone could possess such a wonderful luxury. He approaches the pool\'s owner (Indian star, Nana Patekar) for a job, and the poor boy and rich man quickly bond. The Pool is a tranquil, contempletive character study that may also appeal to fans of Richie Mehta\'s film, Amal, for its meditative look at wealth, as measured through the eyes of the rich and the poor. English and Hindi with subtitles.</p>','PG','',NULL),(25,'The Reader','Stephen Daldry','US/Germany',2008,122,'<p>With an Oscar-worthy performance from Kate Winslett, The Reader warrants an encore presentation. It is the story of a cerebral and steamy love affair between &nbsp;Hanna (Kate Winslet) and Michael (David Kross/Ralph Fiennes). The older (mid-30s) Hanna invites the teenage Michael (Kross) into her bed, where the consumation of physical passion follows Michael\'s reading to Hanna. Ms Winslet is brilliant (as always) as Hanna.</p>','14A','',NULL),(26,'The Soloist','Joe Wright','UK/US/France',2009,116,'<p>Based on real events, The Soloist is the story of LA Times writer Steve Lopez (Robert Downey Jr), who in 2005, encountered Nathaniel Ayers living on the streets of Los Angeles. Mr Lopez writes an article about Mr Ayers&rsquo;s musical abilities, which leads to further writings, research and the discovery that Nathaniel was a gifted musical prodigy, studying at Juilliard before mental illness changed his life. Mr Lopez gradually establishs a connection with the troubled man and the film tells the story of their struggles with mental illness, missed opportunities, homelessness and the deep, but uncertain friendship that is developed. Director Wright moves away from the historical period pieces (Atonement, Pride and Predujice) for this tale of contempoary urban issues, complimented by a classical music-themed soundtrack by Mario Marianelli. The Soloist is recommended for bringing issues of mental illness and homelessness to the fore through the mainstream medium of movies.</p>','PG','',NULL),(27,'State of Play','Kevin Macdonald','US/UK',2009,126,'<p>In the age of instant news reporting on the web and ubiquitous blog commentaries, State of Play represents a welcome throwback to a time when reporters acted as investigators and print deadlines loomed like guillotine papercutters. Russell Crowe and Rachel McAdams star as reporters for a Washington daily who doggedly pursue a story of government corruption that leads to politicians in very high places. Director Macdonald\'s top-notch cast also features Helen Mirren, Ben Affleck, Robin Wright Penn, Jason Bateman and Jeff Daniels. Recommended for those impressed by the film\'s favourable comparisons to films like Three Days of the Condor and All The President\'s Men.</p>','PG','',NULL),(28,'The Stone of Destiny','Charles Martin Smith','Canada/UK',2009,96,'<p>Guaranteed to ignite sparks of nationalist pride, The Stone of Destiny is a humorous telling of events from Scotland\'s recent, and ancient past. The titular stone in question is a three-hundred-pound piece of sandstone known as Scotland\'s Stone of Scone. Taken from Scotland by England\'s Edward I in 1296, the stone sat for centuries in Westminister Abbey, a symbol of England\'s domination. The stone remained an embarrassing reminder of the England\'s centuries-long rule over Scotland until 1950, when an earnest group of students, led by Ian Hamilton, plotted and carried-out a grand heist to remove the stone from the Abbey and return it to its rightful home. &nbsp;Charlie Cox, Kate Mara and Billy Boyd star, with a wee dram of support from Robert Carlyle, Brenda Fricker and Peter Mullen.</p>','PG','',NULL),(29,'Sunshine Cleaning','Christine Jeffs','US',2009,92,'<p>Some critics directed their attention to the grotesque gallows humour and quirky family affectations in Sunshine Cleaning. And yes, the film contains some gag-worthy moments as the sisters, Rose (Amy Adams) and Norah (Emily Blunt) embark on a new career as crime scene and trauma clean-up specialists. And yes, Alan Arkin plays the odd but lovable, curmudgeonly, grandfather, which draws inevitable comparisons to Little Miss Sunshine. And yet, behind this well-scrubbed veneer is a touching story of two sisters who eventually find peace with each other and their pasts after facing the death and grieving of others. The death of a loved one, particularly a parent, brings much sorrow, but as the title implies, with some physical (and emotional) elbow grease, we can re-discover the sunshine.</p>','14A','',NULL),(30,'Tulpan','Sergey Dvortsevoy','Kazakhstan',2009,100,'<p>In his rave, four star review, the Globe and Mail\'s Liam Lacy writes, \"Tulpan is ethnographic filmmaking without the preaching. On the surface, it\'s an absurd domestic comedy about a big-eared shepherd, looking for love and his dream of life on the open range. Bubbling underneath is an intimate portrait of a receding way of life, and the relationship between land, people and animals in a remote part of central Asia.\" The film\'s pace resembles that of life on the steppes and leisurely doles out its nuggets, rewarding the patient filmgoer. Kazakh and Russian with subtitles.</p>','PG','',NULL),(31,'Two Lovers','James Gray','US',2009,101,'<p>Before his unfortunate and awkwardly unsettling appearance on the Letterman Show Joaquin Phoenix completed work on his third film with director James Gray (the previous films being, The Yards and We Own the Night). Mr Phoenix stars as Leonard Kraditor, a senitive but troubled loner, in his early 30s, who lives with his parents in the Bronx. Leonard\'s mother (Isabella Rossellini) invites Sandra (Vinessa Shaw), the daughter of a family friend, to visit, hoping that Leonard may take interest. Marriage would be a good thing for both families. And while Sandra presents quite an attractive demeanor, Leonard finds himself strangely drawn to the alluring and somewhat vulnerable fair-haired neighbour, Michelle (Gwyneth Paltrow). Mr Gray visits his recurring theme of the individual, torn between family commitments and personal aspirations. Among the many rave reviews was the San Francisco Chronicle\'s Mick La Salle, who praised the film\'s \"European feel for capturing character and emotion with an American film sense of pace and story development... \"the best of both worlds. Two Lovers already stands out as one of 2009\'s little gems.</p>','14A','',NULL),(32,'Creation','Jon Amiel','UK',2009,108,'<p>Jon Amiel\'s film focuses on a brief and emotionally intense period in the years preceding Charles Darwin\'s publication of On The Origin of Species. While assembling his field notes and forming the theories that will become one of the most important scientific books ever written, Darwin\'s 10-year-old daughter Annie dies. Creation places Darwin in the middle of a nasty bit of business, grieving his beloved daughter, trying to appease his wife Emma, a devout churchgoer, and facing his parish neighbours who view him as a heretic. This is a portrait of one man\'s inner struggle. Starring Paul Bettany, Jennifer Connelly, Jeremy Northam and Martha West.</p>','PG','http://www.imdb.com/title/tt0974014/','tt0974014'),(33,'Up in the Air','Jason Reitman','USA',2009,109,'<p>Best Actor Oscar nominee George Clooney is the face of this film. But more than just a pretty face, he smoothly delivers one fine dramatic performance with fellow Oscar nominees (both for Best Supporting Actress) Vera Farmiga and Anna Kendrick. Also nominated for Best Picture, Best Director and Best Adapted Screenplay, Up in the Air is a wryly observant drama, with dry, humourous moments about characters trying to find balances between work and life, family and strangers, and longing or belonging. There are also subplots about the pain of corporate downsizing and pursuit of travel reward points. Great stuff. Recommended.</p>','14A','',''),(34,'Cooking with Stella','Dilip Mehta','Canada',2010,103,'<p>Cooking with Stella began as a scriptwriting collaboration between director Dilip and his sister, renowned scriptwriter and director Deepa Mehta (Water). It takes the upstairs-downstairs, class-skewering comedy style to India where Maya (Lisa Ray), Canada\'s newly-appointed High Commissioner, and her husband Michael (Don McKellar) have just arrived at their diplomatic residence in New Delhi. Michael, a trained chef, has chosen to stay home with their young daughter, and hopes to improve his knowledge of Indian cuisine during their posting abroad. The cross-cultural comedy steams up as head housekeeper Stella (Seema Biswas), considers the non-traditional roles of these new Westerners and grapples with having a man in her kitchen. In English and Hindi with subtitles.</p>','PG','',''),(35,'Lovely Bones','Peter Jackson','USA/UK/NZ',2009,135,'<p>Alice Sebold\'s bestselling (and Oprah picked) novel is the story of Susie Salmon, an innocent 14-year-old, who narrates the story after she has been murdered. Much of the book\'s success was due to Ms Sebold giving voice to the young victim, caught between the loved ones of her past and the sweet hereafter ahead. Mr Jackson (Lord of the Rings) taps into the style of his early work, like Heavenly Creatures, to blend Susie\'s everyday life (circa 1973) with her fantastical visions of being caught between Heaven and Earth. Young Saoirse Ronan (Atonement) stars as Susie, with strong support from Mark Wahlberg, Rachel Weisz, Stanley Tucci and Susan Sarandon.</p>','PG','',''),(36,'Nine','Rob Marshall','USA/Italy',2009,118,'<p>Oscar-winning (for Chicago) director, Rob Marshall updates the 1982 Broadway musical focused on the life and loves of an Italian film director as captured in Fredrico Fellini\'s film 8 1/2. If that sounds slightly confusing, think of it another way: This is one sexy musical, with eye-popping visuals and an amazing cast that features Daniel Day-Lewis, Judi Dench, Penelope Cruz, Marion Cotillard, Fergie, Nicole Kidman, Kate Hudson and Sophia Loren.</p>','PG','',''),(37,'Aliens','James Cameron','USA/UK',1986,137,'<p>57 years after her ordeal with an extraterrestrial creature, Ellen  Ripley is rescued by a deep salvage team during her hypersleep. When she  discovers that transmissions from a colony that has since settled on  the alien planet suddenly stop, Ripley is offered a chance to team up  with a group of marines to descend on the planet and investigate the  alien presence. Determined to end the memories of the alien creature,  Ripley agrees to the offer and is once again thrown back into her living  nightmare.</p>','R','http://www.imdb.com/title/tt0090605/','tt0090605'),(38,'The White Ribbon','Michael Haneke','Germany/Austria/France/Italy',2009,144,'<p>Michael Haneke\'s White Ribbon does not wrap into a pretty bow, but  rather tightens around a small German village in 1914, whose children  will grow into the adults of Nazi Germany. Shot in black and white, like  the films of the era, The White Ribbon gradually exposes a bewildering  array of tragic and often unexplained events. Odd deaths, bizarre  accidents and unusual behaviours occur under an ever-increasing cloud of  dread. Mr Haneke is not one to offer easy narratives or neat answers.  The White Ribbon has received universal critical acclaim, three European  Film Awards (Best Picture, Director and Screenplay) a Golden Globe and  Oscar nomination for Best Foreign Film. Mr Haneke\'s dense, harsh,  (beautifully) stark and protracted portrait is essential viewing for  serious cinephiles. Recommended. German with subtitles.</p>','R','http://www.imdb.com/title/tt1149362/','tt1149362'),(40,'The Young Victoria','Jean-Marc Vallee','UK/Canada',2009,105,'<p>Jean-Marc Vallee\'s (C.R.A.Z.Y.) latest film is an unapologetically romantic portrait of the young monarch in the years leading up to her coronation. She is surrounded by schemers and self-interested parties, including her mother, the Duchess of Kent (Miranda Richardson), and Lord Melbourne (Paul Bettany). But as the young Victoria\'s romance for Prince Albert grows, she gains the self-confidence to take charge and step into the role of ruler. The film belongs to Emily Blunt, who deftly strides into the leading lady role as the young Queen-to-be. The Young Victoria is Highly Recommended.</p>','PG','',''),(41,'The Horse Boy','Michael Orion, Rupert Isaacson','USA',2009,93,'<p>This film is an emotionally gripping companion piece to Rupert Isaacson\'s bestselling book. Mr Isaacson is a journalist and former horse trainer living in Texas with his wife, Kristin Neff (a psychology prof.), and their six-year-old son, Rowan, who is autistic. Their story begins with a visit to a nearby ranch where Rowan makes an uncanny connection to his neighbour\'s horse with instantly calming results. As any parent of an autistic child might, Isaacson responds to this event with research, dreaming of a cure or anything that might bridge the gap to his child. Eventually, he finds a shamanistic horse tribe in Upper Mongolia that he believes may possess the power to help his son. Together, the family embarks on the incredible journey that fuels this powerful and unique travelogue. For anyone remotely interested in the study of children with special needs, Autism or the therapeutic power of horses, The Horse Boy is Highly Recommended.</p>','PG','','');
/*!40000 ALTER TABLE `films` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `type` char(5) NOT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT NULL,
  `user` varchar(50) NOT NULL,
  `caption` varchar(250) DEFAULT NULL,
  `description` text,
  `license` varchar(50) DEFAULT NULL,
  `thumbnail` varchar(250) DEFAULT NULL,
  `tt_isbn` char(13) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid` (`uuid`)
) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (28,'190f4074-c4b3-9eb2-21e2-939d5c8b7584','top_feature.jpg','.jpg','2009-12-10 14:19:13','2009-12-10 14:19:13','admin','FoC banner image','','unknown',NULL,NULL),(29,'407bdc82-ed0d-0cd0-4f49-872130a771d9','featured_right.jpg','.jpg','2009-12-10 14:22:09','2009-12-10 14:22:09','admin','random','','unknown',NULL,''),(26,'fa2b3c8d-e747-c7bf-8563-9e4a65795a23','featured_left.jpg','.jpg','2009-12-09 17:36:38','2009-12-09 17:36:38','admin','Left Feature','','unknown',NULL,NULL),(27,'f3fd4750-af0f-3f31-aeaf-73658836d23e','featured_middle.jpg','.jpg','2009-12-09 17:49:38','2009-12-09 17:49:38','admin','Middle Feature','','unknown',NULL,NULL),(12,'1bc3d903-4937-269d-ac1d-9b1e88ded4ce','http://www.youtube.com/v/_sccg1CZzi4','link','2009-11-25 02:18:21','2009-11-25 02:18:21','admin','Samurai Champloo - Shiki No Uta','The singer is Minmi, the beat is produced by Nujabes. The title means \"Song of the seasons\"\n','unknown',NULL,NULL),(19,'242055ac-a720-8ab1-0a6e-c35ac40a0825','http://www.vimeo.com/7945183','link','2009-12-03 09:17:02','2009-12-03 09:17:02','admin','Small Life in Spain by: Christoph Schaarschmidt','','unknown','http://ts.vimeo.com.s3.amazonaws.com/357/473/35747370_200.jpg',NULL),(20,'b4fde205-2985-78bf-69f3-434438c58f90','UnderConstruction.jpg','.jpg','2009-12-03 09:36:13','2009-12-03 09:36:13','admin','foo','','unknown',NULL,NULL),(21,'9911a77b-8c0a-244c-4ddc-9f83afc832e9','darkknight_feature.jpg','.jpg','2009-12-09 15:27:05','2009-12-09 15:27:05','admin','Dark Knight','','unknown',NULL,NULL),(22,'11da9997-e400-2376-e2e1-50fd5923a486','movies.jpg','.jpg','2009-12-09 15:27:46','2009-12-09 15:27:46','admin','Movies Blurb','','unknown',NULL,NULL),(23,'065ae3ff-66de-2560-3e93-6dd3c1b795c8','slumdog_feature.jpg','.jpg','2009-12-09 15:28:09','2009-12-09 15:28:09','admin','Slumdog Feature','','unknown',NULL,NULL),(24,'8a1198a7-a2cf-4561-7a62-764aee4c76fc','startrek_feature.jpg','.jpg','2009-12-09 15:28:30','2009-12-09 15:28:30','admin','Star Trek Feature','','unknown',NULL,NULL),(25,'e97f713d-a8ce-d07f-1784-defb693309ed','wrestler_feature.jpg','.jpg','2009-12-09 15:28:49','2009-12-09 15:28:49','admin','Wrestler Feature','','unknown',NULL,NULL),(30,'4d6153f9-10b3-3a7f-1b50-2b3de1cc6de7','startrek_feature.jpg','.jpg','2009-12-10 14:23:52','2009-12-10 14:23:52','admin','','','unknown',NULL,NULL),(44,'9fdae11b-f042-a10e-b1c6-d63c2a85641e','http://www.youtube.com/watch?v=yjXY9kl2ljQ','link','2010-03-25 19:19:43','2010-03-25 19:19:43','admin','Nicholas Ruddock event','This is a little clip from Nicholas Ruddocks launch of his debut novel \"The Parabolist\". It was a great event! Check it out.  ','unknown',NULL,NULL),(53,'1408d00f-33e5-2050-39ac-5be189c876df','1408d00f-33e5-2050-39ac-5be189c876df.jpg','.jpg','2010-03-26 17:06:50','2010-03-26 17:06:50','admin','The White Ribbon','','unknown',NULL,NULL),(52,'7c4a6d87-0c8b-10e6-2b96-08013a16b84f','7c4a6d87-0c8b-10e6-2b96-08013a16b84f.jpg','.jpg','2010-03-26 16:12:47','2010-03-26 16:12:47','admin','Creation','','unknown',NULL,NULL),(54,'c4cad5fc-982b-10a9-e3ed-2f7531ae9b36','c4cad5fc-982b-10a9-e3ed-2f7531ae9b36.jpg','.jpg','2010-03-26 17:11:37','2010-03-26 17:11:37','admin','Young Victoria','','unknown',NULL,NULL),(55,'5f534ff0-cab5-1bce-82a4-4e7629f12de8','5f534ff0-cab5-1bce-82a4-4e7629f12de8.jpg','.jpg','2010-03-29 13:18:32','2010-03-29 13:18:32','admin',NULL,NULL,NULL,NULL,NULL),(57,'3f68d125-63a3-c34b-f7c2-ad463ebc6a50','3f68d125-63a3-c34b-f7c2-ad463ebc6a50.jpg','.jpg','2010-03-29 13:19:02','2010-03-29 13:19:02','admin',NULL,NULL,NULL,NULL,NULL),(58,'9fe2e992-e13b-e780-a37c-c4a5d2f19dac','http://storage.canoe.ca/v1/dynamic_resize/?src=http://cnews.canoe.ca/CNEWS/World/2010/09/22/barack_rift.jpg&size=256x600&quality=85','link','2010-09-23 14:33:56','2010-09-23 14:33:56','admin',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_map`
--

DROP TABLE IF EXISTS `media_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) DEFAULT NULL,
  `path` varchar(256) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `slot` varchar(128) NOT NULL DEFAULT 'general',
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_map`
--

LOCK TABLES `media_map` WRITE;
/*!40000 ALTER TABLE `media_map` DISABLE KEYS */;
INSERT INTO `media_map` VALUES (1,8,'/articles/1',0,'general',''),(94,20,'/pages/1',3,'general',''),(15,24,'/pages/1',4,'general',''),(39,23,'/pages/1',6,'general',''),(27,40,'/articles/1',1,'general',''),(22,22,'/pages/1',5,'general',''),(17,26,'/articles/1',1,'general',''),(38,28,'/pages/1',1,'top',''),(42,27,'/event/50',1,'general',''),(20,27,'/pages/1',1,'mid',''),(21,29,'/pages/1',1,'right',''),(40,25,'/pages/1',7,'general',''),(76,52,'/films/32',1,'general',''),(44,27,'/event/51',1,'general',''),(85,23,'/articles/2',1,'general',''),(83,57,'/event/54',1,'general',''),(79,52,'/event/58',0,'general',''),(95,23,'/pages/1',1,'left',''),(93,52,'/event/56',1,'general',''),(77,53,'/films/38',1,'general',''),(78,54,'/films/40',1,'general','');
/*!40000 ALTER TABLE `media_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_older`
--

DROP TABLE IF EXISTS `media_older`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_older` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filepath` varchar(1024) NOT NULL,
  `user` varchar(256) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `section` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_older`
--

LOCK TABLES `media_older` WRITE;
/*!40000 ALTER TABLE `media_older` DISABLE KEYS */;
INSERT INTO `media_older` VALUES (2,'startrek_feature.jpg','admin','2009-05-31 03:17:49','2009-05-31 03:17:49','front_page'),(10,'wrestler_feature.jpg','admin','2009-05-31 06:38:52','2009-05-31 06:38:52','front_page'),(4,'darkknight_feature.jpg','admin','2009-05-31 03:18:29','2009-05-31 03:18:29','front_page'),(5,'slumdog_feature.jpg','admin','2009-05-31 03:18:48','2009-05-31 03:18:48','front_page'),(6,'top_feature.jpg','admin','2009-05-31 03:54:29','2009-05-31 03:54:29','top_feature'),(7,'featured_left.jpg','admin','2009-05-31 03:54:53','2009-05-31 03:54:53','left_feature'),(8,'featured_right.jpg','admin','2009-05-31 03:55:06','2009-05-31 03:55:06','right_feature'),(9,'featured_middle.jpg','admin','2009-05-31 03:56:40','2009-05-31 03:56:40','mid_feature'),(12,'no_image.jpg','admin','2009-06-02 06:34:03','2009-06-02 06:34:03','library'),(11,'movies.jpg','admin','2009-05-31 07:46:50','2009-05-31 07:46:50','front_page'),(13,'me_guitar.jpg','admin','2009-11-24 16:06:35','2009-11-24 16:06:35','library');
/*!40000 ALTER TABLE `media_older` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_tag_map`
--

DROP TABLE IF EXISTS `media_tag_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_tag_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `media_id` int(11) NOT NULL,
  `media_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_tag_map`
--

LOCK TABLES `media_tag_map` WRITE;
/*!40000 ALTER TABLE `media_tag_map` DISABLE KEYS */;
INSERT INTO `media_tag_map` VALUES (78,12,11),(84,9,12),(71,10,10),(76,8,5),(75,8,7),(74,8,9),(79,12,7),(80,12,9),(83,9,6),(86,13,13),(87,19,13),(88,20,13),(89,21,13),(90,22,13),(103,23,13),(92,24,13),(93,25,13),(94,26,13),(95,27,13),(101,28,13),(97,30,13),(98,36,13),(100,44,13),(102,49,13),(107,53,14),(105,52,13),(106,54,13),(112,29,13);
/*!40000 ALTER TABLE `media_tag_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_tags`
--

DROP TABLE IF EXISTS `media_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_tag_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_tags`
--

LOCK TABLES `media_tags` WRITE;
/*!40000 ALTER TABLE `media_tags` DISABLE KEYS */;
INSERT INTO `media_tags` VALUES (1,'foo','foo'),(2,'bar','bar'),(12,'stain','stain'),(5,'groovy','groovy'),(6,'awesome','awesome'),(7,'cool','cool'),(8,'zooey','zooey'),(9,'blue','blue'),(10,'hair','hair'),(11,'champloo','champloo'),(13,'','n-a'),(14,'film','film');
/*!40000 ALTER TABLE `media_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_blacklist`
--

DROP TABLE IF EXISTS `ml_blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_blacklist`
--

LOCK TABLES `ml_blacklist` WRITE;
/*!40000 ALTER TABLE `ml_blacklist` DISABLE KEYS */;
INSERT INTO `ml_blacklist` VALUES (6,'jbp@kw.igs.net'),(7,'jim@j2mfk.com');
/*!40000 ALTER TABLE `ml_blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_config`
--

DROP TABLE IF EXISTS `ml_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_config` (
  `item` varchar(35) NOT NULL,
  `value` longtext,
  `editable` tinyint(4) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `help` longtext,
  PRIMARY KEY (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_config`
--

LOCK TABLES `ml_config` WRITE;
/*!40000 ALTER TABLE `ml_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `ml_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_list`
--

DROP TABLE IF EXISTS `ml_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `descrip` text,
  `is_visible` tinyint(4) NOT NULL DEFAULT '1',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  `is_open` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_list`
--

LOCK TABLES `ml_list` WRITE;
/*!40000 ALTER TABLE `ml_list` DISABLE KEYS */;
INSERT INTO `ml_list` VALUES (1,'General News','General news, announcements and special offers',1,1,1),(2,'Book Stuff','Info about books and stuff',1,1,1),(3,'Cinema','List for cinema related announcements',1,1,1),(4,'Test List','A list for testing.',1,1,1);
/*!40000 ALTER TABLE `ml_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_messages`
--

DROP TABLE IF EXISTS `ml_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT 'no subject',
  `from` varchar(255) NOT NULL DEFAULT 'mailman',
  `text_fmt` text,
  `text_plain` text,
  `text_footer` text,
  `ml_list_id` int(11) NOT NULL DEFAULT '0',
  `send_on` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_messages`
--

LOCK TABLES `ml_messages` WRITE;
/*!40000 ALTER TABLE `ml_messages` DISABLE KEYS */;
INSERT INTO `ml_messages` VALUES (1,'Test message','me@someplace.com','<p>This is <strong>formatted</strong>... not!</p>','This is the plain version, it is',NULL,1,'0000-00-00 00:00:00',0),(2,'Yo','me@here.com','<p>\n<p>Dear {{NAME}}</p>\n<p>Thanks for you interest.</p>\n<p>love</p>\n<p>US!</p>\n</p>','',NULL,2,'0000-00-00 00:00:00',0),(3,'Guess what','','<p>Hey here we are</p>','',NULL,1,'2010-04-02 02:00:00',NULL);
/*!40000 ALTER TABLE `ml_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_subscr`
--

DROP TABLE IF EXISTS `ml_subscr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_subscr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(256) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `pref_format` char(5) DEFAULT 'HTML',
  `status` char(10) DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_subscr`
--

LOCK TABLES `ml_subscr` WRITE;
/*!40000 ALTER TABLE `ml_subscr` DISABLE KEYS */;
INSERT INTO `ml_subscr` VALUES (1,'J. Knight','jim@barkingdogstudios.com','HTML','active'),(2,'Mr. Me Again','me@someplace.com','HTML','banned'),(3,'Jim Knight\n','jim@talonedge.com','HTML','active'),(4,'MT Vee\n','emptyvee@gmail.com','HTML','active'),(5,'Joe Blow','joe@blow.com','HTML','active'),(98,NULL,'admin','HTML','active'),(97,NULL,'jim@j2mfk.com','HTML','active');
/*!40000 ALTER TABLE `ml_subscr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_subscr_list_map`
--

DROP TABLE IF EXISTS `ml_subscr_list_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_subscr_list_map` (
  `subscr_id` int(11) NOT NULL,
  `list_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_subscr_list_map`
--

LOCK TABLES `ml_subscr_list_map` WRITE;
/*!40000 ALTER TABLE `ml_subscr_list_map` DISABLE KEYS */;
INSERT INTO `ml_subscr_list_map` VALUES (2,2),(2,1),(3,3),(4,3),(5,3),(3,2),(4,2),(5,2),(97,1),(98,1),(98,2);
/*!40000 ALTER TABLE `ml_subscr_list_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ml_templates`
--

DROP TABLE IF EXISTS `ml_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ml_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tmpl_text` text,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ml_templates`
--

LOCK TABLES `ml_templates` WRITE;
/*!40000 ALTER TABLE `ml_templates` DISABLE KEYS */;
INSERT INTO `ml_templates` VALUES (2,'<p>Dear {{NAME}}</p>\n<p>Thanks for you interest.</p>\n<p>love</p>\n<p>US!</p>\n<p>-=-</p>\n<p>ti unsubscribe...</p>\n<p>&nbsp;</p>','Tester Template');
/*!40000 ALTER TABLE `ml_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` tinyint(4) DEFAULT '1',
  `parent_id` int(11) DEFAULT '0',
  `sort_order` int(11) DEFAULT NULL,
  `page_type` varchar(32) DEFAULT 'page',
  `deletable` tinyint(4) NOT NULL DEFAULT '1',
  `slots` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Homepage',NULL,'2009-12-09 13:25:43',1,0,1,'page',0,'top,left,mid,right'),(2,'Contact','<p>This is the contact info</p>','2009-05-31 17:44:47',1,1,2,'page',0,NULL),(3,'About','<p>About the bookshelf</p>','2009-05-31 17:44:56',1,1,3,'page',0,NULL),(4,'Legal','<p><em>insert mumbo jumbo here</em></p>','2009-05-31 18:57:23',1,1,4,'page',0,NULL),(5,'Privacy','<p>You have none on the internet, deal.</p>','2010-04-03 12:48:01',1,1,5,'page',0,NULL),(6,'Help','<p>If you were approaching Earth from space you might wonder about the green band that circles the northern part of the planet. This band of vegetation is called the \"boreal forest\", named after the Greek god of the north wind, Boreas.</p>\n<p>The boreal forest is found in Canada, Alaska, Russia, China, Mongolia, Norway, Sweden and Finland. Collectively, the boreal forest covers 10 per cent of the earth\'s land surface and represents about one-third of the world\'s forested area.</p>\n<p>The boreal forest is one of the largest ecosystems on the planet. It covers approximately one-half of Canada\'s landmass. Nearly 55 per cent of Saskatchewan is covered by forest.</p>\n<p>Saskatchewan\'s boreal forest and the boreal forests around the world are important to the health of our planet.</p>\n<p>\"These forests have been called the lungs of the planet because of the role they play in filtering our air,\" says Michael McLaughlan, Saskatchewan Environment\'s Director of Forest Management. \"An average tree in the boreal forest will absorb about a tonne of carbon dioxide over its lifetime. Trees in the boreal forest also produce a large amount of oxygen especially during the spring and summer when the trees are vigorously growing. During this time the amount of oxygen in the atmosphere around the world increases and the level of carbon dioxide drops.\"</p>\n<p>In Canada, the boreal forest is home to about two-thirds of Canada\'s 140,000 species of plants, animals and other organisms, including timber wolves, caribou, gray jays, loons, black spruce, jack pine and trembling aspen.</p>\n<p>Many mammals have evolved to make it easier to live in the boreal forest, which has short, cool, moist summers and long, cold, dry winters. For example, the caribou has hooves that are adapted for travel and digging in ice and snow in winter. They also have specialized digestion systems, making them the only member of the deer family that can live on lichens. Rabbits and some other animals change colour with the seasons so they can blend in with their surroundings and avoid predators; other species are able to hibernate. The beaver is one of the most important boreal forest animals. Its dams flood parts of the forest, creating ponds and wetlands that are used by fish, waterfowl and amphibians.</p>\n<p>The boreal forest is often called North America\'s bird nursery. It is used by nearly half of the continent\'s birds, over 300 species, for nesting and raising their young.</p>\n<p>&nbsp;</p>\n<p>In addition to providing habitat for birds and other wildlife, approximately 2.5 million Canadians live in communities that depend on the boreal forest. The boreal forest region supports about 900,000 direct and indirect jobs across Canada in industries such as forestry, mining, tourism, trapping and harvesting natural products.</p>\n<p>\"The boreal forest is a large and relatively resilient ecosystem but it is also under many pressures ranging from development to climate change,\" says Environment\'s McLaughlan.</p>\n<p>\"It\'s hard to imagine, but individual actions as simple as recycling and energy conservation can help by reducing climate change. Governments, industry and other partners are also working together to better understand how the boreal ecosystem works, how humans affect it and how we can make better decisions. Better understanding and using traditional knowledge will help us maintain the boreal forest for generations to come.\"</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>\n<p>&nbsp;</p>','2010-09-13 18:40:27',1,1,6,'page',0,NULL);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_answers`
--

DROP TABLE IF EXISTS `poll_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `count` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  CONSTRAINT `poll_answers_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_answers`
--

LOCK TABLES `poll_answers` WRITE;
/*!40000 ALTER TABLE `poll_answers` DISABLE KEYS */;
INSERT INTO `poll_answers` VALUES (54,5,'Nada',0,1);
/*!40000 ALTER TABLE `poll_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `poll_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
INSERT INTO `polls` VALUES (5,'Waddup?','2010-04-21 22:07:33');
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rating` varchar(128) NOT NULL,
  `rating_short` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
INSERT INTO `ratings` VALUES (1,'General','G'),(2,'Parental Guidance','PG'),(3,'14 Adult Accompaniment','14A'),(4,'18 Adult Accompaniment','18A'),(5,'Restricted','R');
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `route_role_map`
--

DROP TABLE IF EXISTS `route_role_map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `route_role_map` (
  `route_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `allow` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route_role_map`
--

LOCK TABLES `route_role_map` WRITE;
/*!40000 ALTER TABLE `route_role_map` DISABLE KEYS */;
INSERT INTO `route_role_map` VALUES (1,1,1),(2,1,1),(1,2,0),(2,2,0),(5,2,0),(7,2,0);
/*!40000 ALTER TABLE `route_role_map` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `routes`
--

LOCK TABLES `routes` WRITE;
/*!40000 ALTER TABLE `routes` DISABLE KEYS */;
INSERT INTO `routes` VALUES (1,'/admin','Site Admin'),(2,'/admin/articles','Articles - list'),(3,'/admin/calendar','Events - list'),(4,'/admin/media','Media Library - list'),(5,'/admin/films','Films - list '),(7,'/admin/films/edit/\\d+','Films - edit'),(8,'/admin/films/add','Films - add');
/*!40000 ALTER TABLE `routes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(28) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,'admin','system administrator'),(2,'editor','editor'),(3,'user','registered user'),(4,'contributor','contributor');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `passwd` varchar(128) DEFAULT NULL,
  `firstname` varchar(128) DEFAULT NULL,
  `lastname` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `role_id` int(11) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '1',
  `action_uuid` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_1` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','2023d250a2acbffd138a210b23f4177bf4249673','Jim','Knight','jim@talonedge.com','2010-09-23 13:57:54','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,NULL),(2,'jim','*E2D63C9947392FE08413C8B00B54E441B41E2DAB','Jim','Knight','jim@j2mfk.com','2010-04-14 16:15:00','0000-00-00 00:00:00','0000-00-00 00:00:00',2,1,NULL),(3,'doug','2cc36af84e176fb688bed64507a8e7502e38a92e','Doug','Minet','doug@bookshelf.ca','2009-06-01 20:33:24','0000-00-00 00:00:00','0000-00-00 00:00:00',1,1,NULL),(4,'guest','*EC50218EC7819FED8FB502A27606258E1B005517','Guest','User','','2009-06-01 21:10:33','0000-00-00 00:00:00','0000-00-00 00:00:00',3,0,NULL),(9,'jim@j2mfk.com','*5DD10267A777CFE3942A6627B9BDEED2ADA009D6','Jim','Knight','jim@j2mfk.com','2010-04-02 16:29:04','2010-01-27 20:13:58','2010-01-27 21:25:47',4,1,'forgot_6b97c4c7-512a-7de9-7506-3ec857d24371');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venues`
--

DROP TABLE IF EXISTS `venues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `venues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `descrip` text,
  `map_link` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal` char(7) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `prov` char(2) NOT NULL DEFAULT 'ON',
  `country` varchar(128) NOT NULL DEFAULT 'Canada',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venues`
--

LOCK TABLES `venues` WRITE;
/*!40000 ALTER TABLE `venues` DISABLE KEYS */;
INSERT INTO `venues` VALUES (1,'e-Bar (Bookshelf)','It\'s the e e e e',NULL,'41 Quebec St','','Guelph','ON','Canada'),(2,'Cinema (Bookshelf)','It\'s so, like, hollywood',NULL,'41 Quebec St','','Guelph','ON','Canada');
/*!40000 ALTER TABLE `venues` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-09-24  0:14:30
