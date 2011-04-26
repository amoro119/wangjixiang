-- MySQL dump 10.11
--
-- Host: localhost    Database: wangjixiang_com
-- ------------------------------------------------------
-- Server version	5.0.75-0ubuntu10.5-log

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
-- Table structure for table `dede_addonarticle`
--

DROP TABLE IF EXISTS `dede_addonarticle`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addonarticle` (
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `body` mediumtext,
  `redirecturl` varchar(255) NOT NULL default '',
  `templet` varchar(30) NOT NULL default '',
  `userip` char(15) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addonarticle`
--

LOCK TABLES `dede_addonarticle` WRITE;
/*!40000 ALTER TABLE `dede_addonarticle` DISABLE KEYS */;
INSERT INTO `dede_addonarticle` VALUES (1,21,'<p>旺&middot;吉祥太贪玩啦&hellip;&hellip;在韩国和日本真的玩得流连忘返，都舍不得走了。<br />\r\n<br />\r\n不过。我要环游世界九十天，所以要加快脚步啦！<br />\r\n<br />\r\n这回呀，吉祥还要去次美国。<br />\r\n<br />\r\n关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的西部转转。<br />\r\n<br />\r\n西边的太阳快要落山了，美国的西部静悄悄。弹起我心爱的小吉他，唱着那动人的歌谣。好一幅古道西风瘦马，夕阳西下，旺&middot;吉祥在天涯。。。</p>\r\n<p>旺&middot;吉祥太贪玩啦&hellip;&hellip;在韩国和日本真的玩得流连忘返，都舍不得走了。<br />\r\n<br />\r\n不过。我要环游世界九十天，所以要加快脚步啦！<br />\r\n<br />\r\n这回呀，吉祥还要去次美国。<br />\r\n<br />\r\n关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的西部转转。<br />\r\n<br />\r\n西边的太阳快要落山了，美国的西部静悄悄。弹起我心爱的小吉他，唱着那动人的歌谣。好一幅古道西风瘦马，夕阳西下，旺&middot;吉祥在天涯。。。</p>\r\n<p>旺&middot;吉祥太贪玩啦&hellip;&hellip;在韩国和日本真的玩得流连忘返，都舍不得走了。<br />\r\n<br />\r\n不过。我要环游世界九十天，所以要加快脚步啦！<br />\r\n<br />\r\n这回呀，吉祥还要去次美国。<br />\r\n<br />\r\n关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的西部转转。<br />\r\n<br />\r\n西边的太阳快要落山了，美国的西部静悄悄。弹起我心爱的小吉他，唱着那动人的歌谣。好一幅古道西风瘦马，夕阳西下，旺&middot;吉祥在天涯。。。旺&middot;吉祥太贪玩啦&hellip;&hellip;在韩国和日本真的玩得流连忘返，都舍不得走了。<br />\r\n<br />\r\n不过。我要环游世界九十天，所以要加快脚步啦！<br />\r\n<br />\r\n这回呀，吉祥还要去次美国。<br />\r\n<br />\r\n关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的西部转转。<br />\r\n<br />\r\n西边的太阳快要落山了，美国的西部静悄悄。弹起我心爱的小吉他，唱着那动人的歌谣。好一幅古道西风瘦马，夕阳西下，旺&middot;吉祥在天涯。。。</p>\r\n<p>旺&middot;吉祥太贪玩啦&hellip;&hellip;在韩国和日本真的玩得流连忘返，都舍不得走了。<br />\r\n<br />\r\n不过。我要环游世界九十天，所以要加快脚步啦！<br />\r\n<br />\r\n这回呀，吉祥还要去次美国。<br />\r\n<br />\r\n关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的西部转转。<br />\r\n<br />\r\n西边的太阳快要落山了，美国的西部静悄悄。弹起我心爱的小吉他，唱着那动人的歌谣。好一幅古道西风瘦马，夕阳西下，旺&middot;吉祥在天涯。。。</p>','','','127.0.0.1'),(2,30,'','','','127.0.0.1'),(3,22,'','','','127.0.0.1'),(4,22,'','','','127.0.0.1'),(5,22,'','','','127.0.0.1'),(6,22,'','','','127.0.0.1'),(7,22,'','','','127.0.0.1'),(8,33,'','','','127.0.0.1'),(9,33,'','','','127.0.0.1'),(10,33,'','','','127.0.0.1'),(11,21,'<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">话说，最近朋友们都在谈论元旦假期里去了哪些好玩的地方。</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">当然也有朋友在考虑即将来临的春节长假打算去哪儿。</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">不知道朋友们都打算怎么出游呢？</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">是跟旅游团还是自助游呢？</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">我想了想啊，不管用哪种方式，总得有个相当于领队的角色吧？</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">嘿嘿，其实呢，导游这个职业是我第二个心愿。你想啊，导游多好啊，能到处跑，又能看到各种地域风情。</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span class=\"Apple-style-span\">这种自由自在的感觉可是我一直很向往的呢！</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">My friends are talking about what they went during this New Year&rsquo;s Day holiday recently. </span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">They are also thinking about where are they going in the Spring Festival. </span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">Well, I don&rsquo;t know what kind of method they are going to use.</span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">Travel agency or self-travelling? </span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">I think either way must have a person who can lead other. </span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">Actually, my second wish is to be a tour guide because a tour guide can go to lots of places and see many things from foreign countries. </span></span></span></p>\r\n<p class=\"MsoNormal\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体\"><span lang=\"EN-US\">I love this kind of feeling!</span></span></span></p>\r\n<p><span style=\"font-size: larger\"><span style=\"font-family: 宋体\">&nbsp;</span></span></p>','','','211.161.231.103'),(12,21,'<p>新年就要来咯！</p>\r\n<div>今天是31号了，大家是不是准备和朋友们一起庆祝和迎接即将来到的新年呢？如果不是的话，那我们是不是也该给亲朋好友打个电话送出自己的祝福呢？好了，那就由我现在带个头吧，在这里我要祝福大家新年快乐，吉祥！</div>\r\n<div>&nbsp;嗯！就这样吧！我先带着我的宠物兔兔出去玩一会咯！对了！有兴趣的朋友今晚可一定要来和我旺吉祥一起倒数，期待着新年的到来哦！</div>\r\n<div>&nbsp;</div>\r\n<div align=\"left\"><span style=\"font-size: 12pt\">Today is Dec.31! I think everyone is going to celebrate the New Year\'s Day with friends, right? If not, I think we should at least make a phone call to our parents and friends. Ok, I will do the first one. I wish everyone has a happy New year\'s Day.</span></div>\r\n<div align=\"left\"><span style=\"font-size: 12pt\">&nbsp;&nbsp;&nbsp; All right! I\'m going to play with my little rabbit. Welcome all friends who\'s willing to do the countdown with me!</span></div>','','','211.161.231.103'),(13,21,'<p><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">&nbsp;</span></span></p>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">新年就要来咯！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">今天是31号了，大家是不是准备和朋友们一起庆祝和迎接即将来到的新年呢？</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">如果不是的话，那我们是不是也该给亲朋好友打个电话送出自己的祝福呢？</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">好了，那就由我现在带个头吧，在这里我要祝福大家新年快乐，吉祥！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">嗯！就这样吧！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">我先带着我的宠物兔兔出去玩一会咯！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">对了！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">有兴趣的朋友今晚可一定要来和我旺吉祥一起倒数，期待着新年的到来哦！</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">Today is Dec.31!</span></span></div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">&nbsp;</span></span></div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">I think everyone is going to celebrate the New Year\'s Day with friends, right? </span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">If not, I think we should at least make a phone call to our parents and friends. </span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">Ok, I will do the first one. </span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">I wish everyone has a happy New year\'s Day.</span></span></div>\r\n<div>&nbsp;</div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">All right! </span></span><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">I\'m going to play with my little rabbit.</span></span></div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">&nbsp;</span></span></div>\r\n<div><span style=\"font-family: 宋体\"><span style=\"font-size: larger\">Welcome all friends who\'s willing to do the countdown with me!</span></span></div>','','','115.174.109.122'),(14,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">又是一个星期天，到中午喽！好饿哦<span lang=\"EN-US\">~~~</span></font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\">10</span>分钟前妈妈打电话回来说，她还在外面办事，一时间回不来呢。</font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\">&ldquo;</span>唉。。。咋办捏？<span lang=\"EN-US\">&rdquo;</span>我想着想着，走到了厨房。<span lang=\"EN-US\">&ldquo;</span>对了！我自己来做饭！</font></font></span><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">一来我还能实践实践做饭做菜，二来等到妈妈回来了，看到我做的饭菜以后，她一定会很高兴的！<span lang=\"EN-US\">&rdquo;<o:p></o:p></span></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">嗯！说干就干，我穿起围裙，拿起锅铲，搞得热火朝天。希望妈妈喜欢我做的饭哦！<span lang=\"EN-US\"><o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Sunday again! I&rsquo;m so starving&hellip;</font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Mama phoned me 10 minutes earlier. She said that she was busy outside, so she would come back home as soon as possible. </font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&ldquo;So&hellip;&hellip; What am I going to do?&rdquo; I was walking around the house while I was thinking. &ldquo;Right! Let me do the cooking! So I can practise my cooking skill and I think Mama will be so happy after she come back!&rdquo; </font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">OK! Less talking more working. I wore apron and took the pot. HoHo, I was working so hard! I hope Mama would love me cooking!<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(15,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天我问如意：<span lang=\"EN-US\">&ldquo;</span>你的梦想是什么呀？<span lang=\"EN-US\">&rdquo;</span></font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">如意笑眯眯地告诉我：<span lang=\"EN-US\">&ldquo;</span>我这么漂亮，当然是当明星咯<span lang=\"EN-US\">~</span>，你呢？<span lang=\"EN-US\">&rdquo;</span></font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我想了一会儿，说：<span lang=\"EN-US\">&ldquo;</span>既然你相当明星，那我就做导演给你拍电影电视剧啥的好了！<span lang=\"EN-US\">&rdquo;</span></font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">此时此刻，我脑海中浮现出我是个导演的画面。</font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\">&ldquo;</span>灯光，道具，演员准备！<span lang=\"EN-US\"> 3</span>！<span lang=\"EN-US\">2</span>！<span lang=\"EN-US\">1</span>！ 开拍！<span lang=\"EN-US\">&rdquo;</span>你看我手中拿着大喇叭指挥，太专业了！<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Today</span><span style=\"color: #464646\">，<span lang=\"EN-US\">I asked Ru-yi a question about her dream. </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">She said that she wanted to be a famous star since she was so cute. </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">Then she asked what my dream was. </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">I thought for a while, then answered:&ldquo; since you want to be a famous star, then I will be a director to make movie for TV show for you!.&rdquo; </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">At that time, the vision of I being a director appeared in my mind. </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">&ldquo; Lights, Camera, Actors, standby! 3! 2! 1! Action!&rdquo; </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">I directed the movie by using the loudspeaker. Just like a Pro.!<o:p></o:p></span></span></font></font></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(16,21,'<p style=\"text-indent: 21.75pt\"><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天我约了如意一起吃午饭。<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p style=\"text-indent: 21.75pt\"><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">看时间快到中午，她该来了吧，我要给她一个意外和惊喜，是什么呢？嘻嘻。<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p style=\"text-indent: 21.75pt\"><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">看，我像变魔术一样转身换了套餐厅服务员的制服，帅不帅啊？恩，自己照了照镜子，感觉还是蛮不错的，心，砰砰跳，但还故作镇定，趁如意还没到，我先练习一下，托个盘，这个动作专业哇？<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p style=\"text-indent: 21.75pt\"><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我在想，如意见了我会是怎样的表情啊？<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;&nbsp;&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;&nbsp;&nbsp; I am going to have lunch with Ri-yi today.<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;&nbsp;&nbsp; It\'s almost the time for lunch, I think she will be coming soon. Let\'s see...... Maybe I can give her a little surprise!<o:p></o:p></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">&nbsp;&nbsp;&nbsp; Look! I use my &quot;mgaic&quot;(changing clothes using a high speed) to change my outfits into a waiter! Am I looking good</span><span style=\"color: #464646\">？<span lang=\"EN-US\">I look at me by the mirror, fantastic! My heart is beating because I\'m little nervous. Well, let me do some practise, like using the pallet. So professional!<o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;&nbsp; I am imagining Ri-yi is reaction after she sees me~<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(17,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天的天气好好哦，正好赶上放假，做些什么让自己快乐的事呢？玩？当然没多大意思，对了！种花！<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">这段时间妈妈因为太忙了，没什么时间打理她的花园，那么我来帮她吧。</span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">我穿上围裙，拿起水壶，忙得不亦乐乎，原来打理花园这么辛苦哦<span lang=\"EN-US\">&hellip;&hellip;</span>晚上妈妈回来后，看到我的劳动成果，她一定很开心的<span lang=\"EN-US\">~<o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Nice weather today! No school! So, what am I going to do today? Play around the house? No! I must do something meaningful! </span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Let me think&hellip;&hellip; Right</span><span style=\"color: #464646\">！<span lang=\"EN-US\">Planting some flowers! </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">Recently Mama is pretty busy, so she doesn&rsquo;t have time to do the garden works. </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">All right! Let&rsquo;s do this! </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">I dress an apron and take Kettle and do the job. Wow&hellip;&hellip;So tired! </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">I never think this is a hard job! </span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">Well, as long as Mama feels happy after she sees what I&rsquo;ve done, I feel happy, too!<o:p></o:p></span></span></font></font></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(18,21,'<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">期末摸底考试结果下来了，全班同学都考得不好。</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">唉</span><span lang=\"EN-US\"><font face=\"Times New Roman\">&hellip;&hellip;</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">大家都挺后悔的，轻敌了嘛</span><span lang=\"EN-US\"><font face=\"Times New Roman\">&hellip;&hellip;</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">老师安慰我们说：&ldquo;别后悔啦，天底下没有后悔药的。这次只不过是摸底考试而已，吸取教训，下次在正式考中取得好成绩就行了</span><span lang=\"EN-US\"><font face=\"Times New Roman\">!</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">&rdquo;</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">我旺吉祥想啊，是啊，没有后悔药的，别想啦</span><span lang=\"EN-US\"><font face=\"Times New Roman\">&hellip;&hellip;</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">等等，后悔药！</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">要是我长大以后能够成为一名化学药剂师，伴随着日益发达的科技，说不定我能发明真正的后悔药呢！</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">于是，我就开始遐想，穿着白大褂，胸前夹着我的工作证，左手一个试管，右手一个烧杯，努力攻关研发</span><span lang=\"EN-US\"><font face=\"Times New Roman\">&hellip;&hellip;</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">祝我成功吧！</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><o:p><font face=\"Times New Roman\">&nbsp;</font></o:p></span></span><span lang=\"EN-US\"><o:p></o:p></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><o:p><font face=\"Times New Roman\">&nbsp;</font></o:p></span></span><span lang=\"EN-US\"><o:p></o:p></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">The result of simulative final examination was out. We almost failed the test&hellip;&hellip; </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">We regretted deadly because we thought the test should be very easy&hellip;&hellip; </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Our teacher said: &ldquo; It&rsquo;s OK. There is no use crying over spilt milk. This is just a simulative test. So do your best on the real Finals.&rdquo; </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Well, I knew we couldn&rsquo;t regret for the results&hellip;&hellip; </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Wait a minute, what about inventing medicines for regret in the future? </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">The science technology is developing very fast. Maybe I can invent this kind of medicines in the future! </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">HoHo! A chemistry scientist, huh? Wearing a white coat with my working license on my chest, test tube in my left hand, glass beaker in my right hand&hellip;&hellip; </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Trying my best to achieve my goal&hellip;&hellip;Wish me luck!</font></span></span></p>','','','124.14.62.12'),(19,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天天气真好，太阳高高照，小鸟在天空中自由的飞翔。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我呢？嘿嘿，当然是搬着我的工具到大自然中写生！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我今天非常高兴因为如意也陪我去呢！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">嗯，我决定一会儿画一张画给她！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">你看，我又是铅笔打草稿，然后用颜料调色，再上色。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">是不是很专业呢？<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">What a good day! </span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">The sunshine, the birds are flying in the sky.</span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">How about me? Of course, I am going to draw something outside! </span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">I\'m so happy because Ri-yi is coming with me, too! </span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">So&nbsp;I decide to draw a picture for her! </span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Look, I use the pencil to do the drafting first, then use the paint to mix the color up, and finally put the color on the paper</span><span style=\"color: #464646\">！<span lang=\"EN-US\"> </span></span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">Am I professinal? HOHO!<o:p></o:p></span></span></font></font></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(20,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">这两天如意不理我了<span lang=\"EN-US\">&hellip;&hellip;</span>她说我这段时间都不哄她开心。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">唉<span lang=\"EN-US\">&hellip;</span>我也没办法呀<span lang=\"EN-US\">&hellip;&hellip;</span>这几个礼拜学校又是是音乐节，家里又要帮着妈妈做家务，好忙啊！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">一时间疏忽了如意，怎么办呢？</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">哦对了！我去学跳街舞！又酷又有型！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">说不定如意看了会很开心呢！<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Ru-yi doesn&rsquo;t want to talk with me recently&hellip;&hellip; </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">She says that I haven&rsquo;t made her happy for a long time&hellip;&hellip; </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Well, I was so busy these weeks, music festival at school, house working with Mama at home&hellip;&hellip; </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">So, what can I do? Right! I&rsquo;m going to learn Hip-Hop! I know it&rsquo;s so cool! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Ru-yi must be happy after she sees my dancing!<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(21,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天是我第一次当小老师哦！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">当老师是我梦寐以求的愿望，可是我没经验啊，心里不免紧张起来。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">妈妈昨晚对我说：<span lang=\"EN-US\">&ldquo;</span>吉祥啊，明天不要紧张哦<span lang=\"EN-US\">~</span>做任何事都有第一次，<span lang=\"EN-US\">&lsquo;</span>万事开头难<span lang=\"EN-US\">&rsquo;</span>嘛！<span lang=\"EN-US\">&rdquo;</span></font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我想，我要对自己有信心，要抓住这次机会，试试看！</font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\"><o:p></o:p></span></font></font></span>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">想到这里，我也心里也就稍微放松了一点。<span lang=\"EN-US\">&nbsp;</span></span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\">&ldquo;</span>上课！今天英文课的题目是自我介绍。首先，老师我来做个示范！<span lang=\"EN-US\"> Hello everyone</span>！<span lang=\"EN-US\">My name is Wang Ji-Xiang.&rdquo;<o:p></o:p></span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">嘿嘿！你们看我当小老师还是有模有样的吧？<span lang=\"EN-US\"><o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Today is my first time to be a teacher! You know, being a teacher is the greatest dream in my live! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">But, I don&rsquo;t have any experiences&hellip;&hellip; I am so nervous. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Last night, Mama said: &ldquo;you don&rsquo;t have to nervous. You know everything has its first time. Everything is always difficult at the beginning.&rdquo; </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">I think I need to use this good chance to try it.&rdquo;<o:p></o:p></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Now I feel relaxed a bit. </span></font></font><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">&ldquo; Ok class! Let&rsquo;s learn something! </span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\" style=\"color: #464646\">Today we are going to learn how to introduce ourselves to others. First, let me do a demonstration.&nbsp; Hello everyone</span><span style=\"color: #464646\">！<span lang=\"EN-US\"> My name is Wang Ji-Xiang.&rdquo;</span></span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\"><o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">HoHo! Am I looking good like a teacher?<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(22,21,'<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">圣诞节过去了，元旦即将来临。</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">我呢，想带如意去海边看看风景。</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">可是这么远，公交车也没有到海边的，怎么办呢？</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">前两天我试探如意，她看上去还是非常想去的，难道就没有什么更好的办法了么？</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">我想想</span><span lang=\"EN-US\"><font face=\"Times New Roman\">&hellip;&hellip;</font></span><span style=\"font-family: 宋体; mso-ascii-font-family: \'Times New Roman\'; mso-hansi-font-family: \'Times New Roman\'\">对啊，开车去不就搞定了嘛！真是个好办法，到时候我开着车到如意家门口，不知道她会不会感到惊喜呢？</span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><o:p><font face=\"Times New Roman\">&nbsp;</font></o:p></span></span><span lang=\"EN-US\"><o:p></o:p></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Christmas has passed away, New Year&rsquo;s Day is coming soon! </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">I think I should take Ru-yi to the beach to see the nice view. </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">But it&rsquo;s &hellip;&hellip; a little bit far away from the city, and there&rsquo;s no bus there. </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">So&hellip;&hellip; how can we get there? Ru-yi thought that she really wants to go. But right now, I have no idea. </font></span></span></p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"margin: 0cm 0cm 0pt; text-indent: 21.75pt\"><span style=\"font-size: larger\"><span lang=\"EN-US\"><font face=\"Times New Roman\">Let me think&hellip;&hellip; Right! I will drive a car! What a good idea! Ru-yi must feel surprise when I drive a car to her home!</font></span></span></p>\r\n<p><span style=\"font-size: larger\">&nbsp;</span></p>','','','124.14.62.12'),(24,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天上语文课，老师让我们写作文，题目是《我的梦想》。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">哎呀<span lang=\"EN-US\">&hellip;&hellip;</span>平时聊天啊，谈话的时候，不管什么我的能夸夸其谈，怎么今天这作文就难倒我了呢<span lang=\"EN-US\">&hellip;&hellip;</span></font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">想着想着，我就睡着了<span lang=\"EN-US\">&hellip;&hellip;</span>我梦见我身穿身穿迷彩，头带钢盔，手持<span lang=\"EN-US\">AK-47</span>，整个一又帅又酷的特种兵战士啊！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">等等<span lang=\"EN-US\">&hellip;&hellip;</span>难道这就是日有所思夜有所梦？对啊！也许这就是我的梦想啊！！！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">于是乎，我马上醒来了！三下五除二，写完了这作文，心里还挺高兴的呢！<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Today, our teacher wanted us to write an easy about &ldquo;our dreams&rdquo;. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Err&hellip;&hellip;I can talk as much as possible when I was talking with others. But, how come I can&rsquo;t write this simple easy? </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Then, I felt asleep&hellip;&hellip; </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">WoW! I wore Battle Dress Uniform and steel helmet, and carried an AK-47! I&rsquo;m a cool Special Force Warrior! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Hold on&hellip;&hellip; Isn&rsquo;t this dreaming in the night means thinking in the day time&rdquo;? </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Right! Maybe this was my dream! I woke up quickly and wrote down the easy. HaHa, it felt so good!<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(25,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">今天在学校，我们今天要学习医疗知识，好兴奋啊！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">以</font></font></span><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">后要是碰到什么不舒服的情况，我吉祥也能自己解决咯！妈妈一定会很高兴的！</font></font></span></p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">老师说，要在小朋友中间选一个来扮演医生。</span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">于是乎，我自告奋勇，第一个举起了手。</span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">嘿嘿<span lang=\"EN-US\">~</span>我穿上了老师带来的小号白大褂，戴上红十字帽子，脖子上挂着听诊器。是不是很帅啊？</span></font></font></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">我马上就要帮小朋友们<span lang=\"EN-US\">&ldquo;</span>看病<span lang=\"EN-US\">&rdquo;</span>喽！</span></font></font></p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\"><span lang=\"EN-US\"><o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Today, we are going to learn some medical knowledge at school. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">WoW! I\'m so excited! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">After today, I can solve many easy problems by myself! Mama will be proud of me, too!<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Our Teacher says that she will pick one student to pretending doctor. I raise my hand ASAP! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">HOHO! I put on the small size white coat, wear the Red-Cross hat and take the stethoscope! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Am I cool enough to attract you? All right, I\'m off to &quot;cure&quot; my classmates. See you!<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(26,21,'<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">学校要举行艺术节喽！</font></font></span><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">老师说，我们小班也得准备个节目。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">我旺吉祥想啊想，那就搞个音乐会吧。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">嗯！就这么愉快的决定了！老师也同意了我的想法。</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">当确定好所有人的位置以后，咦，竟发现少了个指挥。于是啊，我就推荐自己说，那我来做指挥吧！</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">嘿嘿，又能出风头咯！<span lang=\"EN-US\"><o:p></o:p></span></font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><font size=\"3\"><font face=\"宋体\"><span style=\"color: #464646\">你们看，我排练的时候也够帅吧？<span lang=\"EN-US\"><o:p></o:p></span></span></font></font></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">&nbsp;<o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Our school is preparing a Art Festival! Our teacher says that our class must have an art show. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">So I think we may prepare for a music concert. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">All right! Let&rsquo;s do this! Our teacher agrees my idea, tool. </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">After we get everyone a job, I find out that we don&rsquo;t have a music director! Ok, I will do this! </font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">HoHo, it&rsquo;s good to be on the front!</font></font></span></p>\r\n<p>&nbsp;</p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\"><o:p></o:p></font></font></span></p>\r\n<p><span lang=\"EN-US\" style=\"color: #464646\"><font size=\"3\"><font face=\"宋体\">Hmmmm&hellip;..I think I am so cool. Don&rsquo;t you agree?<o:p></o:p></font></font></span></p>\r\n<p>&nbsp;</p>','','','124.14.62.12'),(27,20,'','','','115.174.109.122'),(28,20,'','','','115.174.109.122'),(29,23,'','','','115.174.109.122'),(30,23,'','','','115.174.109.122'),(31,23,'','','','115.174.109.122'),(32,23,'','','','115.174.109.122'),(33,23,'','','','115.174.109.122'),(34,23,'','','','115.174.109.122'),(35,23,'','','','115.174.109.122'),(36,23,'','','','115.174.109.122'),(37,22,'','','','115.174.109.122'),(38,33,'','','','115.172.89.137'),(39,24,'','','','115.174.109.122'),(40,24,'','','','115.174.109.122'),(41,24,'','','','115.174.109.122'),(42,22,'','','','115.174.109.122'),(43,25,'','','','115.174.109.122'),(44,25,'','','','115.174.109.122'),(45,27,'','','','115.172.89.137'),(46,27,'','','','115.174.109.122'),(47,28,'','','','115.174.109.122'),(48,28,'','','','115.174.109.122'),(49,28,'','','','115.174.109.122'),(50,28,'','','','115.174.109.122'),(51,28,'','','','115.174.109.122'),(52,29,'','','','115.174.109.122'),(53,29,'','','','115.174.109.122'),(54,29,'','','','115.174.109.122'),(55,29,'','','','115.174.109.122'),(56,19,'','','','115.174.109.122'),(57,19,'','','','115.174.109.122'),(58,19,'','','','115.174.109.122'),(59,19,'','','','115.174.109.122'),(60,19,'','','','115.174.109.122'),(61,19,'','','','115.174.109.122'),(62,19,'','','','115.174.109.122'),(63,19,'','','','115.174.109.122'),(64,19,'','','','115.174.109.122'),(65,19,'','','','115.174.109.122'),(66,19,'','','','115.174.109.122'),(67,19,'','','','115.174.109.122'),(68,19,'','','','115.174.109.122'),(69,21,'<p>&nbsp;</p>\r\n<div>恭祝大家新春快乐！</div>\r\n<div>&nbsp;</div>\r\n<div>今天是大年初一！</div>\r\n<div>&nbsp;</div>\r\n<div>各位朋友过年好！</div>\r\n<div>&nbsp;</div>\r\n<div>感谢大家一年来对我的关注。我旺吉祥真的是激动得无法用言语来表达对各位的感谢！</div>\r\n<div>&nbsp;</div>\r\n<div>不过呢，我想，至少呀，还是得说些什么比较好，毕竟，这是给大家的在新年里的祝福嘛~</div>\r\n<div>&nbsp;</div>\r\n<div>好！在这里，我祝愿大家新的1年开始，祝好事接2连3，心情4季如春，生活5颜6色，7彩缤纷，偶尔8点小财，烦恼抛到9霄云外！请接受我10心10意的祝福。祝新春快乐！</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div>Today is the first day of New Year.</div>\r\n<div>&nbsp;</div>\r\n<div>Happy New Year everyone!</div>\r\n<div>&nbsp;</div>\r\n<div>Thanks to everyone&rsquo;s attentions on me.</div>\r\n<div>&nbsp;</div>\r\n<div>I almost can&rsquo;t control my emotion to say some blessings for everyone!</div>\r\n<div>&nbsp;</div>\r\n<div>But, I think I still need to say something. You know, New Year&rsquo;s blessings can make people happy!</div>\r\n<div>&nbsp;</div>\r\n<p><span style=\"font-size: 10.5pt\">So, I wish everyone have a nice beginning of the New Year, happy forever, earn more money, no more sadness. Using one general sentence, HAPPY NEW YEAR!</span></p>','','','61.173.24.217'),(70,21,'','','','211.161.249.7'),(71,19,'','','','211.161.229.135'),(72,19,'','','','124.14.60.241'),(73,19,'','','','211.161.248.204'),(74,19,'','','','115.174.106.159'),(75,19,'','','','115.174.106.159'),(76,19,'','','','115.174.106.159'),(77,21,'','','','124.14.61.100');
/*!40000 ALTER TABLE `dede_addonarticle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_addonimages`
--

DROP TABLE IF EXISTS `dede_addonimages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addonimages` (
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `pagestyle` smallint(6) NOT NULL default '1',
  `maxwidth` smallint(6) NOT NULL default '600',
  `imgurls` text,
  `row` smallint(6) NOT NULL default '0',
  `col` smallint(6) NOT NULL default '0',
  `isrm` smallint(6) NOT NULL default '0',
  `ddmaxwidth` smallint(6) NOT NULL default '200',
  `pagepicnum` smallint(6) NOT NULL default '12',
  `templet` varchar(30) NOT NULL default '',
  `userip` char(15) NOT NULL default '',
  `redirecturl` varchar(255) NOT NULL default '',
  `body` mediumtext,
  PRIMARY KEY  (`aid`),
  KEY `imagesMain` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addonimages`
--

LOCK TABLES `dede_addonimages` WRITE;
/*!40000 ALTER TABLE `dede_addonimages` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_addonimages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_addoninfos`
--

DROP TABLE IF EXISTS `dede_addoninfos`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addoninfos` (
  `aid` int(11) NOT NULL default '0',
  `typeid` int(11) NOT NULL default '0',
  `channel` smallint(6) NOT NULL default '0',
  `arcrank` smallint(6) NOT NULL default '0',
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `click` int(10) unsigned NOT NULL default '0',
  `title` varchar(60) NOT NULL default '',
  `litpic` varchar(60) NOT NULL default '',
  `userip` varchar(15) NOT NULL default ' ',
  `senddate` int(11) NOT NULL default '0',
  `flag` set('c','h','p','f','s','j','a','b') default NULL,
  `lastpost` int(10) unsigned NOT NULL default '0',
  `scores` mediumint(8) NOT NULL default '0',
  `goodpost` mediumint(8) unsigned NOT NULL default '0',
  `badpost` mediumint(8) unsigned NOT NULL default '0',
  `nativeplace` smallint(5) unsigned NOT NULL default '0',
  `infotype` smallint(5) unsigned NOT NULL default '0',
  `body` mediumtext,
  `endtime` int(11) NOT NULL default '0',
  `tel` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `linkman` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `typeid` (`typeid`,`nativeplace`,`infotype`),
  KEY `channel` (`channel`,`arcrank`,`mid`,`click`,`title`,`litpic`,`senddate`,`flag`,`endtime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addoninfos`
--

LOCK TABLES `dede_addoninfos` WRITE;
/*!40000 ALTER TABLE `dede_addoninfos` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_addoninfos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_addonshop`
--

DROP TABLE IF EXISTS `dede_addonshop`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addonshop` (
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `body` mediumtext,
  `price` float NOT NULL default '0',
  `trueprice` float NOT NULL default '0',
  `brand` varchar(250) NOT NULL default '',
  `units` varchar(250) NOT NULL default '',
  `templet` varchar(30) NOT NULL,
  `userip` char(15) NOT NULL,
  `redirecturl` varchar(255) NOT NULL,
  PRIMARY KEY  (`aid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addonshop`
--

LOCK TABLES `dede_addonshop` WRITE;
/*!40000 ALTER TABLE `dede_addonshop` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_addonshop` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_addonsoft`
--

DROP TABLE IF EXISTS `dede_addonsoft`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addonsoft` (
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `filetype` varchar(10) NOT NULL default '',
  `language` varchar(10) NOT NULL default '',
  `softtype` varchar(10) NOT NULL default '',
  `accredit` varchar(10) NOT NULL default '',
  `os` varchar(30) NOT NULL default '',
  `softrank` mediumint(8) unsigned NOT NULL default '0',
  `officialUrl` varchar(30) NOT NULL default '',
  `officialDemo` varchar(50) NOT NULL default '',
  `softsize` varchar(10) NOT NULL default '',
  `softlinks` text,
  `introduce` text,
  `daccess` smallint(5) NOT NULL default '0',
  `needmoney` smallint(5) NOT NULL default '0',
  `templet` varchar(30) NOT NULL default '',
  `userip` char(15) NOT NULL default '',
  `redirecturl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `softMain` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addonsoft`
--

LOCK TABLES `dede_addonsoft` WRITE;
/*!40000 ALTER TABLE `dede_addonsoft` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_addonsoft` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_addonspec`
--

DROP TABLE IF EXISTS `dede_addonspec`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_addonspec` (
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `note` text,
  `templet` varchar(30) NOT NULL default '',
  `userip` char(15) NOT NULL default '',
  `redirecturl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `typeid` (`typeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_addonspec`
--

LOCK TABLES `dede_addonspec` WRITE;
/*!40000 ALTER TABLE `dede_addonspec` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_addonspec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_admin`
--

DROP TABLE IF EXISTS `dede_admin`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_admin` (
  `id` int(10) unsigned NOT NULL,
  `usertype` float unsigned default '0',
  `userid` char(30) NOT NULL default '',
  `pwd` char(32) NOT NULL default '',
  `uname` char(20) NOT NULL default '',
  `tname` char(30) NOT NULL default '',
  `email` char(30) NOT NULL default '',
  `typeid` text,
  `logintime` int(10) unsigned NOT NULL default '0',
  `loginip` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_admin`
--

LOCK TABLES `dede_admin` WRITE;
/*!40000 ALTER TABLE `dede_admin` DISABLE KEYS */;
INSERT INTO `dede_admin` VALUES (1,10,'admin','f297a57a5a743894a0e4','admin','','','0',1303453066,'112.65.132.162');
/*!40000 ALTER TABLE `dede_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_admintype`
--

DROP TABLE IF EXISTS `dede_admintype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_admintype` (
  `rank` float NOT NULL default '1',
  `typename` varchar(30) NOT NULL default '',
  `system` smallint(6) NOT NULL default '0',
  `purviews` text,
  PRIMARY KEY  (`rank`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_admintype`
--

LOCK TABLES `dede_admintype` WRITE;
/*!40000 ALTER TABLE `dede_admintype` DISABLE KEYS */;
INSERT INTO `dede_admintype` VALUES (1,'信息发布员',1,'t_AccList a_AccNew a_AccList a_MyList a_MyEdit a_MyDel sys_MdPwd sys_Feedback sys_MyUpload plus_留言簿模块 '),(5,'频道管理员',1,'t_AccList t_AccNew t_AccEdit t_AccDel a_AccNew a_AccList a_AccEdit a_AccDel a_AccCheck a_MyList a_MyEdit a_MyDel a_MyCheck co_AddNote co_EditNote co_PlayNote co_ListNote co_ViewNote spec_New spec_List spec_Edit sys_MdPwd sys_Log sys_ArcTj sys_Source sys_Writer sys_Keyword sys_MakeHtml sys_Feedback sys_Upload sys_MyUpload member_List member_Edit plus_站内新闻发布 plus_友情链接模块 plus_留言簿模块 plus_投票模块 plus_广告管理 '),(10,'超级管理员',1,'admin_AllowAll ');
/*!40000 ALTER TABLE `dede_admintype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_advancedsearch`
--

DROP TABLE IF EXISTS `dede_advancedsearch`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_advancedsearch` (
  `mid` int(11) NOT NULL,
  `maintable` varchar(256) NOT NULL default '',
  `mainfields` text,
  `addontable` varchar(256) default '',
  `addonfields` text,
  `forms` text,
  `template` varchar(256) NOT NULL default '',
  UNIQUE KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_advancedsearch`
--

LOCK TABLES `dede_advancedsearch` WRITE;
/*!40000 ALTER TABLE `dede_advancedsearch` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_advancedsearch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arcatt`
--

DROP TABLE IF EXISTS `dede_arcatt`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arcatt` (
  `sortid` smallint(6) NOT NULL default '0',
  `att` char(10) NOT NULL default '',
  `attname` char(30) NOT NULL default '',
  PRIMARY KEY  (`att`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arcatt`
--

LOCK TABLES `dede_arcatt` WRITE;
/*!40000 ALTER TABLE `dede_arcatt` DISABLE KEYS */;
INSERT INTO `dede_arcatt` VALUES (5,'s','滚动'),(1,'h','头条'),(3,'f','幻灯'),(2,'c','推荐'),(7,'p','图片'),(8,'j','跳转'),(4,'a','特荐'),(6,'b','加粗');
/*!40000 ALTER TABLE `dede_arcatt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arccache`
--

DROP TABLE IF EXISTS `dede_arccache`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arccache` (
  `md5hash` char(32) NOT NULL default '',
  `uptime` int(11) NOT NULL default '0',
  `cachedata` mediumtext,
  PRIMARY KEY  (`md5hash`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arccache`
--

LOCK TABLES `dede_arccache` WRITE;
/*!40000 ALTER TABLE `dede_arccache` DISABLE KEYS */;
INSERT INTO `dede_arccache` VALUES ('6061452f3bbb5c6394322bf477cdc8c2',1303694555,'7,6'),('26c8ad5d35619cfd47b179c2253f1bf1',1303725213,'50,49,48,47'),('eddd45a6248c6f48a77bd670613d43dd',1303725213,'51,50,49,48,47'),('d7a5c219a6994b37c5e4beaf879b0f2e',1303725213,'0'),('4209647b583f436baee1e9fafe0589f1',1303611460,'77,70,69,26,25'),('e23ee2bfb556bdca2304590d0ce3bc16',1303765629,'77,76,75,74,73'),('478379d9c187605ad9f3c8752af08a8b',1303585528,'77,70,69,26,25,24,22,21,20,19,18,17,16,15,14,13,11,1'),('76cb029367b32782db91406f68515b41',1303706344,'77,76,75,74,73'),('0c85af93893aea8ff371902d2d1caabe',1303421566,'42,37,3'),('75a02ca7676335bc54b54bfddb16b6fd',1303421566,'42,37,3'),('b4b5b6d23c81bdb03098beb17b71dba3',1303421566,'5,4'),('6e148f857558dda02b9c17e4bac7cf57',1303494348,'44,43'),('f3719772e0679c396e0768cdc584a128',1303494348,'44,43'),('7a76664cebbef9e45df8ef745a396d95',1303494348,'0'),('30ba24f83cd3485412736d8e09290428',1303702060,'41,40,39'),('8d862374998a198ac7bf558549e5a939',1303702060,'41,40,39'),('14c1942f0cfbf56cafbfa7b3569692e4',1303702060,'0'),('b027aa940e8530451a743e3bda13fb52',1303748000,'36,34,33,31'),('2f1d516c064655411bfc05daeceb52a6',1303748000,'36,35,34,33,32,31'),('ac1c197d2c003985e940e9a687d8de3f',1303748000,'0'),('473c1ff2a98c6e986c9e6623465151c2',1303547633,'46,45'),('472206156391536e1af11300ee1635de',1303547633,'46,45'),('ea351ecb6e47ce3eb9617615b18011ff',1303547633,'0'),('777839e6fe3019a7d061474dad7b2cfa',1303213138,'76,75,74,73,72'),('556234fe729ca06eccf2eca98ebfb442',1303633630,'55,54,53,52'),('a245dd50ab8cff71889cf13cda817278',1303633630,'55,54,53,52'),('6b1c775aa49c4db281016e416b5f9274',1303633630,'0'),('07a6b538d8e0ad6dbd6257d454c180ac',1303403028,'76,75,74,73,72,71,68,67,66,65,63,62,61,60,59,58,57,56'),('194f9c9672f211b7d96963d4aeaa0d30',1303706344,'28'),('eab21893e56c95ced2269c5afa99358f',1303765629,'28');
/*!40000 ALTER TABLE `dede_arccache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_archives`
--

DROP TABLE IF EXISTS `dede_archives`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_archives` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `typeid2` varchar(90) NOT NULL default '0',
  `sortrank` int(10) unsigned NOT NULL default '0',
  `flag` set('c','h','p','f','s','j','a','b') default NULL,
  `ismake` smallint(6) NOT NULL default '0',
  `channel` smallint(6) NOT NULL default '1',
  `arcrank` smallint(6) NOT NULL default '0',
  `click` mediumint(8) unsigned NOT NULL default '0',
  `money` smallint(6) NOT NULL default '0',
  `title` char(60) NOT NULL default '',
  `shorttitle` char(36) NOT NULL default '',
  `color` char(7) NOT NULL default '',
  `writer` char(20) NOT NULL default '',
  `source` char(30) NOT NULL default '',
  `litpic` char(100) NOT NULL default '',
  `pubdate` int(10) unsigned NOT NULL default '0',
  `senddate` int(10) unsigned NOT NULL default '0',
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `keywords` char(30) NOT NULL default '',
  `lastpost` int(10) unsigned NOT NULL default '0',
  `scores` mediumint(8) NOT NULL default '0',
  `goodpost` mediumint(8) unsigned NOT NULL default '0',
  `badpost` mediumint(8) unsigned NOT NULL default '0',
  `notpost` tinyint(1) unsigned NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `filename` varchar(40) NOT NULL default '',
  `dutyadmin` mediumint(8) unsigned NOT NULL default '0',
  `tackid` int(10) NOT NULL default '0',
  `mtype` mediumint(8) unsigned NOT NULL default '0',
  `weight` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `sortrank` (`sortrank`),
  KEY `mainindex` (`arcrank`,`typeid`,`channel`,`flag`,`mid`),
  KEY `lastpost` (`lastpost`,`scores`,`goodpost`,`badpost`,`notpost`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_archives`
--

LOCK TABLES `dede_archives` WRITE;
/*!40000 ALTER TABLE `dede_archives` DISABLE KEYS */;
INSERT INTO `dede_archives` VALUES (1,21,'0',1291862840,'p',-1,1,0,114,0,'旺·吉祥美国行','','','admin','未知','/uploads/101210/1-101210115A11A.jpg',1291862840,1291862875,1,'行,美国,吉祥,旺,',0,0,0,0,0,'旺吉祥太贪玩啦在韩国和日本真的玩得流连忘返，都舍不得走了。 不过。我要环游世界九十天，所以要加快脚步啦！ 这回呀，吉祥还要去次美国。 关心我的朋友们应该知道，我去过次美国。不过那次去的都是纽约啊华盛顿啊这些大城市。这次，我换上牛仔服，去美国的','',1,0,0,0),(2,30,'0',1292297824,'p',-1,1,0,145,0,'最热商品','','','admin','未知','/uploads/101214/1-101214121U3F0.png',1292297824,1292297926,1,'商品,最热,',0,0,0,0,0,'','',1,0,0,1),(3,22,'0',1292314665,'c,p',1,1,0,146,0,'旺吉祥公仔（吉祥款）：进口绒布制作，内含高性能竹炭。','￥商城价：89.00元','','admin','未知','/uploads/101214/1-101214163112312.jpg',1292314665,1292315334,1,'吉祥,内含,高性能,竹炭,制作,进口,公仔,款,旺,绒布,',0,0,0,0,0,'http://www.taobao.com','',1,0,0,2),(4,22,'0',1292567957,'p,a',-1,1,0,129,0,'特价商品1','','','admin','未知','/uploads/allimg/101217/1-10121G439520-L.jpg',1292567957,1292568009,1,'1,商品,特价,',0,0,0,0,0,'http://www.taobao.com','',1,0,0,3),(5,22,'0',1292568015,'p,a',-1,1,0,190,0,'特价商品2','','','admin','未知','/uploads/allimg/101217/1-10121G440240-L.jpg',1292568015,1292568034,1,'2,商品,特价,',0,0,0,0,0,'http://www.taobao.com','',1,0,0,4),(6,22,'0',1292568427,'h,p',-1,1,0,161,0,'商城首页头条1','','','admin','未知','/uploads/allimg/101217/1-10121G44I70-L.jpg',1292568427,1292568470,1,'1,头条,首页,商城,',0,0,0,0,0,'http://www.taobao.com','',1,0,0,5),(7,22,'0',1292568609,'h,p',-1,1,0,74,0,'商城头条2','','','admin','未知','/uploads/allimg/101217/1-10121G450180-L.jpg',1292568609,1292568632,1,'2,头条,商城,',0,0,0,0,0,'http://www.taobao.com','',1,0,0,6),(8,33,'0',1293166050,'p',1,1,0,69,0,'人气商品','','','admin','未知','/uploads/allimg/101224/1-101224124P40-L.jpg',1293166050,1293166098,1,'商品,人气,',0,0,0,0,0,'','',1,0,0,7),(9,33,'0',1293166102,'p',1,1,0,108,0,'家族成员','','','admin','未知','/uploads/allimg/101224/1-101224124T30-L.jpg',1293166102,1293166132,1,'成员,家族,',0,0,0,0,0,'','',1,0,0,8),(10,33,'0',1293166134,'p',1,1,0,189,0,'精品造型','','','admin','未知','/uploads/allimg/101224/1-101224124Z60-L.jpg',1293166134,1293166151,1,'造型,精品,',0,0,0,0,0,'','',1,0,0,9),(11,21,'0',1294627388,'p',1,1,0,161,0,'旺吉祥72变导游','','','admin','未知','/uploads/110110/1-110110123Z0100.jpg',1294627388,1294627590,1,'祥,',0,0,0,0,0,'话说，最近朋友们都在谈论元旦假期里去了哪些好玩的地方，当然也有朋友在考虑即将来临的春节长假打算去哪儿。不知道朋友们都打算怎么出游呢？是跟旅游团还是自助游呢？我想了想啊，不管用哪种方式，总得有个相当于领队的角色吧？嘿嘿，其实呢，导游这个职业','',1,0,0,0),(12,21,'0',1294642410,'p',-1,1,-2,90,0,'新年就要来咯！','','','admin','未知','/uploads/110110/1-110110153H2M2.jpg',1294642410,1294644664,1,'咯,新年,',0,0,0,0,0,'新年就要来咯！ 今天是31号了，大家是不是准备和朋友们一起庆祝和迎接即将来到的新年呢？如果不是的话，那我们是不是也该给亲朋好友打个电话送出自己的祝福呢？好了，那就由我现在带个头吧，在这里我要祝福大家新年快乐，吉祥！ 嗯！就这样吧！我先带着我的','',1,0,0,11),(13,21,'0',1294645558,'p',-1,1,0,194,0,'新年就要来咯！','','','admin','未知','/uploads/110118/1-11011P952442F.jpg',1294645558,1294645712,1,'咯,新年,',0,0,0,0,0,'新年就要来咯！ 今天是31号了，大家是不是准备和朋友们一起庆祝和迎接即将来到的新年呢？如果不是的话，那我们是不是也该给亲朋好友打个电话送出自己的祝福呢？好了，那就由我现在带个头吧，在这里我要祝福大家新年快乐，吉祥！ 嗯！就这样吧！我先带着我的','',1,0,0,12),(14,21,'0',1294712794,'p',1,1,0,189,0,'旺吉祥72变厨师','','','admin','未知','/uploads/110111/1-110111103520F2.jpg',1294712794,1294713025,1,'祥,',0,0,0,0,0,'又是一个星期天，到中午喽！好饿哦 ~~~ 10 分钟前妈妈打电话回来说，她还在外面办事，一时间回不来呢。 唉。。。咋办捏？ 我想着想着，走到了厨房。 对了！我自己来做饭！ 一来我还能实践实践做饭做菜，二来等到妈妈回来了，看到我做的饭菜以后，她一定会很','',1,0,0,0),(15,21,'0',1294713721,'p',1,1,0,198,0,'旺吉祥72变导演','','','admin','未知','/uploads/110111/1-110111104531150.jpg',1294713721,1294713842,1,'祥,',0,0,0,0,0,'今天我问如意： 你的梦想是什么呀？ 如意笑眯眯地告诉我： 我这么漂亮，当然是当明星咯 ~ ，你呢？ 我想了一会儿，说： 既然你相当明星，那我就做导演给你拍电影电视剧啥的好了！ 此时此刻，我脑海中浮现出我是个导演的画面。 灯光，道具，演员准备！ 3 ！ 2','',1,0,0,0),(16,21,'0',1294713988,'p',1,1,0,130,0,'旺吉祥72变服务员','','','admin','未知','/uploads/110111/1-110111104Q2555.jpg',1294713988,1294714043,1,'员,祥,',0,0,0,0,0,'今天我约了如意一起吃午饭。 看时间快到中午，她该来了吧，我要给她一个意外和惊喜，是什么呢？嘻嘻。 看，我像变魔术一样转身换了套餐厅服务员的制服，帅不帅啊？恩，自己照了照镜子，感觉还是蛮不错的，心，砰砰跳，但还故作镇定，趁如意还没到，我先练习','',1,0,0,0),(17,21,'0',1294714118,'p',1,1,0,78,0,'旺吉祥72变花匠','','','admin','未知','/uploads/110111/1-110111105042Q1.jpg',1294714118,1294714208,1,'花,祥,',0,0,0,0,0,'今天的天气好好哦，正好赶上放假，做些什么让自己快乐的事呢？玩？当然没多大意思，对了！种花！ 这段时间妈妈因为太忙了，没什么时间打理她的花园，那么我来帮她吧。 我穿上围裙，拿起水壶，忙得不亦乐乎，原来打理花园这么辛苦哦 晚上妈妈回来后，看到我的','',1,0,0,0),(18,21,'0',1294714262,'p',1,1,0,97,0,'旺吉祥72变化学药剂师','','','admin','未知','/uploads/110111/1-11011110540H11.jpg',1294714262,1294714394,1,'剂,学药剂,祥,',0,0,0,0,0,'期末摸底考试结果下来了，全班同学都考得不好。 唉 大家都挺后悔的，轻敌了嘛 老师安慰我们说：别后悔啦，天底下没有后悔药的。这次只不过是摸底考试而已，吸取教训，下次在正式考中取得好成绩就行了 ! 我旺吉祥想啊，是啊，没有后悔药的，别想啦 等等，后悔','',1,0,0,0),(19,21,'0',1294714477,'p',1,1,0,200,0,'旺吉祥72变画家','','','admin','未知','/uploads/110111/1-110111105623931.jpg',1294714477,1294714546,1,'家,祥,',0,0,0,0,0,'今天天气真好，太阳高高照，小鸟在天空中自由的飞翔。 我呢？嘿嘿，当然是搬着我的工具到大自然中写生！ 我今天非常高兴因为如意也陪我去呢！ 嗯，我决定一会儿画一张画给她！ 你看，我又是铅笔打草稿，然后用颜料调色，再上色。 是不是很专业呢？ What a go','',1,0,0,0),(20,21,'0',1294714632,'p',1,1,0,106,0,'旺吉祥72变街头舞者','','','admin','未知','/uploads/110111/1-1101111101455E.jpg',1294714632,1294714734,1,'街,祥,',0,0,0,0,0,'这两天如意不理我了 她说我这段时间都不哄她开心。 唉 我也没办法呀 这几个礼拜学校又是是音乐节，家里又要帮着妈妈做家务，好忙啊！ 一时间疏忽了如意，怎么办呢？ 哦对了！我去学跳街舞！又酷又有型！ 说不定如意看了会很开心呢！ Ru-yi doesnt want to ta','',1,0,0,0),(21,21,'0',1294714923,'p',1,1,0,81,0,'旺吉祥72变教师','','','admin','未知','/uploads/110111/1-110111110426407.jpg',1294714923,1294715029,1,'教,祥,',0,0,0,0,0,'今天是我第一次当小老师哦！ 当老师是我梦寐以求的愿望，可是我没经验啊，心里不免紧张起来。 妈妈昨晚对我说： 吉祥啊，明天不要紧张哦 ~ 做任何事都有第一次， 万事开头难 嘛！ 我想，我要对自己有信心，要抓住这次机会，试试看！ 想到这里，我也心里也就','',1,0,0,0),(22,21,'0',1294715084,'p',1,1,0,183,0,'旺吉祥72变司机','','','admin','未知','/uploads/110111/1-110111110A9251.jpg',1294715084,1294715185,1,'祥,',0,0,0,0,0,'圣诞节过去了，元旦即将来临。 我呢，想带如意去海边看看风景。 可是这么远，公交车也没有到海边的，怎么办呢？ 前两天我试探如意，她看上去还是非常想去的，难道就没有什么更好的办法了么？ 我想想 对啊，开车去不就搞定了嘛！真是个好办法，到时候我开着车','',1,0,0,0),(24,21,'0',1294715411,'p',1,1,0,151,0,'旺吉祥72变特种兵','','','admin','未知','/uploads/110111/1-110111111150F1.jpg',1294715411,1294715480,1,'兵,特种,祥,',0,0,0,0,0,'今天上语文课，老师让我们写作文，题目是《我的梦想》。 哎呀 平时聊天啊，谈话的时候，不管什么我的能夸夸其谈，怎么今天这作文就难倒我了呢 想着想着，我就睡着了 我梦见我身穿身穿迷彩，头带钢盔，手持 AK-47 ，整个一又帅又酷的特种兵战士啊！ 等等 难道','',1,0,0,0),(25,21,'0',1294715528,'p',1,1,0,86,0,'旺吉祥72变医生','','','admin','未知','/uploads/110111/1-110111111400249.jpg',1294715528,1294715609,1,'祥,',0,0,0,0,0,'今天在学校，我们今天要学习医疗知识，好兴奋啊！ 以 后要是碰到什么不舒服的情况，我吉祥也能自己解决咯！妈妈一定会很高兴的！ 老师说，要在小朋友中间选一个来扮演医生。 于是乎，我自告奋勇，第一个举起了手。 嘿嘿 ~ 我穿上了老师带来的小号白大褂，戴','',1,0,0,0),(26,21,'0',1294715658,'p,a',1,1,0,68,0,'旺吉祥72变音乐家','','','admin','未知','/uploads/110111/1-11011111162Y40.jpg',1294715658,1294715747,1,'乐家,祥,',0,0,0,0,0,'学校要举行艺术节喽！ 老师说，我们小班也得准备个节目。 我旺吉祥想啊想，那就搞个音乐会吧。 嗯！就这么愉快的决定了！老师也同意了我的想法。 当确定好所有人的位置以后，咦，竟发现少了个指挥。于是啊，我就推荐自己说，那我来做指挥吧！ 嘿嘿，又能出风','',1,0,0,0),(27,20,'0',1295314310,'p',1,1,-2,94,0,'菜包记','','','admin','未知','/uploads/110118/1-11011P93513335.jpg',1295314310,1295314567,1,'记,',0,0,0,0,0,'','',1,0,0,25),(28,20,'0',1295315672,'p',-1,1,0,163,0,'菜包记','','','admin','未知','/uploads/110118/1-11011P95F3613.jpg',1295315672,1295315884,1,'记,',0,0,0,0,0,'','',1,0,0,26),(29,23,'0',1295332333,'c',1,1,-2,110,0,'旺吉祥马克杯','￥商城价：19.00元','','admin','未知','',1295332333,1295332518,1,'杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,27),(30,23,'0',1295332333,'c',1,1,-2,110,0,'旺吉祥马克杯','￥商城价：19.00元','','admin','未知','',1295332333,1295332539,1,'杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,27),(31,23,'0',1295332831,'c,p',1,1,0,152,0,'旺吉祥马克杯（爱心款）','￥商城价：19.00元','','admin','未知','/uploads/110118/110118/1-11011Q44145O5.jpg',1295332831,1295332984,1,'杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,29),(32,23,'0',1295333085,'p',1,1,0,78,0,'旺吉祥马克杯（捉迷藏）','￥商城价：19.00元','','admin','未知','/uploads/110118/1-11011Q44615N6.jpg',1295333085,1295333245,1,'杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,30),(33,23,'0',1295333322,'c,p',1,1,0,51,0,'旺吉祥马克杯（糖葫芦）','￥商城价：19.00元','','admin','未知','/uploads/110118/1-11011Q4491I14.jpg',1295333322,1295333392,1,'糖葫,杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,31),(34,23,'0',1295333454,'c,p',1,1,0,93,0,'旺吉祥马克杯（喝酸奶）','￥商城价：19.00元','','admin','未知','/uploads/110118/1-11011Q4512b02.jpg',1295333454,1295333513,1,'奶,喝酸,杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,32),(35,23,'0',1295333516,'p',1,1,0,69,0,'旺吉祥马克杯（放学）','￥商城价：19.00元','','admin','未知','/uploads/110118/1-11011Q45222E1.jpg',1295333516,1295333558,1,'杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,33),(36,23,'0',1295333563,'c,p',1,1,0,54,0,'旺吉祥马克杯（捉蝴蝶）','￥商城价：19.00元','','admin','未知','/uploads/110118/1-11011Q4531M59.jpg',1295333563,1295333607,1,'蝶,捉蝴,杯,克,祥马克,',0,0,0,0,0,'','',1,0,0,34),(37,22,'0',1295333781,'c,p',1,1,0,113,0,'旺吉祥公仔（时尚款）：进口绒布制作，内含高性能竹炭。','￥商城价：89.00元','','admin','未知','/uploads/110118/1-11011Q45I0b2.jpg',1295333781,1295333869,1,'内,竹炭,作,绒,仔,祥公,',0,0,0,0,0,'','',1,0,0,35),(38,33,'0',1295336133,'p',1,1,0,111,0,'中部banner','','','admin','未知','/uploads/110119/1-11011910215DM.png',1295336133,1295336170,1,'er,nn,ba,',0,0,0,0,0,'http://www.wangjixiang.com/plus/list.php?tid=16','',1,0,0,36),(39,24,'0',1295337724,'c,p',1,1,0,91,0,'旺吉祥纸杯（拜年款）','￥商城价：25.00元/50只','','admin','未知','/uploads/110118/1-11011Q60246415.jpg',1295337724,1295337837,1,'年,杯,祥纸杯,',0,0,0,0,0,'','',1,0,0,37),(40,24,'0',1295337841,'c,p',1,1,0,71,0,'旺吉祥纸杯（运动款）','￥商城价：25.00元/50只','','admin','未知','/uploads/110118/1-11011Q60440Y3.jpg',1295337841,1295337897,1,'运,杯,祥纸杯,',0,0,0,0,0,'','',1,0,0,38),(41,24,'0',1295337982,'c,p',1,1,0,125,0,'旺吉祥纸杯（经典款）','￥商城价：25.00元/50只','','admin','未知','/uploads/110118/1-11011Q60ADW.jpg',1295337982,1295338030,1,'经典,杯,祥纸杯,',0,0,0,0,0,'','',1,0,0,39),(42,22,'0',1295338155,'c,p',1,1,0,193,0,'旺吉祥公仔（组合款）：进口绒布制作，内含高性能竹炭。','￥商城价：178.00元','','admin','未知','/uploads/110118/1-11011Q610033b.jpg',1295338155,1295338215,1,'内,竹炭,作,绒,仔,组,祥公,',0,0,0,0,0,'','',1,0,0,40),(43,25,'0',1295338311,'c,p',1,1,0,131,0,'旺吉祥杯垫（经典套）','￥商城价：10.00元/套','','admin','未知','/uploads/110118/1-11011Q6124V07.jpg',1295338311,1295338393,1,'典套,祥杯,',0,0,0,0,0,'','',1,0,0,41),(44,25,'0',1295338396,'c,p',1,1,0,132,0,'旺吉祥杯垫（第二套）','￥商城价：10.00元/套','','admin','未知','/uploads/110118/1-11011Q61431446.jpg',1295338396,1295338484,1,'二套,祥杯,',0,0,0,0,0,'','',1,0,0,42),(45,27,'0',1295338542,'c,p',1,1,0,60,0,'旺吉祥包袋','￥商城价：6.00元','','admin','未知','/uploads/110119/1-11011Z94K5F5.jpg',1295338542,1295338669,1,'袋,祥,',0,0,0,0,0,'','',1,0,0,43),(46,27,'0',1295338730,'c,p',1,1,0,177,0,'旺吉祥包袋 反面','￥商城价：6.00元','','admin','未知','/uploads/110118/1-11011Q619139D.jpg',1295338730,1295338784,1,'反面,袋,祥,',0,0,0,0,0,'','',1,0,0,44),(47,28,'0',1295338804,'c,p',1,1,0,75,0,'旺吉祥明信片（职业装）','￥商城价：5元/张','','admin','未知','/uploads/110118/1-11011Q62122I4.jpg',1295338804,1295338905,1,'装,职,片,祥,',0,0,0,0,0,'','',1,0,0,45),(48,28,'0',1295338907,'c,p',1,1,0,179,0,'旺吉祥明信片（金色全家福）','￥商城价：5元/张','','admin','未知','/uploads/110118/1-11011Q62242V2.jpg',1295338907,1295338990,1,'福,家,金色全,片,祥,',0,0,0,0,0,'','',1,0,0,46),(49,28,'0',1295338993,'c,p',1,1,0,125,0,'旺吉祥明信片（可爱小屋）','￥商城价：5元/张','','admin','未知','/uploads/110118/1-11011Q62343101.jpg',1295338993,1295339065,1,'屋,片,祥,',0,0,0,0,0,'','',1,0,0,47),(50,28,'0',1295339068,'c,p',1,1,0,159,0,'旺吉祥明信片（马戏团）','￥商城价：5元/张','','admin','未知','/uploads/110118/1-11011Q62449510.jpg',1295339068,1295339106,1,'马,片,祥,',0,0,0,0,0,'','',1,0,0,48),(51,28,'0',1295339108,'p',1,1,0,66,0,'旺吉祥明信片（喝酸奶）','￥商城价：5元/张','','admin','未知','/uploads/110118/1-11011Q6253T59.jpg',1295339108,1295339147,1,'奶,喝酸,片,祥,',0,0,0,0,0,'','',1,0,0,49),(52,29,'0',1295339216,'c,p',1,1,0,200,0,'旺吉祥锅垫 效果图 1','设计制作','','admin','未知','/uploads/110118/1-11011Q62K5Q4.jpg',1295339216,1295339288,1,'1,效果,祥,',0,0,0,0,0,'','',1,0,0,50),(53,29,'0',1295339291,'c,p',1,1,0,86,0,'旺吉祥锅垫 效果图 2','设计制作','','admin','未知','/uploads/110118/1-11011Q62T2496.jpg',1295339291,1295339332,1,'效果,祥,',0,0,0,0,0,'','',1,0,0,51),(54,29,'0',1295339335,'c,p',1,1,0,162,0,'旺吉祥锅垫 效果图 3','设计制作','','admin','未知','/uploads/110118/1-11011Q62932X5.jpg',1295339335,1295339386,1,'效果,祥,',0,0,0,0,0,'','',1,0,0,52),(55,29,'0',1295339388,'c,p',1,1,0,153,0,'旺吉祥锅垫 效果图 4','设计制作','','admin','未知','/uploads/110118/1-11011Q63022Z8.jpg',1295339388,1295339431,1,'效果,祥,',0,0,0,0,0,'','',1,0,0,53),(56,19,'0',1295340357,'p',1,1,0,185,0,'吉祥十二生肖-鼠','','','admin','未知','/uploads/110118/1-11011Q64645413.jpg',1295340357,1295340418,1,'肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,54),(57,19,'0',1295341087,'p',1,1,0,199,0,'吉祥十二生肖-牛','','','admin','未知','/uploads/110118/1-11011QA9304R.jpg',1295341087,1295341179,1,'牛,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,55),(58,19,'0',1295341291,'p',1,1,0,70,0,'吉祥十二生肖-虎','','','admin','未知','/uploads/110118/1-11011QF200F3.jpg',1295341291,1295341328,1,'虎,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,56),(59,19,'0',1295341330,'p',1,1,0,166,0,'吉祥十二生肖-兔','','','admin','未知','/uploads/110118/1-11011QF2302Z.jpg',1295341330,1295341402,1,'兔,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,57),(60,19,'0',1295341404,'p',1,1,0,182,0,'吉祥十二生肖-龙','','','admin','未知','/uploads/110118/1-11011QF3451S.jpg',1295341404,1295341430,1,'龙,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,58),(61,19,'0',1295341432,'p',1,1,0,156,0,'吉祥十二生肖-蛇','','','admin','未知','/uploads/110118/1-11011QF412R7.jpg',1295341432,1295341458,1,'蛇,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,59),(62,19,'0',1295341493,'p',1,1,0,130,0,'吉祥十二生肖-马','','','admin','未知','/uploads/110118/1-11011QF514354.jpg',1295341493,1295341534,1,'马,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,60),(63,19,'0',1295341537,'p',1,1,0,161,0,'吉祥十二生肖-羊','','','admin','未知','/uploads/110118/1-11011QF644T0.jpg',1295341537,1295341611,1,'羊,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,61),(64,19,'0',1295341613,'p',1,1,-2,85,0,'旺吉祥十二生肖-猴','','','admin','未知','/uploads/110118/1-11011QFG5635.jpg',1295341613,1295341644,1,'肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,62),(65,19,'0',1295341646,'p',1,1,0,128,0,'吉祥十二生肖-鸡','','','admin','未知','/uploads/110118/1-11011QFPH27.jpg',1295341646,1295341659,1,'肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,63),(66,19,'0',1295341701,'p',1,1,0,73,0,'吉祥十二生肖-狗','','','admin','未知','/uploads/110118/1-11011QFU2O5.jpg',1295341701,1295341739,1,'狗,肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,64),(67,19,'0',1295341741,'p',1,1,0,145,0,'吉祥十二生肖-猪','','','admin','未知','/uploads/110118/1-11011QF945D8.jpg',1295341741,1295341802,1,'肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,65),(68,19,'0',1295342181,'p',1,1,0,200,0,'吉祥十二生肖-猴','','','admin','未知','/uploads/110118/1-11011QGF1547.jpg',1295342181,1295342235,1,'肖,二,十,祥,',0,0,0,0,0,'','',1,0,0,66),(69,21,'0',1296711677,'p',1,1,0,121,0,'旺吉祥恭祝大家新春快乐！','','','admin','未知','/uploads/110203/1-110203134R1O6.jpg',1296711677,1296712195,1,'乐,家新,祝,祥恭,',0,0,0,0,0,'恭祝大家新春快乐！ 今天是大年初一！ 各位朋友过年好！ 感谢大家一年来对我的关注。我旺吉祥真的是激动得无法用言语来表达对各位的感谢！ 不过呢，我想，至少呀，还是得说些什么比较好，毕竟，这是给大家的在新年里的祝福嘛~ 好！在这里，我祝愿大家新的1年','',1,0,0,67),(70,21,'0',1297232488,'p,a',1,1,0,155,0,'开车兜兜风','','','admin','未知','/uploads/110209/1-110209141PK06.jpg',1297232488,1297232549,1,'兜风,车兜,',0,0,0,0,0,'','',1,0,0,68),(71,19,'0',1297666455,'p',1,1,0,51,0,'吉祥乐队-星期一','','','admin','未知','/uploads/110214/1-110214145504559.jpg',1297666455,1297666539,1,'一,他手,OW,SH,一周乐,祥,',0,0,0,0,0,'','',1,0,0,69),(72,19,'0',1297753165,'p',1,1,0,193,0,'吉祥乐队-星期二','','','admin','未知','/uploads/110215/1-110215145Z15T.jpg',1297753165,1297753232,1,'二,祥乐,',0,0,0,0,0,'','',1,0,0,70),(73,19,'0',1297822155,'p',1,1,0,199,0,'吉祥乐队-星期三','','','admin','未知','/uploads/110216/1-11021610093I58.jpg',1297822155,1297822186,1,'祥乐,',0,0,0,0,0,'','',1,0,0,71),(74,19,'0',1298004937,'p',1,1,0,176,0,'吉祥乐队-星期五','','','admin','未知','/uploads/110218/1-11021Q25R63b.jpg',1298004937,1298005112,1,'五,祥乐,',0,0,0,0,0,'','',1,0,0,72),(75,19,'0',1298008367,'p',1,1,0,146,0,'吉祥乐队-星期四','','','admin','未知','/uploads/110218/1-11021Q35315547.jpg',1298008367,1298008416,1,'祥乐,',0,0,0,0,0,'','',1,0,0,73),(76,19,'0',1298030588,'p',1,1,0,196,0,'吉祥乐队-周末狂欢','','','admin','未知','/uploads/110218/1-11021R00340592.jpg',1298030588,1298030626,1,'狂,周,祥乐,',0,0,0,0,0,'','',1,0,0,74),(77,21,'0',1303176875,'c,p,a',1,1,0,114,0,'划船','','','admin','未知','/uploads/110419/1-11041Z93912433.jpg',1303176875,1303176992,1,'',0,0,0,0,0,'','',1,0,0,75);
/*!40000 ALTER TABLE `dede_archives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arcmulti`
--

DROP TABLE IF EXISTS `dede_arcmulti`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arcmulti` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `tagid` char(60) NOT NULL default '',
  `uptime` int(11) NOT NULL default '0',
  `innertext` varchar(255) NOT NULL default '',
  `pagesize` int(11) NOT NULL default '0',
  `arcids` text NOT NULL,
  `ordersql` varchar(255) default '',
  `addfieldsSql` varchar(255) default '',
  `addfieldsSqlJoin` varchar(255) default '',
  `attstr` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arcmulti`
--

LOCK TABLES `dede_arcmulti` WRITE;
/*!40000 ALTER TABLE `dede_arcmulti` DISABLE KEYS */;
INSERT INTO `dede_arcmulti` VALUES (1,'dedecms',1291359176,'<li class=\'dotline\'><a href=\"[field:arcurl/]\">[field:title/]</a></li>',8,'',' order by arc.sortrank desc','','','a:16:{s:3:\"row\";s:2:\"16\";s:8:\"titlelen\";s:2:\"42\";s:7:\"infolen\";i:160;s:8:\"imgwidth\";i:120;s:9:\"imgheight\";i:120;s:8:\"listtype\";s:3:\"all\";s:5:\"arcid\";i:0;s:9:\"channelid\";i:0;s:7:\"orderby\";s:7:\"default\";s:8:\"orderWay\";s:4:\"desc\";s:6:\"subday\";i:0;s:8:\"pagesize\";s:1:\"8\";s:7:\"keyword\";s:0:\"\";s:10:\"tablewidth\";s:4:\"100%\";s:3:\"col\";i:1;s:8:\"colWidth\";s:4:\"100%\";}');
/*!40000 ALTER TABLE `dede_arcmulti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arcrank`
--

DROP TABLE IF EXISTS `dede_arcrank`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arcrank` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `rank` smallint(6) NOT NULL default '0',
  `membername` char(20) NOT NULL default '',
  `adminrank` smallint(6) NOT NULL default '0',
  `money` smallint(8) unsigned NOT NULL default '500',
  `scores` mediumint(8) NOT NULL default '0',
  `purviews` mediumtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arcrank`
--

LOCK TABLES `dede_arcrank` WRITE;
/*!40000 ALTER TABLE `dede_arcrank` DISABLE KEYS */;
INSERT INTO `dede_arcrank` VALUES (1,0,'开放浏览',5,0,0,''),(2,-1,'待审核稿件',0,0,0,''),(3,10,'注册会员',5,0,100,'');
/*!40000 ALTER TABLE `dede_arcrank` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arctiny`
--

DROP TABLE IF EXISTS `dede_arctiny`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arctiny` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `typeid2` varchar(90) NOT NULL default '0',
  `arcrank` smallint(6) NOT NULL default '0',
  `channel` smallint(5) NOT NULL default '1',
  `senddate` int(10) unsigned NOT NULL default '0',
  `sortrank` int(10) unsigned NOT NULL default '0',
  `mid` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `sortrank` (`sortrank`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arctiny`
--

LOCK TABLES `dede_arctiny` WRITE;
/*!40000 ALTER TABLE `dede_arctiny` DISABLE KEYS */;
INSERT INTO `dede_arctiny` VALUES (1,21,'0',0,1,1291862875,1291862840,1),(2,30,'0',0,1,1292297926,1292297824,1),(3,22,'0',0,1,1292315334,1292314665,1),(4,22,'0',0,1,1292568009,1292567957,1),(5,22,'0',0,1,1292568034,1292568015,1),(6,22,'0',0,1,1292568470,1292568427,1),(7,22,'0',0,1,1292568632,1292568609,1),(8,33,'0',0,1,1293166098,1293166050,1),(9,33,'0',0,1,1293166132,1293166102,1),(10,33,'0',0,1,1293166151,1293166134,1),(11,21,'0',0,1,1294627590,1294627388,1),(12,21,'0',-2,1,1294644664,1294642410,1),(13,21,'0',0,1,1294645712,1294645558,1),(14,21,'0',0,1,1294713025,1294712794,1),(15,21,'0',0,1,1294713842,1294713721,1),(16,21,'0',0,1,1294714043,1294713988,1),(17,21,'0',0,1,1294714208,1294714118,1),(18,21,'0',0,1,1294714394,1294714262,1),(19,21,'0',0,1,1294714546,1294714477,1),(20,21,'0',0,1,1294714734,1294714632,1),(21,21,'0',0,1,1294715029,1294714923,1),(22,21,'0',0,1,1294715185,1294715084,1),(24,21,'0',0,1,1294715480,1294715411,1),(25,21,'0',0,1,1294715609,1294715528,1),(26,21,'0',0,1,1294715747,1294715658,1),(27,20,'0',-2,1,1295314567,1295314310,1),(28,20,'0',0,1,1295315884,1295315672,1),(29,23,'0',-2,1,1295332518,1295332333,1),(30,23,'0',-2,1,1295332539,1295332333,1),(31,23,'0',0,1,1295332984,1295332831,1),(32,23,'0',0,1,1295333245,1295333085,1),(33,23,'0',0,1,1295333392,1295333322,1),(34,23,'0',0,1,1295333513,1295333454,1),(35,23,'0',0,1,1295333558,1295333516,1),(36,23,'0',0,1,1295333607,1295333563,1),(37,22,'0',0,1,1295333869,1295333781,1),(38,33,'0',0,1,1295336170,1295336133,1),(39,24,'0',0,1,1295337837,1295337724,1),(40,24,'0',0,1,1295337897,1295337841,1),(41,24,'0',0,1,1295338030,1295337982,1),(42,22,'0',0,1,1295338215,1295338155,1),(43,25,'0',0,1,1295338393,1295338311,1),(44,25,'0',0,1,1295338484,1295338396,1),(45,27,'0',0,1,1295338669,1295338542,1),(46,27,'0',0,1,1295338784,1295338730,1),(47,28,'0',0,1,1295338905,1295338804,1),(48,28,'0',0,1,1295338990,1295338907,1),(49,28,'0',0,1,1295339065,1295338993,1),(50,28,'0',0,1,1295339106,1295339068,1),(51,28,'0',0,1,1295339147,1295339108,1),(52,29,'0',0,1,1295339288,1295339216,1),(53,29,'0',0,1,1295339332,1295339291,1),(54,29,'0',0,1,1295339386,1295339335,1),(55,29,'0',0,1,1295339431,1295339388,1),(56,19,'0',0,1,1295340418,1295340357,1),(57,19,'0',0,1,1295341179,1295341087,1),(58,19,'0',0,1,1295341328,1295341291,1),(59,19,'0',0,1,1295341402,1295341330,1),(60,19,'0',0,1,1295341430,1295341404,1),(61,19,'0',0,1,1295341458,1295341432,1),(62,19,'0',0,1,1295341534,1295341493,1),(63,19,'0',0,1,1295341611,1295341537,1),(64,19,'0',-2,1,1295341644,1295341613,1),(65,19,'0',0,1,1295341659,1295341646,1),(66,19,'0',0,1,1295341739,1295341701,1),(67,19,'0',0,1,1295341802,1295341741,1),(68,19,'0',0,1,1295342235,1295342181,1),(69,21,'0',0,1,1296712195,1296711677,1),(70,21,'0',0,1,1297232549,1297232488,1),(71,19,'0',0,1,1297666539,1297666455,1),(72,19,'0',0,1,1297753232,1297753165,1),(73,19,'0',0,1,1297822186,1297822155,1),(74,19,'0',0,1,1298005112,1298004937,1),(75,19,'0',0,1,1298008416,1298008367,1),(76,19,'0',0,1,1298030626,1298030588,1),(77,21,'0',0,1,1303176992,1303176875,1);
/*!40000 ALTER TABLE `dede_arctiny` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_arctype`
--

DROP TABLE IF EXISTS `dede_arctype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_arctype` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `reid` smallint(5) unsigned NOT NULL default '0',
  `topid` smallint(5) unsigned NOT NULL default '0',
  `sortrank` smallint(5) unsigned NOT NULL default '50',
  `typename` char(30) NOT NULL default '',
  `typedir` char(60) NOT NULL default '',
  `isdefault` smallint(6) NOT NULL default '0',
  `defaultname` char(15) NOT NULL default 'index.html',
  `issend` smallint(6) NOT NULL default '0',
  `channeltype` smallint(6) default '1',
  `maxpage` smallint(6) NOT NULL default '-1',
  `ispart` smallint(6) NOT NULL default '0',
  `corank` smallint(6) NOT NULL default '0',
  `tempindex` char(50) NOT NULL default '',
  `templist` char(50) NOT NULL default '',
  `temparticle` char(50) NOT NULL default '',
  `namerule` char(50) NOT NULL default '',
  `namerule2` char(50) NOT NULL default '',
  `modname` char(20) NOT NULL default '',
  `description` char(150) NOT NULL default '',
  `keywords` varchar(60) NOT NULL default '',
  `seotitle` varchar(80) NOT NULL default '',
  `moresite` tinyint(1) unsigned NOT NULL default '0',
  `sitepath` char(60) NOT NULL default '',
  `siteurl` char(50) NOT NULL default '',
  `ishidden` smallint(6) NOT NULL default '0',
  `cross` tinyint(1) NOT NULL default '0',
  `crossid` text,
  `content` text,
  `smalltypes` text,
  PRIMARY KEY  (`id`),
  KEY `reid` (`reid`,`isdefault`,`channeltype`,`ispart`,`corank`,`topid`,`ishidden`),
  KEY `sortrank` (`sortrank`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_arctype`
--

LOCK TABLES `dede_arctype` WRITE;
/*!40000 ALTER TABLE `dede_arctype` DISABLE KEYS */;
INSERT INTO `dede_arctype` VALUES (1,0,0,999,'在线留言','http://localhost/plus/guestbook.php',1,'guestbook.php',0,1,-1,2,0,'','','','','','default','','','',0,'http://localhost/plus/guestbook.php','',1,0,'0','',''),(2,0,0,50,'吉祥商城','{cmspath}/a/jixiangshangcheng',-1,'index.html',0,1,-1,1,0,'{style}/shangcheng1.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(3,0,0,50,'吉祥故事','{cmspath}/a/jixianggushi',-1,'index.html',0,1,-1,1,0,'{style}/jixianggushi1.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(4,0,0,50,'吉祥家族','{cmspath}/a/jixiangjiazu',-1,'index.html',1,1,-1,1,0,'{style}/jiazu.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','',''),(5,0,0,50,'联系我们','{cmspath}/a/lianxiwomen',-1,'index.html',0,1,-1,1,0,'{style}/contact.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/lianxiwomen','',0,0,'','<ul>\r\n    <li>公司地址</li>\r\n    <li>地址：上海市零陵路585号爱邦大厦26楼E座</li>\r\n    <li>电话：86-21-64396277（总机）</li>\r\n    <li style=\"padding-left: 36px\">86-21-64399255（直线）</li>\r\n    <li>传真：86-21-64399255</li>\r\n    <li>邮箱：wenxindesign@126.com</li>\r\n</ul>',''),(6,4,4,50,'旺吉祥','{cmspath}/a/jixiangjiazu/wangjixiang',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<h1><img style=\"cursor: pointer\" border=\"0\" alt=\"\" width=\"881\" height=\"471\" onclick=\"window.open(\'/uploads/allimg/110111/1_110111141642_1.png\')\" src=\"http://www.wangjixiang.com/uploads/allimg/110111/1_110111141642_1.png\" /></h1>',''),(7,4,4,50,'木呆呆','{cmspath}/a/jixiangjiazu/mudaidai',1,'index.html',1,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<p><img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110117/1_110117135205_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110117/1_110117135205_1.png\" width=\"881\" height=\"444\" /></p>',''),(8,4,4,50,'肥牛','{cmspath}/a/jixiangjiazu/feiniu',1,'index.html',1,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div><br />\r\n&nbsp;</div>\r\n<p>&nbsp;<img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110117/1_110117135313_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110117/1_110117135313_1.png\" width=\"881\" height=\"448\" /></p>',''),(9,4,4,50,'啡啡','{cmspath}/a/jixiangjiazu/feifei',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<p><br />\r\n&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;<img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110111/1_110111154518_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110111/1_110111154518_1.png\" width=\"881\" height=\"487\" /></p>',''),(10,4,4,50,'鼻涕虫','{cmspath}/a/jixiangjiazu/bitichong',1,'index.html',1,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<div><img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110117/1_110117135115_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110117/1_110117135115_1.png\" width=\"881\" height=\"523\" /><br />\r\n&nbsp;</div>\r\n<p>&nbsp;</p>',''),(11,4,4,50,'玉如意','{cmspath}/a/jixiangjiazu/yuruyi',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<div><img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110111/1_110111173510_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110111/1_110111173510_1.png\" width=\"881\" height=\"458\" /><br />\r\n&nbsp;</div>\r\n<p>&nbsp;</p>',''),(12,4,4,50,'顿牛','{cmspath}/a/jixiangjiazu/dunniu',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<div><img style=\"cursor: pointer\" border=\"0\" alt=\"\" width=\"881\" height=\"466\" onclick=\"window.open(\'/uploads/allimg/110111/1_110111143940_1.png\')\" src=\"http://www.wangjixiang.com/uploads/allimg/110111/1_110111143940_1.png\" /><br />\r\n&nbsp;</div>',''),(13,4,4,50,'粘宝宝','{cmspath}/a/jixiangjiazu/zhanbaobao',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<div><img width=\"882\" height=\"560\" border=\"0\" alt=\"\" src=\"/uploads/allimg/101208/1_101208170341_1.jpg\" /></div>\r\n<p>&nbsp;</p>',''),(14,4,4,50,'呗呗','{cmspath}/a/jixiangjiazu/beibei',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<div><img style=\"cursor: pointer\" onclick=\"window.open(\'/uploads/allimg/110111/1_110111150750_1.png\')\" border=\"0\" alt=\"\" src=\"http://www.wangjixiang.com/uploads/allimg/110111/1_110111150750_1.png\" width=\"881\" height=\"473\" /></div>',''),(15,4,4,50,'鼻涕弟弟','{cmspath}/a/jixiangjiazu/bitididi',1,'index.html',0,1,-1,1,0,'{style}/jiazu_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangjiazu','',0,0,'','<div><br />\r\n&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<p>&nbsp;<img style=\"cursor: pointer\" border=\"0\" alt=\"\" width=\"881\" height=\"444\" onclick=\"window.open(\'/uploads/allimg/110118/1_110118111814_1.png\')\" src=\"http://www.wangjixiang.com/uploads/allimg/110118/1_110118111814_1.png\" /></p>',''),(16,3,3,50,'吉祥SHOW','{cmspath}/a/jixianggushi/jixiangSHOW',1,'index.html',1,1,-1,1,0,'{style}/jixianggushi2.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(17,3,3,50,'吉祥乐闻','{cmspath}/a/jixianggushi/jixianglewen',-1,'index.html',1,1,-1,1,0,'{style}/jixianggushi2.htm','{style}/list_article.htm','{style}/jixianggushi3.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(18,3,3,50,'旺旺三格半','{cmspath}/a/jixianggushi/wangwangsangeban',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/jixianggushi2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(19,16,3,50,'故事列表','{cmspath}/a/jixianggushi/jixiangSHOW/gushiliebiao',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/jixianggushi3.htm','{style}/jixianggushi4.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(20,18,3,50,'故事列表','{cmspath}/a/jixianggushi/wangwangsangeban/gushiliebiao',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/jixianggushi3.htm','{style}/jixianggushi4.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(21,17,3,50,'故事列表','{cmspath}/a/jixianggushi/jixianglewen/gushiliebiao',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/jixianggushi3.htm','{style}/jixianggushi4.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixianggushi','',0,0,'','',''),(22,2,2,50,'毛绒公仔','{cmspath}/a/jixiangshangcheng/maoronggongzi',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(23,2,2,50,'马克杯','{cmspath}/a/jixiangshangcheng/makebei',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(24,2,2,50,'一次性纸杯','{cmspath}/a/jixiangshangcheng/yicixingzhibei',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(25,2,2,50,'杯垫系列','{cmspath}/a/jixiangshangcheng/beidianxilie',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(26,2,2,50,'竹炭系列','{cmspath}/a/jixiangshangcheng/zhutanxilie',1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(27,2,2,50,'包袋系列','{cmspath}/a/jixiangshangcheng/baodaixilie',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(28,2,2,50,'明信片系列','{cmspath}/a/jixiangshangcheng/zhijinxilie',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(29,2,2,50,'特种订购','{cmspath}/a/jixiangshangcheng/tezhongdinggou',-1,'index.html',1,1,-1,0,0,'{style}/index_article.htm','{style}/shangcheng2.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/jixiangshangcheng','',0,0,'','',''),(30,0,0,50,'首页','{cmspath}/a/shouye',1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/shouye','',0,0,'','',''),(33,0,0,50,'首页图片','{cmspath}/a/shouyetupian',-1,'index.html',0,1,-1,0,0,'{style}/index_article.htm','{style}/list_article.htm','{style}/article_article.htm','{typedir}/{Y}/{M}{D}/{aid}.html','{typedir}/list_{tid}_{page}.html','default','','','',0,'{cmspath}/a/shouyetupian','',1,0,'','','');
/*!40000 ALTER TABLE `dede_arctype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_area`
--

DROP TABLE IF EXISTS `dede_area`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_area` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `reid` int(10) unsigned NOT NULL default '0',
  `disorder` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3118 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_area`
--

LOCK TABLES `dede_area` WRITE;
/*!40000 ALTER TABLE `dede_area` DISABLE KEYS */;
INSERT INTO `dede_area` VALUES (1,'北京市',0,0),(102,'西城区',1,2),(126,'崇文区',1,0),(104,'宣武区',1,0),(105,'朝阳区',1,0),(106,'海淀区',1,0),(107,'丰台区',1,0),(108,'石景山区',1,0),(109,'门头沟区',1,0),(110,'房山区',1,0),(111,'通州区',1,0),(112,'顺义区',1,0),(113,'昌平区',1,0),(114,'大兴区',1,0),(115,'平谷县',1,0),(116,'怀柔县',1,0),(117,'密云县',1,0),(118,'延庆县',1,0),(2,'上海市',0,0),(201,'黄浦区',2,0),(202,'卢湾区',2,0),(203,'徐汇区',2,0),(204,'长宁区',2,0),(205,'静安区',2,0),(206,'普陀区',2,0),(207,'闸北区',2,0),(208,'虹口区',2,0),(209,'杨浦区',2,0),(210,'宝山区',2,0),(211,'闵行区',2,0),(212,'嘉定区',2,0),(213,'浦东新区',2,0),(214,'松江区',2,0),(215,'金山区',2,0),(216,'青浦区',2,0),(217,'南汇区',2,0),(218,'奉贤区',2,0),(219,'崇明县',2,0),(3,'天津市',0,0),(301,'和平区',3,0),(302,'河东区',3,0),(303,'河西区',3,0),(304,'南开区',3,0),(305,'河北区',3,0),(306,'红桥区',3,0),(307,'塘沽区',3,0),(308,'汉沽区',3,0),(309,'大港区',3,0),(310,'东丽区',3,0),(311,'西青区',3,0),(312,'北辰区',3,0),(313,'津南区',3,0),(314,'武清区',3,0),(315,'宝坻区',3,0),(316,'静海县',3,0),(317,'宁河县',3,0),(318,'蓟县',3,0),(4,'重庆市',0,0),(401,'渝中区',4,0),(402,'大渡口区',4,0),(403,'江北区',4,0),(404,'沙坪坝区',4,0),(405,'九龙坡区',4,0),(406,'南岸区',4,0),(407,'北碚区',4,0),(408,'万盛区',4,0),(409,'双桥区',4,0),(410,'渝北区',4,0),(411,'巴南区',4,0),(412,'万州区',4,0),(413,'涪陵区',4,0),(414,'黔江区',4,0),(415,'永川市',4,0),(416,'合川市',4,0),(417,'江津市',4,0),(418,'南川市',4,0),(419,'长寿县',4,0),(420,'綦江县',4,0),(421,'潼南县',4,0),(422,'荣昌县',4,0),(423,'璧山县',4,0),(424,'大足县',4,0),(425,'铜梁县',4,0),(426,'梁平县',4,0),(427,'城口县',4,0),(428,'垫江县',4,0),(429,'武隆县',4,0),(430,'丰都县',4,0),(431,'奉节县',4,0),(432,'开县',4,0),(433,'云阳县',4,0),(434,'忠县',4,0),(435,'巫溪县',4,0),(436,'巫山县',4,0),(437,'石柱县',4,0),(438,'秀山县',4,0),(439,'酉阳县',4,0),(440,'彭水县',4,0),(5,'广东省',0,0),(501,'广州市',5,0),(502,'深圳市',5,0),(503,'珠海市',5,0),(504,'汕头市',5,0),(505,'韶关市',5,0),(506,'河源市',5,0),(507,'梅州市',5,0),(508,'惠州市',5,0),(509,'汕尾市',5,0),(510,'东莞市',5,0),(511,'中山市',5,0),(512,'江门市',5,0),(513,'佛山市',5,0),(514,'阳江市',5,0),(515,'湛江市',5,0),(516,'茂名市',5,0),(517,'肇庆市',5,0),(518,'清远市',5,0),(519,'潮州市',5,0),(520,'揭阳市',5,0),(521,'云浮市',5,0),(6,'福建省',0,0),(601,'福州市',6,0),(602,'厦门市',6,0),(603,'三明市',6,0),(604,'莆田市',6,0),(605,'泉州市',6,0),(606,'漳州市',6,0),(607,'南平市',6,0),(608,'龙岩市',6,0),(609,'宁德市',6,0),(7,'浙江省',0,0),(701,'杭州市',7,0),(702,'宁波市',7,0),(703,'温州市',7,0),(704,'嘉兴市',7,0),(705,'湖州市',7,0),(706,'绍兴市',7,0),(707,'金华市',7,0),(708,'衢州市',7,0),(709,'舟山市',7,0),(710,'台州市',7,0),(711,'丽水市',7,0),(8,'江苏省',0,0),(801,'南京市',8,0),(802,'徐州市',8,0),(803,'连云港市',8,0),(804,'淮安市',8,0),(805,'宿迁市',8,0),(806,'盐城市',8,0),(807,'扬州市',8,0),(808,'泰州市',8,0),(809,'南通市',8,0),(810,'镇江市',8,0),(811,'常州市',8,0),(812,'无锡市',8,0),(813,'苏州市',8,0),(9,'山东省',0,0),(901,'济南市',9,0),(902,'青岛市',9,0),(903,'淄博市',9,0),(904,'枣庄市',9,0),(905,'东营市',9,0),(906,'潍坊市',9,0),(907,'烟台市',9,0),(908,'威海市',9,0),(909,'济宁市',9,0),(910,'泰安市',9,0),(911,'日照市',9,0),(912,'莱芜市',9,0),(913,'德州市',9,0),(914,'临沂市',9,0),(915,'聊城市',9,0),(916,'滨州市',9,0),(917,'菏泽市',9,0),(10,'辽宁省',0,0),(1001,'沈阳市',10,0),(1002,'大连市',10,0),(1003,'鞍山市',10,0),(1004,'抚顺市',10,0),(1005,'本溪市',10,0),(1006,'丹东市',10,0),(1007,'锦州市',10,0),(1008,'葫芦岛市',10,0),(1009,'营口市',10,0),(1010,'盘锦市',10,0),(1011,'阜新市',10,0),(1012,'辽阳市',10,0),(1013,'铁岭市',10,0),(1014,'朝阳市',10,0),(11,'江西省',0,0),(1101,'南昌市',11,0),(1102,'景德镇市',11,0),(1103,'萍乡市',11,0),(1104,'新余市',11,0),(1105,'九江市',11,0),(1106,'鹰潭市',11,0),(1107,'赣州市',11,0),(1108,'吉安市',11,0),(1109,'宜春市',11,0),(1110,'抚州市',11,0),(1111,'上饶市',11,0),(12,'四川省',0,0),(1201,'成都市',12,0),(1202,'自贡市',12,0),(1203,'攀枝花市',12,0),(1204,'泸州市',12,0),(1205,'德阳市',12,0),(1206,'绵阳市',12,0),(1207,'广元市',12,0),(1208,'遂宁市',12,0),(1209,'内江市',12,0),(1210,'乐山市',12,0),(1211,'南充市',12,0),(1212,'宜宾市',12,0),(1213,'广安市',12,0),(1214,'达州市',12,0),(1215,'巴中市',12,0),(1216,'雅安市',12,0),(1217,'眉山市',12,0),(1218,'资阳市',12,0),(1219,'阿坝州',12,0),(1220,'甘孜州',12,0),(1221,'凉山州',12,0),(13,'陕西省',0,0),(3114,'西安市',13,0),(1302,'铜川市',13,0),(1303,'宝鸡市',13,0),(1304,'咸阳市',13,0),(1305,'渭南市',13,0),(1306,'延安市',13,0),(1307,'汉中市',13,0),(1308,'榆林市',13,0),(1309,'安康市',13,0),(1310,'商洛地区',13,0),(14,'湖北省',0,0),(1401,'武汉市',14,0),(1402,'黄石市',14,0),(1403,'襄樊市',14,0),(1404,'十堰市',14,0),(1405,'荆州市',14,0),(1406,'宜昌市',14,0),(1407,'荆门市',14,0),(1408,'鄂州市',14,0),(1409,'孝感市',14,0),(1410,'黄冈市',14,0),(1411,'咸宁市',14,0),(1412,'随州市',14,0),(1413,'仙桃市',14,0),(1414,'天门市',14,0),(1415,'潜江市',14,0),(1416,'神农架',14,0),(1417,'恩施州',14,0),(15,'河南省',0,0),(1501,'郑州市',15,0),(1502,'开封市',15,0),(1503,'洛阳市',15,0),(1504,'平顶山市',15,0),(1505,'焦作市',15,0),(1506,'鹤壁市',15,0),(1507,'新乡市',15,0),(1508,'安阳市',15,0),(1509,'濮阳市',15,0),(1510,'许昌市',15,0),(1511,'漯河市',15,0),(1512,'三门峡市',15,0),(1513,'南阳市',15,0),(1514,'商丘市',15,0),(1515,'信阳市',15,0),(1516,'周口市',15,0),(1517,'驻马店市',15,0),(1518,'济源市',15,0),(16,'河北省',0,0),(1601,'石家庄市',16,0),(1602,'唐山市',16,0),(1603,'秦皇岛市',16,0),(1604,'邯郸市',16,0),(1605,'邢台市',16,0),(1606,'保定市',16,0),(1607,'张家口市',16,0),(1608,'承德市',16,0),(1609,'沧州市',16,0),(1610,'廊坊市',16,0),(1611,'衡水市',16,0),(17,'山西省',0,0),(1701,'太原市',17,0),(1702,'大同市',17,0),(1703,'阳泉市',17,0),(1704,'长治市',17,0),(1705,'晋城市',17,0),(1706,'朔州市',17,0),(1707,'晋中市',17,0),(1708,'忻州市',17,0),(1709,'临汾市',17,0),(1710,'运城市',17,0),(1711,'吕梁地区',17,0),(18,'内蒙古',0,0),(1801,'呼和浩特',18,0),(1802,'包头市',18,0),(1803,'乌海市',18,0),(1804,'赤峰市',18,0),(1805,'通辽市',18,0),(1806,'鄂尔多斯',18,0),(1807,'乌兰察布',18,0),(1808,'锡林郭勒',18,0),(1809,'呼伦贝尔',18,0),(1810,'巴彦淖尔',18,0),(1811,'阿拉善盟',18,0),(1812,'兴安盟',18,0),(19,'吉林省',0,0),(1901,'长春市',19,0),(1902,'吉林市',19,0),(1903,'四平市',19,0),(1904,'辽源市',19,0),(1905,'通化市',19,0),(1906,'白山市',19,0),(1907,'松原市',19,0),(1908,'白城市',19,0),(1909,'延边州',19,0),(20,'黑龙江',0,0),(2001,'哈尔滨市',20,0),(2002,'齐齐哈尔',20,0),(2003,'鹤岗市',20,0),(2004,'双鸭山市',20,0),(2005,'鸡西市',20,0),(2006,'大庆市',20,0),(2007,'伊春市',20,0),(2008,'牡丹江市',20,0),(2009,'佳木斯市',20,0),(2010,'七台河市',20,0),(2011,'黑河市',20,0),(2012,'绥化市',20,0),(2013,'大兴安岭',20,0),(21,'安徽省',0,0),(2101,'合肥市',21,0),(2102,'芜湖市',21,0),(2103,'蚌埠市',21,0),(2104,'淮南市',21,0),(2105,'马鞍山市',21,0),(2106,'淮北市',21,0),(2107,'铜陵市',21,0),(2108,'安庆市',21,0),(2109,'黄山市',21,0),(2110,'滁州市',21,0),(2111,'阜阳市',21,0),(2112,'宿州市',21,0),(2113,'巢湖市',21,0),(2114,'六安市',21,0),(2115,'亳州市',21,0),(2116,'宣城市',21,0),(2117,'池州市',21,0),(22,'湖南省',0,0),(2201,'长沙市',22,0),(2202,'株州市',22,0),(2203,'湘潭市',22,0),(2204,'衡阳市',22,0),(2205,'邵阳市',22,0),(2206,'岳阳市',22,0),(2207,'常德市',22,0),(2208,'张家界市',22,0),(2209,'益阳市',22,0),(2210,'郴州市',22,0),(2211,'永州市',22,0),(2212,'怀化市',22,0),(2213,'娄底市',22,0),(2214,'湘西州',22,0),(23,'广西区',0,0),(2301,'南宁市',23,0),(2302,'柳州市',23,0),(2303,'桂林市',23,0),(2304,'梧州市',23,0),(2305,'北海市',23,0),(2306,'防城港市',23,0),(2307,'钦州市',23,0),(2308,'贵港市',23,0),(2309,'玉林市',23,0),(2310,'南宁地区',23,0),(2311,'柳州地区',23,0),(2312,'贺州地区',23,0),(2313,'百色地区',23,0),(2314,'河池地区',23,0),(24,'海南省',0,0),(2401,'海口市',24,0),(2402,'三亚市',24,0),(2403,'五指山市',24,0),(2404,'琼海市',24,0),(2405,'儋州市',24,0),(2406,'琼山市',24,0),(2407,'文昌市',24,0),(2408,'万宁市',24,0),(2409,'东方市',24,0),(2410,'澄迈县',24,0),(2411,'定安县',24,0),(2412,'屯昌县',24,0),(2413,'临高县',24,0),(2414,'白沙县',24,0),(2415,'昌江县',24,0),(2416,'乐东县',24,0),(2417,'陵水县',24,0),(2418,'保亭县',24,0),(2419,'琼中县',24,0),(25,'云南省',0,0),(2501,'昆明市',25,0),(2502,'曲靖市',25,0),(2503,'玉溪市',25,0),(2504,'保山市',25,0),(2505,'昭通市',25,0),(2506,'思茅地区',25,0),(2507,'临沧地区',25,0),(2508,'丽江地区',25,0),(2509,'文山州',25,0),(2510,'红河州',25,0),(2511,'西双版纳',25,0),(2512,'楚雄州',25,0),(2513,'大理州',25,0),(2514,'德宏州',25,0),(2515,'怒江州',25,0),(2516,'迪庆州',25,0),(26,'贵州省',0,0),(2601,'贵阳市',26,0),(2602,'六盘水市',26,0),(2603,'遵义市',26,0),(2604,'安顺市',26,0),(2605,'铜仁地区',26,0),(2606,'毕节地区',26,0),(2607,'黔西南州',26,0),(2608,'黔东南州',26,0),(2609,'黔南州',26,0),(27,'西藏区',0,0),(2701,'拉萨市',27,0),(2702,'那曲地区',27,0),(2703,'昌都地区',27,0),(2704,'山南地区',27,0),(2705,'日喀则',27,0),(2706,'阿里地区',27,0),(2707,'林芝地区',27,0),(28,'甘肃省',0,0),(2801,'兰州市',28,0),(2802,'金昌市',28,0),(2803,'白银市',28,0),(2804,'天水市',28,0),(2805,'嘉峪关市',28,0),(2806,'武威市',28,0),(2807,'定西地区',28,0),(2808,'平凉地区',28,0),(2809,'庆阳地区',28,0),(2810,'陇南地区',28,0),(2811,'张掖地区',28,0),(2812,'酒泉地区',28,0),(2813,'甘南州',28,0),(2814,'临夏州',28,0),(29,'宁夏区',0,0),(2901,'银川市',29,0),(2902,'石嘴山市',29,0),(2903,'吴忠市',29,0),(2904,'固原市',29,0),(30,'青海省',0,0),(3001,'西宁市',30,0),(3002,'海东地区',30,0),(3003,'海北州',30,0),(3004,'黄南州',30,0),(3005,'海南州',30,0),(3006,'果洛州',30,0),(3007,'玉树州',30,0),(3008,'海西州',30,0),(31,'新疆区',0,0),(3101,'乌鲁木齐',31,0),(3102,'克拉玛依',31,0),(3103,'石河子市',31,0),(3104,'吐鲁番',31,0),(3105,'哈密地区',31,0),(3106,'和田地区',31,0),(3107,'阿克苏',31,0),(3108,'喀什地区',31,0),(3109,'克孜勒苏',31,0),(3110,'巴音郭楞',31,0),(3111,'昌吉州',31,0),(3112,'博尔塔拉',31,0),(3113,'伊犁州',31,0),(3117,'东城区',1,0),(32,'香港区',0,0),(33,'澳门区',0,0),(35,'台湾省',0,0);
/*!40000 ALTER TABLE `dede_area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_channeltype`
--

DROP TABLE IF EXISTS `dede_channeltype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_channeltype` (
  `id` smallint(6) NOT NULL default '0',
  `nid` varchar(20) NOT NULL default '',
  `typename` varchar(30) NOT NULL default '',
  `maintable` varchar(50) NOT NULL default 'dede_archives',
  `addtable` varchar(50) NOT NULL default '',
  `addcon` varchar(30) NOT NULL default '',
  `mancon` varchar(30) NOT NULL default '',
  `editcon` varchar(30) NOT NULL default '',
  `useraddcon` varchar(30) NOT NULL default '',
  `usermancon` varchar(30) NOT NULL default '',
  `usereditcon` varchar(30) NOT NULL default '',
  `fieldset` text,
  `listfields` text,
  `allfields` text,
  `issystem` smallint(6) NOT NULL default '0',
  `isshow` smallint(6) NOT NULL default '1',
  `issend` smallint(6) NOT NULL default '0',
  `arcsta` smallint(6) NOT NULL default '-1',
  `usertype` char(10) NOT NULL default '',
  `sendrank` smallint(6) NOT NULL default '10',
  `isdefault` smallint(6) NOT NULL default '0',
  `needdes` tinyint(1) NOT NULL default '1',
  `needpic` tinyint(1) NOT NULL default '1',
  `titlename` varchar(20) NOT NULL default '标题',
  `onlyone` smallint(6) NOT NULL default '0',
  `dfcid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `nid` (`nid`,`isshow`,`arcsta`,`sendrank`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_channeltype`
--

LOCK TABLES `dede_channeltype` WRITE;
/*!40000 ALTER TABLE `dede_channeltype` DISABLE KEYS */;
INSERT INTO `dede_channeltype` VALUES (1,'article','普通文章','dede_archives','dede_addonarticle','article_add.php','content_list.php','article_edit.php','article_add.php','content_list.php','article_edit.php','<field:body itemname=\"文章内容\" autofield=\"0\" notsend=\"0\" type=\"htmltext\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"\" page=\"split\">\r\n</field:body>\r\n','','',1,1,1,-1,'',10,0,1,1,'标题',0,0),(2,'image','图片集','dede_archives','dede_addonimages','album_add.php','content_i_list.php','album_edit.php','album_add.php','content_list.php','album_edit.php','<field:pagestyle itemname=\'页面风格\' type=\'number\' isnull=\'true\' default=\'2\' rename=\'\' notsend=\'1\' />\r\n<field:imgurls itemname=\'图片集合\' type=\'img\' isnull=\'true\' default=\'\' rename=\'\' page=\'split\'/>\r\n<field:body itemname=\'图集内容\' autofield=\'0\' notsend=\'0\' type=\'htmltext\' isnull=\'true\' islist=\'0\' default=\'\'  maxlength=\'250\' page=\'\'></field:body>','','',1,1,1,-1,'',10,0,1,1,'标题',0,0),(3,'soft','软件','dede_archives','dede_addonsoft','soft_add.php','content_i_list.php','soft_edit.php','','','','<field:filetype islist=\'1\' itemname=\'文件类型\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:language islist=\'1\' itemname=\'语言\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:softtype islist=\'1\' itemname=\'软件类型\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:accredit islist=\'1\' itemname=\'授权方式\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:os islist=\'1\' itemname=\'操作系统\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:softrank  islist=\'1\' itemname=\'软件等级\' type=\'int\' isnull=\'true\' default=\'3\' rename=\'\' function=\'GetRankStar(@me)\' notsend=\'1\'/>\r\n<field:officialUrl  itemname=\'官方网址\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:officialDemo itemname=\'演示网址\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:softsize  itemname=\'软件大小\' type=\'text\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:softlinks  itemname=\'软件地址\' type=\'softlinks\' isnull=\'true\' default=\'\' rename=\'\' />\r\n<field:introduce  itemname=\'详细介绍\' type=\'htmltext\' isnull=\'trnue\' default=\'\' rename=\'\' />\r\n<field:daccess islist=\'1\' itemname=\'下载级别\' type=\'int\' isnull=\'true\' default=\'0\' rename=\'\' function=\'\' notsend=\'1\'/>\r\n<field:needmoney islist=\'1\' itemname=\'需要金币\' type=\'int\' isnull=\'true\' default=\'0\' rename=\'\' function=\'\' notsend=\'1\' />','filetype,language,softtype,os,accredit,softrank','',1,1,1,-1,'',10,0,1,1,'标题',0,0),(-1,'spec','专题','dede_archives','dede_addonspec','spec_add.php','content_s_list.php','spec_edit.php','','','','<field:note type=\'specialtopic\' isnull=\'true\' default=\'\' rename=\'\'/>','','',1,1,0,-1,'',10,0,1,1,'标题',0,0),(6,'shop','商品','dede_archives','dede_addonshop','archives_add.php','content_list.php','archives_edit.php','archives_add.php','content_list.php','archives_edit.php','<field:body itemname=\"详细介绍\" autofield=\"1\" notsend=\"0\" type=\"htmltext\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"\" page=\"split\">\r\n</field:body>\r\n\r\n\r\n<field:price itemname=\"市场价\" autofield=\"1\" notsend=\"0\" type=\"float\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"\" page=\"\">\r\n</field:price>\r\n\r\n\r\n<field:trueprice itemname=\"优惠价\" autofield=\"1\" notsend=\"0\" type=\"float\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"\" page=\"\">\r\n</field:trueprice>\r\n\r\n\r\n<field:brand itemname=\"品牌\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"250\" page=\"\">\r\n</field:brand>\r\n\r\n\r\n<field:units itemname=\"计量单位\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"250\" page=\"\">\r\n</field:units>\r\n\r\n\n\r\n\n\r\n','price,trueprice,brand,units','',0,1,1,-1,'企业',10,0,1,1,'商品名称',0,0),(-8,'infos','分类信息','dede_archives','dede_addoninfos','archives_sg_add.php','content_sg_list.php','archives_sg_edit.php','archives_sg_add.php','content_sg_list.php','archives_sg_edit.php','<field:channel itemname=\"频道id\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:channel>\r\n<field:arcrank itemname=\"浏览权限\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"5\" page=\"\"></field:arcrank>\r\n<field:mid itemname=\"会员id\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:mid>\r\n<field:click itemname=\"点击\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:click>\r\n<field:title itemname=\"标题\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"60\" page=\"\"></field:title>\r\n<field:senddate itemname=\"发布时间\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:senddate>\r\n<field:flag itemname=\"推荐属性\" autofield=\"0\" notsend=\"0\" type=\"checkbox\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:flag>\r\n<field:litpic itemname=\"缩略图\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"60\" page=\"\"></field:litpic>\r\n<field:userip itemname=\"会员IP\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"0\"  maxlength=\"15\" page=\"\"></field:userip>\r\n<field:lastpost itemname=\"最后评论时间\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:lastpost>\r\n<field:scores itemname=\"评论积分\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:scores>\r\n<field:goodpost itemname=\"好评数\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:goodpost>\r\n<field:badpost itemname=\"差评数\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:badpost>\r\n<field:nativeplace itemname=\"地区\" autofield=\"1\" notsend=\"0\" type=\"stepselect\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"250\" page=\"\">\r\n</field:nativeplace>\r\n<field:infotype itemname=\"信息类型\" autofield=\"1\" notsend=\"0\" type=\"stepselect\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"250\" page=\"\">\r\n</field:infotype>\r\n<field:body itemname=\"信息内容\" autofield=\"1\" notsend=\"0\" type=\"htmltext\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"250\" page=\"\">\r\n</field:body>\r\n<field:endtime itemname=\"截止日期\" autofield=\"1\" notsend=\"0\" type=\"datetime\" isnull=\"true\" islist=\"1\" default=\"\"  maxlength=\"250\" page=\"\">\r\n</field:endtime>\r\n<field:linkman itemname=\"联系人\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"50\" page=\"\">\r\n</field:linkman>\r\n<field:tel itemname=\"联系电话\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"\" maxlength=\"50\" page=\"\">\r\n</field:tel>\r\n<field:email itemname=\"电子邮箱\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"50\" page=\"\">\r\n</field:email>\r\n<field:address itemname=\"地址\" autofield=\"1\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"100\" page=\"\">\r\n</field:address>\r\n','channel,arcrank,mid,click,title,senddate,flag,litpic,lastpost,scores,goodpost,badpost,nativeplace,infotype,endtime',NULL,-1,1,1,-1,'',0,0,0,1,'信息标题',0,0);
/*!40000 ALTER TABLE `dede_channeltype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_co_htmls`
--

DROP TABLE IF EXISTS `dede_co_htmls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_co_htmls` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `nid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(60) NOT NULL default '',
  `litpic` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  `dtime` int(10) unsigned NOT NULL default '0',
  `isdown` tinyint(1) unsigned NOT NULL default '0',
  `isexport` tinyint(1) NOT NULL default '0',
  `result` mediumtext,
  PRIMARY KEY  (`aid`),
  KEY `nid` (`nid`),
  KEY `typeid` (`typeid`,`title`,`url`,`dtime`,`isdown`,`isexport`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_co_htmls`
--

LOCK TABLES `dede_co_htmls` WRITE;
/*!40000 ALTER TABLE `dede_co_htmls` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_co_htmls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_co_mediaurls`
--

DROP TABLE IF EXISTS `dede_co_mediaurls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_co_mediaurls` (
  `nid` mediumint(8) unsigned NOT NULL default '0',
  `hash` char(32) NOT NULL default '',
  `tofile` char(60) NOT NULL default '',
  KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_co_mediaurls`
--

LOCK TABLES `dede_co_mediaurls` WRITE;
/*!40000 ALTER TABLE `dede_co_mediaurls` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_co_mediaurls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_co_note`
--

DROP TABLE IF EXISTS `dede_co_note`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_co_note` (
  `nid` mediumint(8) unsigned NOT NULL auto_increment,
  `channelid` smallint(5) unsigned NOT NULL default '0',
  `notename` varchar(50) NOT NULL default '',
  `sourcelang` varchar(10) NOT NULL default 'gb2312',
  `uptime` int(10) unsigned NOT NULL default '0',
  `cotime` int(10) unsigned NOT NULL default '0',
  `pnum` smallint(5) unsigned NOT NULL default '0',
  `isok` tinyint(1) unsigned NOT NULL default '0',
  `usemore` tinyint(1) unsigned NOT NULL default '0',
  `listconfig` text,
  `itemconfig` text,
  PRIMARY KEY  (`nid`),
  KEY `isok` (`isok`,`channelid`,`cotime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_co_note`
--

LOCK TABLES `dede_co_note` WRITE;
/*!40000 ALTER TABLE `dede_co_note` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_co_note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_co_onepage`
--

DROP TABLE IF EXISTS `dede_co_onepage`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_co_onepage` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(60) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `issource` smallint(6) NOT NULL default '1',
  `lang` varchar(10) NOT NULL default 'gb2312',
  `rule` text,
  PRIMARY KEY  (`id`),
  KEY `url` (`url`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_co_onepage`
--

LOCK TABLES `dede_co_onepage` WRITE;
/*!40000 ALTER TABLE `dede_co_onepage` DISABLE KEYS */;
INSERT INTO `dede_co_onepage` VALUES (5,'www.dedecms.com','织梦网络',1,'gb2312','<div class=\"content\">{@body}<div class=\"cupage\">'),(4,'www.techweb.com.cn','Techweb',1,'gb2312','<div class=\"content_txt\">{@body}</div>\r\n'),(6,'tw.news.yahoo.com','台湾雅虎',1,'big5','<div id=\"ynwsartcontent\">{@body}</div>\r\n');
/*!40000 ALTER TABLE `dede_co_onepage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_co_urls`
--

DROP TABLE IF EXISTS `dede_co_urls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_co_urls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `hash` varchar(32) NOT NULL default '',
  `nid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_co_urls`
--

LOCK TABLES `dede_co_urls` WRITE;
/*!40000 ALTER TABLE `dede_co_urls` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_co_urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_diyforms`
--

DROP TABLE IF EXISTS `dede_diyforms`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_diyforms` (
  `diyid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `posttemplate` varchar(50) NOT NULL,
  `viewtemplate` varchar(50) NOT NULL,
  `listtemplate` varchar(50) NOT NULL,
  `table` varchar(50) NOT NULL default '',
  `info` text,
  `public` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`diyid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_diyforms`
--

LOCK TABLES `dede_diyforms` WRITE;
/*!40000 ALTER TABLE `dede_diyforms` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_diyforms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_downloads`
--

DROP TABLE IF EXISTS `dede_downloads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_downloads` (
  `hash` char(32) NOT NULL,
  `id` int(10) unsigned NOT NULL default '0',
  `downloads` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_downloads`
--

LOCK TABLES `dede_downloads` WRITE;
/*!40000 ALTER TABLE `dede_downloads` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_downloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_erradd`
--

DROP TABLE IF EXISTS `dede_erradd`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_erradd` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL,
  `mid` mediumint(8) unsigned default NULL,
  `title` char(60) NOT NULL default '',
  `type` smallint(6) NOT NULL default '0',
  `errtxt` mediumtext,
  `oktxt` mediumtext,
  `sendtime` int(10) unsigned NOT NULL default '0',
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_erradd`
--

LOCK TABLES `dede_erradd` WRITE;
/*!40000 ALTER TABLE `dede_erradd` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_erradd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_feedback`
--

DROP TABLE IF EXISTS `dede_feedback`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_feedback` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `username` char(20) NOT NULL default '',
  `arctitle` varchar(60) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `ischeck` smallint(6) NOT NULL default '0',
  `dtime` int(10) unsigned NOT NULL default '0',
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `bad` mediumint(8) unsigned NOT NULL default '0',
  `good` mediumint(8) unsigned NOT NULL default '0',
  `ftype` set('feedback','good','bad') NOT NULL default 'feedback',
  `face` smallint(5) unsigned NOT NULL default '0',
  `msg` text,
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`,`ischeck`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_feedback`
--

LOCK TABLES `dede_feedback` WRITE;
/*!40000 ALTER TABLE `dede_feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_flink`
--

DROP TABLE IF EXISTS `dede_flink`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_flink` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `sortrank` smallint(6) NOT NULL default '0',
  `url` char(60) NOT NULL default '',
  `webname` char(30) NOT NULL default '',
  `msg` char(200) NOT NULL default '',
  `email` char(50) NOT NULL default '',
  `logo` char(60) NOT NULL default '',
  `dtime` int(10) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `ischeck` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_flink`
--

LOCK TABLES `dede_flink` WRITE;
/*!40000 ALTER TABLE `dede_flink` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_flink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_flinktype`
--

DROP TABLE IF EXISTS `dede_flinktype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_flinktype` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `typename` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_flinktype`
--

LOCK TABLES `dede_flinktype` WRITE;
/*!40000 ALTER TABLE `dede_flinktype` DISABLE KEYS */;
INSERT INTO `dede_flinktype` VALUES (1,'综合网站'),(2,'娱乐类'),(3,'教育类'),(4,'计算机类'),(5,'电子商务'),(6,'网上信息'),(7,'论坛类'),(8,'其它类型');
/*!40000 ALTER TABLE `dede_flinktype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_freelist`
--

DROP TABLE IF EXISTS `dede_freelist`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_freelist` (
  `aid` int(11) NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `namerule` varchar(50) NOT NULL default '',
  `listdir` varchar(60) NOT NULL default '',
  `defaultpage` varchar(20) NOT NULL default '',
  `nodefault` smallint(6) NOT NULL default '0',
  `templet` varchar(50) NOT NULL default '',
  `edtime` int(11) NOT NULL default '0',
  `maxpage` smallint(5) unsigned NOT NULL default '100',
  `click` int(11) NOT NULL default '1',
  `listtag` mediumtext,
  `keywords` varchar(100) NOT NULL default '',
  `description` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_freelist`
--

LOCK TABLES `dede_freelist` WRITE;
/*!40000 ALTER TABLE `dede_freelist` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_freelist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_guestbook`
--

DROP TABLE IF EXISTS `dede_guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_guestbook` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(60) NOT NULL default '',
  `tid` mediumint(8) NOT NULL default '0',
  `mid` mediumint(8) unsigned default '0',
  `posttime` int(10) unsigned NOT NULL default '0',
  `uname` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `homepage` varchar(50) NOT NULL default '',
  `qq` varchar(15) NOT NULL default '',
  `face` varchar(10) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `dtime` int(10) unsigned NOT NULL default '0',
  `ischeck` smallint(6) NOT NULL default '1',
  `msg` text,
  PRIMARY KEY  (`id`),
  KEY `ischeck` (`ischeck`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_guestbook`
--

LOCK TABLES `dede_guestbook` WRITE;
/*!40000 ALTER TABLE `dede_guestbook` DISABLE KEYS */;
INSERT INTO `dede_guestbook` VALUES (1,'无标题',0,1,0,'admin','','','','','127.0.0.1',1292904884,1,'222'),(2,'无标题',0,1,0,'admin','','','','','127.0.0.1',1292905507,1,'222'),(3,'无标题',0,1,0,'admin','','','','','112.65.132.162',1293434611,1,'测试'),(4,'无标题',0,16,0,'lind','','','','','211.161.219.102',1293440552,1,'HI'),(5,'无标题',0,16,0,'lind','','','','','211.161.219.102',1293440580,1,'你好 旺吉祥'),(6,'无标题',0,17,0,'JoJoChow','','','','','221.137.116.107',1293717593,1,'我是第一个吗？'),(7,'无标题',0,17,0,'JoJoChow','','','','','221.137.116.107',1294146365,1,'我没看到我的留言'),(8,'无标题',0,21,0,'leolijun','','','','','202.109.121.58',1300437423,1,'王琳：你好！<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;首先恭贺你乔迁之喜！再恭喜你的旺吉祥网站做得很好，最后恭喜我成为旺吉祥的VIP。有空到你的新居参观。李俊'),(9,'无标题',0,22,0,'参与者','','','','','180.174.254.248',1300461027,1,'哈哈……快一点长大啊，世界是美丽的%'),(10,'无标题',0,22,0,'参与者','','','','','58.39.29.130',1300497921,1,'哈哈……是否可以开设一个“旺吉祥摄影园地”啊……');
/*!40000 ALTER TABLE `dede_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_homepageset`
--

DROP TABLE IF EXISTS `dede_homepageset`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_homepageset` (
  `templet` char(50) NOT NULL default '',
  `position` char(30) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_homepageset`
--

LOCK TABLES `dede_homepageset` WRITE;
/*!40000 ALTER TABLE `dede_homepageset` DISABLE KEYS */;
INSERT INTO `dede_homepageset` VALUES ('default/index.htm','../index.html');
/*!40000 ALTER TABLE `dede_homepageset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_keywords`
--

DROP TABLE IF EXISTS `dede_keywords`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_keywords` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `keyword` char(16) NOT NULL default '',
  `rank` mediumint(8) unsigned NOT NULL default '0',
  `sta` smallint(6) NOT NULL default '1',
  `rpurl` char(60) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `keyword` (`keyword`,`rank`,`sta`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_keywords`
--

LOCK TABLES `dede_keywords` WRITE;
/*!40000 ALTER TABLE `dede_keywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_log`
--

DROP TABLE IF EXISTS `dede_log`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_log` (
  `lid` mediumint(8) unsigned NOT NULL auto_increment,
  `adminid` smallint(8) unsigned NOT NULL default '0',
  `filename` char(60) NOT NULL default '',
  `method` char(10) NOT NULL default '',
  `query` char(200) NOT NULL default '',
  `cip` char(15) NOT NULL default '',
  `dtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_log`
--

LOCK TABLES `dede_log` WRITE;
/*!40000 ALTER TABLE `dede_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member`
--

DROP TABLE IF EXISTS `dede_member`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member` (
  `mid` mediumint(8) unsigned NOT NULL auto_increment,
  `mtype` varchar(20) NOT NULL default '个人',
  `userid` char(20) NOT NULL default '',
  `pwd` char(32) NOT NULL default '',
  `uname` char(36) NOT NULL default '',
  `sex` enum('男','女','保密') NOT NULL default '保密',
  `rank` smallint(5) unsigned NOT NULL default '0',
  `uptime` int(11) NOT NULL default '0',
  `exptime` smallint(6) NOT NULL default '0',
  `money` mediumint(8) unsigned NOT NULL default '0',
  `email` char(50) NOT NULL default '',
  `scores` mediumint(8) unsigned NOT NULL default '0',
  `matt` smallint(5) unsigned NOT NULL default '0',
  `spacesta` smallint(6) NOT NULL default '0',
  `face` char(50) NOT NULL default '',
  `safequestion` smallint(5) unsigned NOT NULL default '0',
  `safeanswer` char(30) NOT NULL default '',
  `jointime` int(10) unsigned NOT NULL default '0',
  `joinip` char(16) NOT NULL default '',
  `logintime` int(10) unsigned NOT NULL default '0',
  `loginip` char(16) NOT NULL default '',
  PRIMARY KEY  (`mid`),
  KEY `userid` (`userid`,`sex`),
  KEY `logintime` (`logintime`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member`
--

LOCK TABLES `dede_member` WRITE;
/*!40000 ALTER TABLE `dede_member` DISABLE KEYS */;
INSERT INTO `dede_member` VALUES (1,'个人','admin','21232f297a57a5a743894a0e4a801fc3','admin','男',100,0,0,0,'',10000,10,2,'',0,'',1291180264,'',1303453066,'112.65.132.162'),(15,'个人','yoyo','65f97e37b0cb8c6c14575355874df70b','','女',10,0,0,0,'346145894@qq.com',100,0,0,'',0,'',1293371156,'114.92.124.245',1293371156,'114.92.124.245'),(16,'个人','lind','65f97e37b0cb8c6c14575355874df70b','lind','男',10,1297220990,0,0,'430128061@qq.com',1000000,0,0,'/uploads/userup/16/myface.jpg',0,'',1293372020,'114.92.124.245',1293507072,'211.161.228.237'),(17,'个人','JoJoChow','0cbe7c7c99deaaeb375bb24c62122698','','男',10,0,0,0,'jojo_zhow@hotmail.com',104,0,0,'',0,'',1293717211,'221.137.116.107',1295192679,'58.24.95.132'),(18,'个人','dxh20030601','93d77219e0c24af305209824362c1aa1','','女',10,0,0,0,'dxh20030601@163.com',100,0,0,'',0,'',1294894796,'58.246.80.230',1294894796,'58.246.80.230'),(19,'个人','Candy','216a4e7bbcd38eac28e69edcccda12b8','','女',10,0,0,0,'candy213@gmail.com',102,0,0,'',0,'',1295007075,'114.86.90.126',1295007075,'114.86.90.126'),(20,'个人','国际正于','e516b2800a183f32f0c9b225ec6d6d9b','','男',10,0,0,0,'546502800@qq.com',100,0,0,'/uploads/userup/20/myface.jpg',0,'',1295331752,'114.92.19.90',1295331752,'114.92.19.90'),(21,'个人','leolijun','71464592903d1ac2a9bd40ae99cdbd95','','男',10,0,0,0,'leolijun@dhu.edu.cn',100,0,0,'',0,'',1300437227,'202.109.121.58',1300437227,'202.109.121.58'),(22,'个人','参与者','c0016e0a8a552fec7bb85767407c23d3','','男',10,0,0,0,'cyg5013@163.com',104,0,0,'/uploads/userup/22/myface.jpg',0,'',1300460852,'180.174.254.248',1300852723,'115.174.108.38'),(23,'个人','Arianrhood','86a634f8ee30c3f7dd021d0e8d17d4d9','','男',10,0,0,0,'d_ishizu@hotmail.com',100,0,0,'',0,'',1300776698,'115.174.109.156',1303365253,'124.14.53.199');
/*!40000 ALTER TABLE `dede_member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_company`
--

DROP TABLE IF EXISTS `dede_member_company`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_company` (
  `mid` mediumint(8) NOT NULL auto_increment,
  `company` varchar(36) NOT NULL default '',
  `product` varchar(50) NOT NULL default '',
  `place` smallint(5) unsigned NOT NULL default '0',
  `vocation` smallint(5) unsigned NOT NULL default '0',
  `cosize` smallint(5) unsigned NOT NULL default '0',
  `tel` varchar(30) NOT NULL default '',
  `fax` varchar(30) NOT NULL default '',
  `linkman` varchar(20) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `mobile` varchar(30) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `url` varchar(50) NOT NULL default '',
  `uptime` int(10) unsigned NOT NULL default '0',
  `checked` tinyint(1) unsigned NOT NULL default '0',
  `introduce` text,
  `comface` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_company`
--

LOCK TABLES `dede_member_company` WRITE;
/*!40000 ALTER TABLE `dede_member_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_feed`
--

DROP TABLE IF EXISTS `dede_member_feed`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_feed` (
  `fid` mediumint(8) unsigned NOT NULL auto_increment,
  `mid` smallint(8) unsigned NOT NULL default '0',
  `userid` char(20) NOT NULL default '',
  `uname` char(36) NOT NULL default '',
  `type` char(20) character set gb2312 NOT NULL default '0',
  `aid` mediumint(8) NOT NULL default '0',
  `dtime` int(10) unsigned NOT NULL default '0',
  `title` char(253) NOT NULL,
  `note` char(200) NOT NULL default '',
  `ischeck` smallint(6) NOT NULL,
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_feed`
--

LOCK TABLES `dede_member_feed` WRITE;
/*!40000 ALTER TABLE `dede_member_feed` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_feed` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_flink`
--

DROP TABLE IF EXISTS `dede_member_flink`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_flink` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(30) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_flink`
--

LOCK TABLES `dede_member_flink` WRITE;
/*!40000 ALTER TABLE `dede_member_flink` DISABLE KEYS */;
INSERT INTO `dede_member_flink` VALUES (1,2,'织梦内容管理系统','http://www.dedecms.com'),(2,3,'织梦内容管理系统','http://www.dedecms.com'),(3,4,'织梦内容管理系统','http://www.dedecms.com'),(4,5,'织梦内容管理系统','http://www.dedecms.com'),(5,6,'织梦内容管理系统','http://www.dedecms.com'),(6,7,'织梦内容管理系统','http://www.dedecms.com'),(7,8,'织梦内容管理系统','http://www.dedecms.com'),(8,9,'织梦内容管理系统','http://www.dedecms.com'),(9,10,'织梦内容管理系统','http://www.dedecms.com'),(10,11,'织梦内容管理系统','http://www.dedecms.com'),(11,12,'织梦内容管理系统','http://www.dedecms.com'),(12,13,'织梦内容管理系统','http://www.dedecms.com'),(13,14,'织梦内容管理系统','http://www.dedecms.com'),(14,15,'织梦内容管理系统','http://www.dedecms.com'),(15,16,'织梦内容管理系统','http://www.dedecms.com'),(16,17,'织梦内容管理系统','http://www.dedecms.com'),(17,18,'织梦内容管理系统','http://www.dedecms.com'),(18,19,'织梦内容管理系统','http://www.dedecms.com'),(19,20,'织梦内容管理系统','http://www.dedecms.com'),(20,21,'织梦内容管理系统','http://www.dedecms.com'),(21,22,'织梦内容管理系统','http://www.dedecms.com'),(22,23,'织梦内容管理系统','http://www.dedecms.com');
/*!40000 ALTER TABLE `dede_member_flink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_friends`
--

DROP TABLE IF EXISTS `dede_member_friends`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_friends` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `fid` mediumint(8) unsigned NOT NULL default '0',
  `floginid` char(20) NOT NULL default '',
  `funame` char(36) NOT NULL default '',
  `mid` mediumint(8) NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  `ftype` tinyint(4) NOT NULL default '0',
  `groupid` int(8) NOT NULL default '1',
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_friends`
--

LOCK TABLES `dede_member_friends` WRITE;
/*!40000 ALTER TABLE `dede_member_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_group`
--

DROP TABLE IF EXISTS `dede_member_group`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_group` (
  `id` int(10) NOT NULL auto_increment,
  `groupname` varchar(50) NOT NULL,
  `mid` int(8) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_group`
--

LOCK TABLES `dede_member_group` WRITE;
/*!40000 ALTER TABLE `dede_member_group` DISABLE KEYS */;
INSERT INTO `dede_member_group` VALUES (1,'朋友',0);
/*!40000 ALTER TABLE `dede_member_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_guestbook`
--

DROP TABLE IF EXISTS `dede_member_guestbook`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_guestbook` (
  `aid` int(10) unsigned NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `gid` varchar(20) NOT NULL default '0',
  `title` varchar(60) NOT NULL default '',
  `uname` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `qq` varchar(50) NOT NULL default '',
  `tel` varchar(50) NOT NULL default '',
  `ip` varchar(20) NOT NULL default '',
  `dtime` int(10) unsigned NOT NULL default '0',
  `msg` text,
  PRIMARY KEY  (`aid`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_guestbook`
--

LOCK TABLES `dede_member_guestbook` WRITE;
/*!40000 ALTER TABLE `dede_member_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_model`
--

DROP TABLE IF EXISTS `dede_member_model`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_model` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `table` varchar(30) NOT NULL,
  `description` varchar(50) NOT NULL,
  `state` int(2) NOT NULL default '0',
  `issystem` int(2) NOT NULL default '0',
  `info` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_model`
--

LOCK TABLES `dede_member_model` WRITE;
/*!40000 ALTER TABLE `dede_member_model` DISABLE KEYS */;
INSERT INTO `dede_member_model` VALUES (1,'个人','dede_member_person','个人会员模型',1,1,'\r\n<field:onlynet itemname=\"联系方式限制\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"1\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:onlynet>\r\n\r\n<field:sex itemname=\"性别\" autofield=\"1\" type=\"radio\" isnull=\"true\" default=\"男,女,保密\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:sex>\r\n\r\n<field:uname itemname=\"昵称/公司名称\" autofield=\"1\" type=\"textchar\" isnull=\"true\" default=\"\"  maxlength=\"30\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:uname>\r\n\r\n<field:qq itemname=\"QQ\" autofield=\"1\" type=\"textchar\" isnull=\"true\" default=\"\"  maxlength=\"12\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:qq>\r\n\r\n<field:msn itemname=\"MSN\" autofield=\"1\" type=\"textchar\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:msn>\r\n\r\n<field:tel itemname=\"电话号码\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"15\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:tel>\r\n\r\n<field:mobile itemname=\"手机\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"15\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:mobile>\r\n\r\n<field:place itemname=\"目前所在地\" autofield=\"1\" type=\"int\" default=\"0\"  maxlength=\"5\" issearch=\"0\" isshow=\"0\" state=\"1\">\r\n</field:place>\r\n\r\n\r\n<field:oldplace itemname=\"家乡所在地\" autofield=\"1\" type=\"int\" default=\"0\"  maxlength=\"5\" issearch=\"0\" isshow=\"0\" state=\"1\">\r\n</field:oldplace>\r\n\r\n\r\n<field:birthday itemname=\"生日\" autofield=\"1\" type=\"datetime\" isnull=\"true\" default=\"\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:birthday>\r\n\r\n<field:star itemname=\"星座\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"1\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:star>\r\n\r\n<field:income itemname=\"收入\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:income>\r\n\r\n<field:education itemname=\"学历\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:education>\r\n\r\n<field:height itemname=\"身高\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"160\"  maxlength=\"5\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:height>\r\n\r\n<field:bodytype itemname=\"体重\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:bodytype>\r\n\r\n<field:blood itemname=\"血型\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:blood>\r\n\r\n<field:vocation itemname=\"职业\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:vocation>\r\n\r\n<field:smoke itemname=\"吸烟\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:smoke>\r\n\r\n<field:marital itemname=\"婚姻状况\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:marital>\r\n\r\n<field:house itemname=\"住房\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:house>\r\n\r\n<field:drink itemname=\"饮酒\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:drink>\r\n\r\n<field:datingtype itemname=\"交友\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"6\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:datingtype>\r\n\r\n<field:language itemname=\"语言\" autofield=\"1\" type=\"checkbox\" isnull=\"true\" default=\"普通话,上海话,广东话,英语,日语,韩语,法语,意大利语,德语,西班牙语,俄语,阿拉伯语\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:language>\r\n\r\n\r\n<field:nature itemname=\"性格\" autofield=\"1\" type=\"checkbox\" isnull=\"true\" default=\"性格外向,性格内向,活泼开朗,豪放不羁,患得患失,冲动,幽默,稳重,轻浮,沉默寡言,多愁善感,时喜时悲,附庸风雅,能说会道,坚强,脆弱,幼稚,成熟,快言快语,损人利己,狡猾善变,交际广泛,优柔寡断,自私,真诚,独立,依赖,难以琢磨,悲观消极,郁郁寡欢,胆小怕事,乐观向上,任性,自负,自卑,拜金,温柔体贴,小心翼翼,暴力倾向,逆来顺受,不拘小节,暴躁,倔强,豪爽,害羞,婆婆妈妈,敢做敢当,助人为乐,耿直,虚伪,孤僻,老实,守旧,敏感,迟钝,婆婆妈妈,武断,果断,刻薄\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:nature>\r\n\r\n<field:lovemsg itemname=\"人生格言\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"100\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:lovemsg>\r\n\r\n<field:address itemname=\"家庭住址\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:address>\r\n\r\n<field:uptime itemname=\"更新时间\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"\"  maxlength=\"10\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:uptime>\r\n'),(2,'企业','dede_member_company','公司企业会员模型',1,1,'\r\n<field:company itemname=\"公司名称\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"36\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:company>\r\n\r\n<field:product itemname=\"公司产品\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:product>\r\n\r\n<field:place itemname=\"所在地址\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"5\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:place>\r\n\r\n<field:vocation itemname=\"所属行业\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"5\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:vocation>\r\n\r\n<field:cosize itemname=\"公司规模\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"5\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:cosize>\r\n\r\n<field:tel itemname=\"电话号码\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"30\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:tel>\r\n\r\n<field:fax itemname=\"传真\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"30\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:fax>\r\n\r\n<field:linkman itemname=\"联系人\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"20\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:linkman>\r\n\r\n<field:address itemname=\"详细地址\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:address>\r\n\r\n<field:mobile itemname=\"手机\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"30\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:mobile>\r\n\r\n<field:email itemname=\"邮箱\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:email>\r\n\r\n<field:url itemname=\"地址\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"50\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:url>\r\n\r\n<field:uptime itemname=\"更新时间\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"10\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:uptime>\r\n\r\n<field:checked itemname=\"是否审核\" autofield=\"1\" type=\"int\" isnull=\"true\" default=\"0\"  maxlength=\"1\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:checked>\r\n\r\n<field:introduce itemname=\"公司简介\" autofield=\"1\" type=\"multitext\" isnull=\"true\" default=\"\"  maxlength=\"250\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:introduce>\r\n\r\n<field:comface itemname=\"公司标志\" autofield=\"1\" type=\"text\" isnull=\"true\" default=\"\"  maxlength=\"255\" issearch=\"\" isshow=\"\" state=\"1\">\r\n</field:comface>\r\n');
/*!40000 ALTER TABLE `dede_member_model` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_msg`
--

DROP TABLE IF EXISTS `dede_member_msg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_msg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `userid` char(20) NOT NULL default '',
  `ip` char(15) NOT NULL default '',
  `ischeck` smallint(6) NOT NULL default '0',
  `dtime` int(10) unsigned NOT NULL default '0',
  `msg` text,
  PRIMARY KEY  (`id`),
  KEY `id` (`ischeck`,`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_msg`
--

LOCK TABLES `dede_member_msg` WRITE;
/*!40000 ALTER TABLE `dede_member_msg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_msg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_operation`
--

DROP TABLE IF EXISTS `dede_member_operation`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_operation` (
  `aid` int(11) NOT NULL auto_increment,
  `buyid` varchar(80) NOT NULL default '',
  `pname` varchar(50) NOT NULL default '',
  `product` varchar(10) NOT NULL default '',
  `money` int(11) NOT NULL default '0',
  `mtime` int(11) NOT NULL default '0',
  `pid` int(11) NOT NULL default '0',
  `mid` int(11) NOT NULL default '0',
  `sta` int(11) NOT NULL default '0',
  `oldinfo` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`aid`),
  KEY `buyid` (`buyid`),
  KEY `pid` (`pid`,`mid`,`sta`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_operation`
--

LOCK TABLES `dede_member_operation` WRITE;
/*!40000 ALTER TABLE `dede_member_operation` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_operation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_person`
--

DROP TABLE IF EXISTS `dede_member_person`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_person` (
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `onlynet` tinyint(1) unsigned NOT NULL default '1',
  `sex` enum('男','女','保密') NOT NULL default '男',
  `uname` char(30) NOT NULL default '',
  `qq` char(12) NOT NULL default '',
  `msn` char(50) NOT NULL default '',
  `tel` varchar(15) NOT NULL default '',
  `mobile` varchar(15) NOT NULL default '',
  `place` smallint(5) unsigned NOT NULL default '0',
  `oldplace` smallint(5) unsigned NOT NULL default '0',
  `birthday` date NOT NULL default '1980-01-01',
  `star` smallint(6) unsigned NOT NULL default '1',
  `income` smallint(6) NOT NULL default '0',
  `education` smallint(6) NOT NULL default '0',
  `height` smallint(5) unsigned NOT NULL default '160',
  `bodytype` smallint(6) NOT NULL default '0',
  `blood` smallint(6) NOT NULL default '0',
  `vocation` smallint(6) NOT NULL default '0',
  `smoke` smallint(6) NOT NULL default '0',
  `marital` smallint(6) NOT NULL default '0',
  `house` smallint(6) NOT NULL default '0',
  `drink` smallint(6) NOT NULL default '0',
  `datingtype` smallint(6) NOT NULL default '0',
  `language` set('普通话','上海话','广东话','英语','日语','韩语','法语','意大利语','德语','西班牙语','俄语','阿拉伯语') default NULL,
  `nature` set('性格外向','性格内向','活泼开朗','豪放不羁','患得患失','冲动','幽默','稳重','轻浮','沉默寡言','多愁善感','时喜时悲','附庸风雅','能说会道','坚强','脆弱','幼稚','成熟','快言快语','损人利己','狡猾善变','交际广泛','优柔寡断','自私','真诚','独立','依赖','难以琢磨','悲观消极','郁郁寡欢','胆小怕事','乐观向上','任性','自负','自卑','拜金','温柔体贴','小心翼翼','暴力倾向','逆来顺受','不拘小节','暴躁','倔强','豪爽','害羞','婆婆妈妈','敢做敢当','助人为乐','耿直','虚伪','孤僻','老实','守旧','敏感','迟钝','婆婆妈妈','武断','果断','刻薄') default NULL,
  `lovemsg` varchar(100) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `uptime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_person`
--

LOCK TABLES `dede_member_person` WRITE;
/*!40000 ALTER TABLE `dede_member_person` DISABLE KEYS */;
INSERT INTO `dede_member_person` VALUES (1,2,'男','','','','','',0,0,'2010-12-16',0,0,0,160,0,0,0,0,0,0,0,0,'','','','',0),(2,2,'男','','','','','',0,0,'2010-12-16',0,0,0,160,0,0,0,0,0,0,0,0,'','','','',0),(3,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(4,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(5,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(6,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(7,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(8,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(9,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(10,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(11,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(12,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(13,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(14,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(15,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(16,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(17,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(18,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(19,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(20,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(21,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(22,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0),(23,1,'男','','','','','',0,0,'1980-01-01',1,0,0,160,0,0,0,0,0,0,0,0,NULL,NULL,'','',0);
/*!40000 ALTER TABLE `dede_member_person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_pms`
--

DROP TABLE IF EXISTS `dede_member_pms`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_pms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `floginid` varchar(20) NOT NULL default '',
  `fromid` mediumint(8) unsigned NOT NULL default '0',
  `toid` mediumint(8) unsigned NOT NULL default '0',
  `tologinid` char(20) NOT NULL default '',
  `folder` enum('inbox','outbox') default 'inbox',
  `subject` varchar(60) NOT NULL default '',
  `sendtime` int(10) unsigned NOT NULL default '0',
  `writetime` int(10) unsigned NOT NULL default '0',
  `hasview` tinyint(1) unsigned NOT NULL default '0',
  `isadmin` tinyint(1) NOT NULL default '0',
  `message` text,
  PRIMARY KEY  (`id`),
  KEY `sendtime` (`sendtime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_pms`
--

LOCK TABLES `dede_member_pms` WRITE;
/*!40000 ALTER TABLE `dede_member_pms` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_pms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_snsmsg`
--

DROP TABLE IF EXISTS `dede_member_snsmsg`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_snsmsg` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `userid` varchar(20) NOT NULL,
  `sendtime` int(10) unsigned NOT NULL default '0',
  `msg` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_snsmsg`
--

LOCK TABLES `dede_member_snsmsg` WRITE;
/*!40000 ALTER TABLE `dede_member_snsmsg` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_snsmsg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_space`
--

DROP TABLE IF EXISTS `dede_member_space`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_space` (
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `pagesize` smallint(5) unsigned NOT NULL default '10',
  `matt` smallint(6) NOT NULL default '0',
  `spacename` varchar(50) NOT NULL default '',
  `spacelogo` varchar(50) NOT NULL default '',
  `spacestyle` varchar(20) NOT NULL default '',
  `sign` varchar(100) NOT NULL default '没签名',
  `spacenews` text,
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_space`
--

LOCK TABLES `dede_member_space` WRITE;
/*!40000 ALTER TABLE `dede_member_space` DISABLE KEYS */;
INSERT INTO `dede_member_space` VALUES (1,10,0,'admin的空间','','person','',''),(2,10,0,'test的空间','','person','',''),(3,10,0,'test2的空间','','person','',''),(4,10,0,'的空间','','person','',''),(5,10,0,'的空间','','person','',''),(6,10,0,'的空间','','person','',''),(7,10,0,'的空间','','person','',''),(8,10,0,'的空间','','person','',''),(9,10,0,'的空间','','person','',''),(10,10,0,'的空间','','person','',''),(11,10,0,'的空间','','person','',''),(12,10,0,'的空间','','person','',''),(13,10,0,'的空间','','person','',''),(14,10,0,'的空间','','person','',''),(15,10,0,'的空间','','person','',''),(16,10,0,'的空间','','person','',''),(17,10,0,'的空间','','person','',''),(18,10,0,'的空间','','person','',''),(19,10,0,'的空间','','person','',''),(20,10,0,'的空间','','person','',''),(21,10,0,'的空间','','person','',''),(22,10,0,'的空间','','person','',''),(23,10,0,'的空间','','person','','');
/*!40000 ALTER TABLE `dede_member_space` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_stow`
--

DROP TABLE IF EXISTS `dede_member_stow`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_stow` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `aid` mediumint(8) unsigned NOT NULL default '0',
  `title` char(60) NOT NULL default '',
  `addtime` int(10) unsigned NOT NULL default '0',
  `type` varchar(20) NOT NULL default 'sys',
  PRIMARY KEY  (`id`),
  KEY `uid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_stow`
--

LOCK TABLES `dede_member_stow` WRITE;
/*!40000 ALTER TABLE `dede_member_stow` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_stow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_stowtype`
--

DROP TABLE IF EXISTS `dede_member_stowtype`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_stowtype` (
  `stowname` varchar(30) NOT NULL,
  `indexname` varchar(30) NOT NULL,
  `indexurl` varchar(50) NOT NULL,
  PRIMARY KEY  (`stowname`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_stowtype`
--

LOCK TABLES `dede_member_stowtype` WRITE;
/*!40000 ALTER TABLE `dede_member_stowtype` DISABLE KEYS */;
INSERT INTO `dede_member_stowtype` VALUES ('sys','系统收藏','archives_do.php'),('book','小说收藏','/book/book.php?bid');
/*!40000 ALTER TABLE `dede_member_stowtype` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_tj`
--

DROP TABLE IF EXISTS `dede_member_tj`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_tj` (
  `mid` mediumint(8) NOT NULL auto_increment,
  `article` smallint(5) unsigned NOT NULL default '0',
  `album` smallint(5) unsigned NOT NULL default '0',
  `archives` smallint(5) unsigned NOT NULL default '0',
  `homecount` int(10) unsigned NOT NULL default '0',
  `pagecount` int(10) unsigned NOT NULL default '0',
  `feedback` mediumint(8) unsigned NOT NULL default '0',
  `friend` smallint(5) unsigned NOT NULL default '0',
  `stow` smallint(5) unsigned NOT NULL default '0',
  `soft` int(10) NOT NULL default '0',
  `info` int(10) NOT NULL default '0',
  `shop` int(10) NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_tj`
--

LOCK TABLES `dede_member_tj` WRITE;
/*!40000 ALTER TABLE `dede_member_tj` DISABLE KEYS */;
INSERT INTO `dede_member_tj` VALUES (1,0,0,0,1,10,0,0,0,0,0,0),(2,0,0,0,2,0,0,0,0,0,0,0),(3,0,0,0,1,0,0,0,0,0,0,0),(4,0,0,0,0,0,0,0,0,0,0,0),(5,0,0,0,0,0,0,0,0,0,0,0),(6,0,0,0,0,0,0,0,0,0,0,0),(7,0,0,0,0,0,0,0,0,0,0,0),(8,0,0,0,0,0,0,0,0,0,0,0),(9,0,0,0,0,0,0,0,0,0,0,0),(10,0,0,0,0,0,0,0,0,0,0,0),(11,0,0,0,0,0,0,0,0,0,0,0),(12,0,0,0,0,0,0,0,0,0,0,0),(13,0,0,0,0,0,0,0,0,0,0,0),(14,0,0,0,0,0,0,0,0,0,0,0),(15,0,0,0,1,0,0,0,0,0,0,0),(16,0,0,0,1,0,0,0,0,0,0,0),(17,0,0,0,0,0,0,0,0,0,0,0),(18,0,0,0,1,0,0,0,0,0,0,0),(19,0,0,0,1,0,0,0,0,0,0,0),(20,0,0,0,1,0,0,0,0,0,0,0),(21,0,0,0,0,0,0,0,0,0,0,0),(22,0,0,0,0,0,0,0,0,0,0,0),(23,0,0,0,0,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `dede_member_tj` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_type`
--

DROP TABLE IF EXISTS `dede_member_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_type` (
  `aid` int(11) NOT NULL auto_increment,
  `rank` int(11) NOT NULL default '0',
  `pname` varchar(50) NOT NULL default '',
  `money` int(11) NOT NULL default '0',
  `exptime` int(11) NOT NULL default '30',
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_type`
--

LOCK TABLES `dede_member_type` WRITE;
/*!40000 ALTER TABLE `dede_member_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_member_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_member_vhistory`
--

DROP TABLE IF EXISTS `dede_member_vhistory`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_member_vhistory` (
  `id` int(10) NOT NULL auto_increment,
  `mid` mediumint(8) unsigned NOT NULL default '0',
  `loginid` char(20) NOT NULL default '',
  `vid` mediumint(8) unsigned NOT NULL default '0',
  `vloginid` char(20) NOT NULL default '',
  `count` smallint(5) unsigned NOT NULL default '0',
  `vip` char(15) NOT NULL default '',
  `vtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `vtime` (`vtime`),
  KEY `mid` (`mid`,`vid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_member_vhistory`
--

LOCK TABLES `dede_member_vhistory` WRITE;
/*!40000 ALTER TABLE `dede_member_vhistory` DISABLE KEYS */;
INSERT INTO `dede_member_vhistory` VALUES (1,3,'test2',1,'admin',1,'127.0.0.1',1292918060),(2,2,'test',1,'admin',1,'127.0.0.1',1292918071),(3,15,'yoyo',1,'admin',1,'112.65.132.162',1293427066),(4,20,'国际正于',1,'admin',1,'211.161.249.7',1297220945),(5,19,'Candy',1,'admin',1,'211.161.249.7',1297220973),(6,18,'dxh20030601',1,'admin',1,'211.161.249.7',1297220979),(7,16,'lind',1,'admin',1,'211.161.249.7',1297220984);
/*!40000 ALTER TABLE `dede_member_vhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_moneycard_record`
--

DROP TABLE IF EXISTS `dede_moneycard_record`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_moneycard_record` (
  `aid` int(11) NOT NULL auto_increment,
  `ctid` int(11) NOT NULL default '0',
  `cardid` varchar(50) NOT NULL default '',
  `uid` int(11) NOT NULL default '0',
  `isexp` smallint(6) NOT NULL default '0',
  `mtime` int(11) NOT NULL default '0',
  `utime` int(11) NOT NULL default '0',
  `money` int(11) NOT NULL default '0',
  `num` int(11) NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `ctid` (`ctid`),
  KEY `cardid` (`cardid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_moneycard_record`
--

LOCK TABLES `dede_moneycard_record` WRITE;
/*!40000 ALTER TABLE `dede_moneycard_record` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_moneycard_record` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_moneycard_type`
--

DROP TABLE IF EXISTS `dede_moneycard_type`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_moneycard_type` (
  `tid` int(11) NOT NULL auto_increment,
  `num` int(11) NOT NULL default '500',
  `money` int(11) NOT NULL default '50',
  `pname` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_moneycard_type`
--

LOCK TABLES `dede_moneycard_type` WRITE;
/*!40000 ALTER TABLE `dede_moneycard_type` DISABLE KEYS */;
INSERT INTO `dede_moneycard_type` VALUES (1,100,30,'100点卡'),(2,200,55,'200点卡'),(3,300,75,'300点卡');
/*!40000 ALTER TABLE `dede_moneycard_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_mtypes`
--

DROP TABLE IF EXISTS `dede_mtypes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_mtypes` (
  `mtypeid` mediumint(8) unsigned NOT NULL auto_increment,
  `mtypename` char(40) NOT NULL,
  `channelid` smallint(6) NOT NULL default '1',
  `mid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mtypeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_mtypes`
--

LOCK TABLES `dede_mtypes` WRITE;
/*!40000 ALTER TABLE `dede_mtypes` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_mtypes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_multiserv_config`
--

DROP TABLE IF EXISTS `dede_multiserv_config`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_multiserv_config` (
  `remoteuploads` smallint(6) NOT NULL default '0',
  `remoteupUrl` text NOT NULL,
  `rminfo` text,
  `servinfo` mediumtext,
  PRIMARY KEY  (`remoteuploads`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_multiserv_config`
--

LOCK TABLES `dede_multiserv_config` WRITE;
/*!40000 ALTER TABLE `dede_multiserv_config` DISABLE KEYS */;
INSERT INTO `dede_multiserv_config` VALUES (0,'http://img.dedecms.com','','');
/*!40000 ALTER TABLE `dede_multiserv_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_myad`
--

DROP TABLE IF EXISTS `dede_myad`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_myad` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `tagname` varchar(30) NOT NULL default '',
  `adname` varchar(60) NOT NULL default '',
  `timeset` smallint(6) NOT NULL default '0',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `normbody` text,
  `expbody` text,
  PRIMARY KEY  (`aid`),
  KEY `tagname` (`tagname`,`typeid`,`timeset`,`endtime`,`starttime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_myad`
--

LOCK TABLES `dede_myad` WRITE;
/*!40000 ALTER TABLE `dede_myad` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_myad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_mytag`
--

DROP TABLE IF EXISTS `dede_mytag`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_mytag` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `tagname` varchar(30) NOT NULL default '',
  `timeset` smallint(6) NOT NULL default '0',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `normbody` text,
  `expbody` text,
  PRIMARY KEY  (`aid`),
  KEY `tagname` (`tagname`,`typeid`,`timeset`,`endtime`,`starttime`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_mytag`
--

LOCK TABLES `dede_mytag` WRITE;
/*!40000 ALTER TABLE `dede_mytag` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_mytag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_payment`
--

DROP TABLE IF EXISTS `dede_payment`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_payment` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `code` varchar(20) NOT NULL default '',
  `name` varchar(120) NOT NULL default '',
  `fee` varchar(10) NOT NULL default '0',
  `description` text NOT NULL,
  `rank` tinyint(3) unsigned NOT NULL default '0',
  `config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL default '0',
  `cod` tinyint(1) unsigned NOT NULL default '0',
  `online` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_payment`
--

LOCK TABLES `dede_payment` WRITE;
/*!40000 ALTER TABLE `dede_payment` DISABLE KEYS */;
INSERT INTO `dede_payment` VALUES (3,'alipay','支付宝','','支付宝网站(www.alipay.com) 是国内先进的网上支付平台。<br/>DedeCMS联合支付宝推出支付宝接口。<br/><a href=\"https://www.alipay.com/himalayas/practicality_customer.htm?customer_external_id=C4335994340215837114&market_type=from_agent_contract&pro_codes=6ACD133C5F350958F7F62F29651356BB \" target=\"_blank\"><font color=\"red\">立即在线申请</font></a>',1,'a:4:{s:14:\"alipay_account\";a:4:{s:5:\"title\";s:14:\"支付宝用户账号\";s:11:\"description\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}s:10:\"alipay_key\";a:4:{s:5:\"title\";s:14:\"交易安全校验码\";s:11:\"description\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}s:14:\"alipay_partner\";a:4:{s:5:\"title\";s:12:\"合作者身份ID\";s:11:\"description\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}s:17:\"alipay_pay_method\";a:5:{s:5:\"title\";s:14:\"支付宝账号类型\";s:11:\"description\";s:52:\"请选择您最后一次跟支付宝签订的协议里面说明的接口类型\";s:4:\"type\";s:6:\"select\";s:5:\"iterm\";s:58:\"0:使用标准双接口,1:使用担保交易接口,2:使用即时到帐交易接口\";s:5:\"value\";s:0:\"\";}}',0,0,1),(2,'bank','银行汇款/转帐','0','银行名称\r\n收款人信息：全称 ××× ；帐号或地址 ××× ；开户行 ×××。\r\n注意事项：办理电汇时，请在电汇单“汇款用途”一栏处注明您的订单号。',4,'a:0:{}',1,1,0),(1,'cod','货到付款','0','开通城市：×××\r\n货到付款区域：×××',3,'a:0:{}',1,1,0),(6,'yeepay','YeePay易宝','','YeePay易宝（北京通融通信息技术有限公司）是专业从事多元化电子支付业务一站式服务的领跑者。在立足于网上支付的同时，YeePay易宝不断创新，将互联网、手机、固定电话整合在一个平台上，继短信支付、手机充值之后，首家推出了YeePay易宝电话支付业务，真正实现了离线支付，为更多传统行业搭建了电子支付的高速公路。YeePay易宝融合世界先进的电子支付文化，聚合众多金融、电信、IT、互联网等领域内的巨擘，旨在通过创新的支付机制，推动中国电子商务新进程。YeePay易宝致力于成为世界一流的电子支付应用和服务提供商，专注于金融增值服务和移动增值服务两大领域，创新并推广多元化、低成本的、安全有效的支付服务。<input type=\"button\" name=\"Submit\" value=\"立即注册\" onclick=\"window.open(\'https://www.yeepay.com/selfservice/requestRegister.action\')\" />',2,'a:2:{s:10:\"yp_account\";a:4:{s:5:\"title\";s:8:\"商户编号\";s:11:\"description\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}s:6:\"yp_key\";a:4:{s:5:\"title\";s:8:\"商户密钥\";s:11:\"description\";s:0:\"\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}}',0,0,1);
/*!40000 ALTER TABLE `dede_payment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_plus`
--

DROP TABLE IF EXISTS `dede_plus`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_plus` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `plusname` varchar(30) NOT NULL default '',
  `menustring` varchar(200) NOT NULL default '',
  `mainurl` varchar(50) NOT NULL default '',
  `writer` varchar(30) NOT NULL default '',
  `isshow` smallint(6) NOT NULL default '1',
  `filelist` text,
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_plus`
--

LOCK TABLES `dede_plus` WRITE;
/*!40000 ALTER TABLE `dede_plus` DISABLE KEYS */;
INSERT INTO `dede_plus` VALUES (27,'友情链接模块','<m:item name=\'友情链接\' link=\'friendlink_main.php\' rank=\'plus_友情链接\' target=\'main\' />','','织梦团队',1,''),(24,'文件管理器','<m:item name=\'文件管理器\' link=\'file_manage_main.php\' rank=\'plus_文件管理器\' target=\'main\' />','','织梦团队',1,''),(23,'百度新闻','<m:item name=\'百度新闻\' link=\'baidunews.php\' rank=\'plus_百度新闻\' target=\'main\' />','','织梦团队',1,'baidunews.php'),(28,'投票模块','<m:item name=\'投票模块\' link=\'vote_main.php\' rank=\'plus_投票模块\' target=\'main\' />','','织梦团队',1,''),(25,'广告管理','<m:item name=\'广告管理\' link=\'ad_main.php\' rank=\'plus_广告管理\' target=\'main\' />','','织梦官方',1,''),(10,'挑错管理','<m:item name=\'挑错管理\' link=\'erraddsave.php\' rank=\'plus_挑错管理\' target=\'main\' />','','织梦团队',1,'<m:item name=\'挑错管理\' link=\'catalog_do.php?dopost=erraddsave.php\' rank=\'plus_挑错管理\' target=\'main\' />'),(29,'留言簿模块','<m:item name=\'留言簿模块\' link=\'catalog_do.php?dopost=guestbook\' rank=\'plus_留言簿模块\' target=\'main\' />','','织梦团队',1,'');
/*!40000 ALTER TABLE `dede_plus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_pwd_tmp`
--

DROP TABLE IF EXISTS `dede_pwd_tmp`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_pwd_tmp` (
  `mid` mediumint(8) NOT NULL,
  `membername` char(16) NOT NULL default '',
  `pwd` char(32) NOT NULL default '',
  `mailtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_pwd_tmp`
--

LOCK TABLES `dede_pwd_tmp` WRITE;
/*!40000 ALTER TABLE `dede_pwd_tmp` DISABLE KEYS */;
INSERT INTO `dede_pwd_tmp` VALUES (0,'test','9d919b59b2fe2dddb59770d29fdae06a',1292902905);
/*!40000 ALTER TABLE `dede_pwd_tmp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_ratings`
--

DROP TABLE IF EXISTS `dede_ratings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_ratings` (
  `id` varchar(11) NOT NULL,
  `total_votes` int(11) NOT NULL default '0',
  `total_value` int(11) NOT NULL default '0',
  `used_ips` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_ratings`
--

LOCK TABLES `dede_ratings` WRITE;
/*!40000 ALTER TABLE `dede_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_scores`
--

DROP TABLE IF EXISTS `dede_scores`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_scores` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `titles` char(15) NOT NULL,
  `icon` smallint(6) unsigned default '0',
  `integral` int(10) NOT NULL default '0',
  `isdefault` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `integral` (`integral`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_scores`
--

LOCK TABLES `dede_scores` WRITE;
/*!40000 ALTER TABLE `dede_scores` DISABLE KEYS */;
INSERT INTO `dede_scores` VALUES (2,'列兵',1,0,1),(3,'班长',2,1000,1),(4,'少尉',3,2000,1),(5,'中尉',4,3000,1),(6,'上尉',5,4000,1),(7,'少校',6,5000,1),(8,'中校',7,6000,1),(9,'上校',8,9000,1),(10,'少将',9,14000,1),(11,'中将',10,19000,1),(12,'上将',11,24000,1),(15,'大将',12,29000,1);
/*!40000 ALTER TABLE `dede_scores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_search_cache`
--

DROP TABLE IF EXISTS `dede_search_cache`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_search_cache` (
  `hash` char(32) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL default '0',
  `rsnum` mediumint(8) unsigned NOT NULL default '0',
  `ids` mediumtext,
  PRIMARY KEY  (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_search_cache`
--

LOCK TABLES `dede_search_cache` WRITE;
/*!40000 ALTER TABLE `dede_search_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_search_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_search_keywords`
--

DROP TABLE IF EXISTS `dede_search_keywords`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_search_keywords` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `keyword` char(30) NOT NULL default '',
  `spwords` char(50) NOT NULL default '',
  `count` mediumint(8) unsigned NOT NULL default '1',
  `result` mediumint(8) unsigned NOT NULL default '0',
  `lasttime` int(10) unsigned NOT NULL default '0',
  `channelid` smallint(5) unsigned NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_search_keywords`
--

LOCK TABLES `dede_search_keywords` WRITE;
/*!40000 ALTER TABLE `dede_search_keywords` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_search_keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sgpage`
--

DROP TABLE IF EXISTS `dede_sgpage`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sgpage` (
  `aid` smallint(5) unsigned NOT NULL auto_increment,
  `title` varchar(60) NOT NULL default '',
  `ismake` smallint(6) NOT NULL default '1',
  `filename` varchar(60) NOT NULL default '',
  `keywords` varchar(30) NOT NULL default '',
  `template` varchar(30) NOT NULL default '',
  `likeid` varchar(20) NOT NULL default '',
  `description` varchar(250) NOT NULL default '',
  `uptime` int(10) unsigned NOT NULL default '0',
  `body` mediumtext,
  PRIMARY KEY  (`aid`),
  KEY `ismake` (`ismake`,`uptime`),
  KEY `likeid` (`likeid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sgpage`
--

LOCK TABLES `dede_sgpage` WRITE;
/*!40000 ALTER TABLE `dede_sgpage` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_sgpage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_shops_delivery`
--

DROP TABLE IF EXISTS `dede_shops_delivery`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_shops_delivery` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `dname` char(80) NOT NULL,
  `price` float(13,2) NOT NULL default '0.00',
  `des` char(255) default NULL,
  `orders` int(10) NOT NULL default '0',
  PRIMARY KEY  (`pid`),
  KEY `orders` (`orders`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_shops_delivery`
--

LOCK TABLES `dede_shops_delivery` WRITE;
/*!40000 ALTER TABLE `dede_shops_delivery` DISABLE KEYS */;
INSERT INTO `dede_shops_delivery` VALUES (1,'送货上门',10.21,'送货上门,领取商品时付费.',0),(2,'特快专递（EMS）',25.00,'特快专递（EMS）,要另收手续费.',0),(3,'普通邮递',5.00,'普通邮递',0),(4,'邮局快邮',12.00,'邮局快邮',0);
/*!40000 ALTER TABLE `dede_shops_delivery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_shops_orders`
--

DROP TABLE IF EXISTS `dede_shops_orders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_shops_orders` (
  `oid` varchar(80) NOT NULL,
  `userid` int(10) NOT NULL,
  `pid` int(10) NOT NULL default '0',
  `paytype` tinyint(2) NOT NULL default '1',
  `cartcount` int(10) NOT NULL default '0',
  `dprice` float(13,2) NOT NULL default '0.00',
  `price` float(13,2) NOT NULL default '0.00',
  `priceCount` float(13,2) NOT NULL,
  `state` tinyint(1) NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `stime` int(10) NOT NULL default '0',
  KEY `stime` (`stime`),
  KEY `userid` (`userid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_shops_orders`
--

LOCK TABLES `dede_shops_orders` WRITE;
/*!40000 ALTER TABLE `dede_shops_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_shops_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_shops_products`
--

DROP TABLE IF EXISTS `dede_shops_products`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_shops_products` (
  `aid` mediumint(8) NOT NULL default '0',
  `oid` varchar(80) NOT NULL default '',
  `userid` int(10) NOT NULL,
  `title` varchar(80) NOT NULL default '',
  `price` float(13,2) NOT NULL default '0.00',
  `buynum` int(10) NOT NULL default '9',
  KEY `oid` (`oid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_shops_products`
--

LOCK TABLES `dede_shops_products` WRITE;
/*!40000 ALTER TABLE `dede_shops_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_shops_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_shops_userinfo`
--

DROP TABLE IF EXISTS `dede_shops_userinfo`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_shops_userinfo` (
  `userid` int(10) NOT NULL,
  `oid` varchar(80) NOT NULL default '',
  `consignee` char(15) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `zip` int(10) NOT NULL default '0',
  `tel` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `des` varchar(255) NOT NULL default '',
  KEY `oid` (`oid`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_shops_userinfo`
--

LOCK TABLES `dede_shops_userinfo` WRITE;
/*!40000 ALTER TABLE `dede_shops_userinfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_shops_userinfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_softconfig`
--

DROP TABLE IF EXISTS `dede_softconfig`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_softconfig` (
  `downtype` smallint(6) NOT NULL default '0',
  `ismoresite` smallint(6) NOT NULL default '0',
  `gotojump` smallint(6) NOT NULL default '0',
  `islocal` smallint(5) unsigned NOT NULL default '1',
  `sites` text,
  `downmsg` text,
  `moresitedo` smallint(5) unsigned NOT NULL default '1',
  `dfrank` smallint(5) unsigned NOT NULL default '0',
  `dfywboy` smallint(5) unsigned NOT NULL default '0',
  `argrange` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`downtype`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_softconfig`
--

LOCK TABLES `dede_softconfig` WRITE;
/*!40000 ALTER TABLE `dede_softconfig` DISABLE KEYS */;
INSERT INTO `dede_softconfig` VALUES (0,1,1,1,'http://www.aaa.com | 镜像地址一\r\nhttp://www.bbb.com | 镜像地址二\r\nhttp://www.ccc.com | 镜像地址三\r\n','<p>☉推荐使用第三方专业下载工具下载本站软件，使用 WinRAR v3.10 以上版本解压本站软件。<br />\r\n☉如果这个软件总是不能下载的请点击报告错误,谢谢合作!!<br />\r\n☉下载本站资源，如果服务器暂不能下载请过一段时间重试！<br />\r\n☉如果遇到什么问题，请到本站论坛去咨寻，我们将在那里提供更多 、更好的资源！<br />\r\n☉本站提供的一些商业软件是供学习研究之用，如用于商业用途，请购买正版。</p>',0,0,0,0);
/*!40000 ALTER TABLE `dede_softconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_stepselect`
--

DROP TABLE IF EXISTS `dede_stepselect`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_stepselect` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `itemname` char(30) default '',
  `egroup` char(20) default '',
  `issign` tinyint(1) unsigned default '0',
  `issystem` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_stepselect`
--

LOCK TABLES `dede_stepselect` WRITE;
/*!40000 ALTER TABLE `dede_stepselect` DISABLE KEYS */;
INSERT INTO `dede_stepselect` VALUES (1,'血型','blood',1,1),(2,'体型','bodytype',1,1),(3,'公司规模','cosize',1,1),(4,'交友','datingtype',1,1),(5,'是否饮酒','drink',1,1),(6,'教育程度','education',1,1),(7,'住房','house',1,1),(8,'收入','income',1,1),(9,'婚姻','marital',1,1),(10,'是否抽烟','smoke',1,1),(11,'星座','star',1,1),(12,'系统缓存标识','system',1,1),(13,'行业','vocation',0,0),(14,'地区','nativeplace',0,0),(15,'信息类型','infotype',0,0);
/*!40000 ALTER TABLE `dede_stepselect` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sys_enum`
--

DROP TABLE IF EXISTS `dede_sys_enum`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sys_enum` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `ename` char(30) NOT NULL default '',
  `evalue` smallint(6) NOT NULL default '0',
  `egroup` char(20) NOT NULL default '',
  `disorder` smallint(5) unsigned NOT NULL default '0',
  `issign` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=140 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sys_enum`
--

LOCK TABLES `dede_sys_enum` WRITE;
/*!40000 ALTER TABLE `dede_sys_enum` DISABLE KEYS */;
INSERT INTO `dede_sys_enum` VALUES (139,'cms制作',503,'vocation',503,0),(39,'租房',1,'house',0,1),(40,'一房以上',2,'house',0,1),(41,'两房以上',3,'house',0,1),(42,'大户/别墅',4,'house',0,1),(43,'低于1000元',1,'income',0,1),(44,'1000元以上',2,'income',0,1),(45,'2000元以上',3,'income',0,1),(46,'4000元以上',4,'income',0,1),(47,'8000元以上',5,'income',0,1),(48,'15000以上',6,'income',0,1),(49,'初中以上',1,'education',0,1),(50,'高中/中专',2,'education',0,1),(51,'大学专科',3,'education',0,1),(52,'大学本科',4,'education',0,1),(53,'硕士',5,'education',0,1),(54,'博士以上',6,'education',0,1),(55,'仅用于判断缓存是否存在',0,'system',0,1),(56,'白羊座',1,'star',0,1),(57,'金牛座',2,'star',0,1),(58,'双子座',3,'star',0,1),(59,'巨蟹座',4,'star',0,1),(60,'狮子座',5,'star',0,1),(61,'处女座',6,'star',0,1),(62,'天枰座',7,'star',0,1),(63,'天蝎座',8,'star',0,1),(64,'射手座',9,'star',0,1),(65,'摩羯座',10,'star',0,1),(66,'水瓶座',11,'star',0,1),(67,'双鱼座',12,'star',0,1),(68,'不吸烟',1,'smoke',0,1),(69,'偶尔吸一点',2,'smoke',0,1),(70,'抽得很凶',3,'smoke',0,1),(71,'不喝酒',1,'drink',0,1),(72,'偶尔喝一点',2,'drink',0,1),(73,'喝得很凶',3,'drink',0,1),(74,'A',1,'blood',0,1),(75,'B',2,'blood',0,1),(76,'AB',3,'blood',0,1),(77,'O',4,'blood',0,1),(78,'未婚',1,'marital',0,1),(79,'已婚',2,'marital',0,1),(80,'离异',3,'marital',0,1),(81,'丧偶',4,'marital',0,1),(82,'匀称',1,'bodytype',0,1),(83,'苗条',2,'bodytype',0,1),(84,'健壮',3,'bodytype',0,1),(85,'略胖',4,'bodytype',0,1),(86,'丰满',5,'bodytype',0,1),(87,'瘦小',6,'bodytype',0,1),(88,'高瘦',7,'bodytype',0,1),(89,'网友',1,'datingtype',0,1),(90,'恋人',2,'datingtype',0,1),(91,'玩伴',3,'datingtype',0,1),(92,'共同兴趣',4,'datingtype',0,1),(93,'男性朋友',5,'datingtype',0,1),(94,'女性朋友',6,'datingtype',0,1),(95,'50人以下',1,'cosize',0,1),(96,'50-200人',2,'cosize',0,1),(97,'200-500人',3,'cosize',0,1),(98,'500-2000人',4,'cosize',0,1),(99,'2000-5000人',5,'cosize',0,1),(100,'5000人以上',6,'cosize',0,1),(101,'广州市',500,'nativeplace',500,0),(102,'中山市',1000,'nativeplace',1000,0),(103,'天河区',501,'nativeplace',501,0),(104,'越秀区',502,'nativeplace',502,0),(106,'海珠区',503,'nativeplace',503,0),(107,'石岐区',1001,'nativeplace',1001,0),(108,'西区',1002,'nativeplace',1002,0),(109,'东区',1003,'nativeplace',1003,0),(110,'小榄镇',1004,'nativeplace',1004,0),(111,'商品',500,'infotype',500,0),(112,'租房',1000,'infotype',1000,0),(113,'交友',1500,'infotype',1500,0),(114,'招聘',2000,'infotype',2000,0),(115,'求职',2500,'infotype',2500,0),(116,'票务',3000,'infotype',3000,0),(117,'服务',3500,'infotype',3500,0),(118,'培训',4000,'infotype',4000,0),(119,'出售',501,'infotype',501,0),(121,'求购',502,'infotype',502,0),(122,'交换',503,'infotype',503,0),(123,'合作',504,'infotype',504,0),(124,'出租',1001,'infotype',1001,0),(125,'求租',1002,'infotype',1002,0),(126,'合租',1003,'infotype',1003,0),(127,'找帅哥',1501,'infotype',1501,0),(128,'找美女',1502,'infotype',1502,0),(129,'纯友谊',1503,'infotype',1503,0),(130,'玩伴',1504,'infotype',1504,0),(131,'互联网',500,'vocation',500,0),(132,'网站制作',501,'vocation',501,0),(133,'机械',1000,'vocation',1000,0),(134,'农业机械',1001,'vocation',1001,0),(135,'机床',1002,'vocation',1002,0),(136,'纺织设备和器材',1003,'vocation',1003,0),(137,'风机/排风设备',1004,'vocation',1004,0),(138,'虚心',502,'vocation',502,0);
/*!40000 ALTER TABLE `dede_sys_enum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sys_module`
--

DROP TABLE IF EXISTS `dede_sys_module`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sys_module` (
  `id` int(11) NOT NULL auto_increment,
  `hashcode` char(32) NOT NULL default '',
  `modname` varchar(30) NOT NULL default '',
  `indexname` varchar(20) NOT NULL default '',
  `indexurl` varchar(30) NOT NULL default '',
  `ismember` tinyint(4) NOT NULL default '1',
  `menustring` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sys_module`
--

LOCK TABLES `dede_sys_module` WRITE;
/*!40000 ALTER TABLE `dede_sys_module` DISABLE KEYS */;
INSERT INTO `dede_sys_module` VALUES (1,'0cce60bc0238aa03804682c801584991','百度新闻','','',0,''),(2,'1f35620fb42d452fa2bdc1dee1690f92','文件管理器','','',0,''),(3,'72ffa6fabe3c236f9238a2b281bc0f93','广告管理','','',0,''),(4,'b437d85a7a7bc778c9c79b5ec36ab9aa','友情链接','','',0,''),(5,'acb8b88eb4a6d4bfc375c18534f9439e','投票模块','','',0,''),(6,'572606600345b1a4bb8c810bbae434cc','挑错管理','','',0,''),(7,'0a7bea5dbe571d35def883cbec796437','留言簿模块','','',0,''),(8,'59155be87ea60c5c65ec1f7a46a0fc9d','手机WAP浏览','','',0,'');
/*!40000 ALTER TABLE `dede_sys_module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sys_set`
--

DROP TABLE IF EXISTS `dede_sys_set`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sys_set` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `sname` char(20) NOT NULL default '',
  `items` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sys_set`
--

LOCK TABLES `dede_sys_set` WRITE;
/*!40000 ALTER TABLE `dede_sys_set` DISABLE KEYS */;
INSERT INTO `dede_sys_set` VALUES (1,'nature','性格外向,性格内向,活泼开朗,沉默寡言,幽默,稳重,轻浮,冲动,坚强,脆弱,幼稚,成熟,能说会道,自私,真诚,独立,依赖,任性,自负,自卑,温柔体贴,神经质,拜金,小心翼翼,暴躁,倔强,逆来顺受,不拘小节,婆婆妈妈,交际广泛,豪爽,害羞,狡猾善变,耿直,虚伪,乐观向上,悲观消极,郁郁寡欢,孤僻,难以琢磨,胆小怕事,敢做敢当,助人为乐,老实,守旧,敏感,迟钝,武断,果断,优柔寡断,暴力倾向,刻薄,损人利己,附庸风雅,时喜时悲,患得患失,快言快语,豪放不羁,多愁善感,循规蹈矩'),(2,'language','普通话,上海话,广东话,英语,日语,韩语,法语,意大利语,德语,西班牙语,俄语,阿拉伯语');
/*!40000 ALTER TABLE `dede_sys_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sys_task`
--

DROP TABLE IF EXISTS `dede_sys_task`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sys_task` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `taskname` varchar(50) NOT NULL,
  `dourl` varchar(100) NOT NULL,
  `islock` tinyint(1) unsigned NOT NULL default '0',
  `runtype` tinyint(1) unsigned NOT NULL default '0',
  `runtime` varchar(10) default '0000',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `freq` tinyint(2) unsigned NOT NULL default '0',
  `lastrun` int(10) unsigned NOT NULL default '0',
  `description` varchar(250) NOT NULL,
  `parameter` text,
  `settime` int(10) unsigned NOT NULL default '0',
  `sta` enum('运行','成功','失败') default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sys_task`
--

LOCK TABLES `dede_sys_task` WRITE;
/*!40000 ALTER TABLE `dede_sys_task` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_sys_task` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_sysconfig`
--

DROP TABLE IF EXISTS `dede_sysconfig`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_sysconfig` (
  `aid` smallint(8) unsigned NOT NULL default '0',
  `varname` varchar(20) NOT NULL default '',
  `info` varchar(100) NOT NULL default '',
  `groupid` smallint(6) NOT NULL default '1',
  `type` varchar(10) NOT NULL default 'string',
  `value` text,
  PRIMARY KEY  (`varname`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_sysconfig`
--

LOCK TABLES `dede_sysconfig` WRITE;
/*!40000 ALTER TABLE `dede_sysconfig` DISABLE KEYS */;
INSERT INTO `dede_sysconfig` VALUES (1,'cfg_basehost','站点根网址',1,'string','http://localhost'),(2,'cfg_cmspath','DedeCMS安装目录',2,'string',''),(3,'cfg_cookie_encode','cookie加密码',2,'string','YbAUb4438X'),(4,'cfg_indexurl','网页主页链接',1,'string','/'),(5,'cfg_backup_dir','数据备份目录（在data目录内）',2,'string','backupdata'),(6,'cfg_indexname','主页链接名',1,'string','主页'),(7,'cfg_webname','网站名称',1,'string','旺吉祥'),(8,'cfg_adminemail','网站发信EMAIL',2,'string','admin@ewbsite.com'),(9,'cfg_html_editor','Html编辑器选项（仅支持 dede 和 fck）',2,'string','fck'),(10,'cfg_arcdir','文档HTML默认保存路径',1,'string','/a'),(11,'cfg_medias_dir','图片/上传文件默认路径',1,'string','/uploads'),(12,'cfg_ddimg_width','缩略图默认宽度',3,'number','240'),(13,'cfg_ddimg_height','缩略图默认高度',3,'number','180'),(63,'cfg_album_width','图集默认显示图片的大小',3,'number','800'),(15,'cfg_imgtype','图片浏览器文件类型',3,'string','jpg|gif|png'),(16,'cfg_softtype','允许上传的软件类型',3,'bstring','zip|gz|rar|iso|doc|xsl|ppt|wps'),(17,'cfg_mediatype','允许的多媒体文件类型',3,'bstring','swf|mpg|mp3|rm|rmvb|wmv|wma|wav|mid|mov'),(18,'cfg_specnote','专题的最大节点数',2,'number','6'),(19,'cfg_list_symbol','栏目位置的间隔符号',2,'string',' > '),(20,'cfg_notallowstr','禁用词语（系统将直接停止用户动作）<br/>用|分开，但不要在结尾加|',5,'bstring','非典|艾滋病|阳痿'),(21,'cfg_feedbackcheck','评论及留言(是/否)需审核',5,'bool','N'),(22,'cfg_keyword_replace','关键字替换(是/否)使用本功能会影响HTML生成速度',2,'bool','Y'),(23,'cfg_fck_xhtml','编辑器(是/否)使用XHTML',1,'bool','Y'),(24,'cfg_df_style','模板默认风格',1,'string','default'),(25,'cfg_multi_site','(是/否)支持多站点，开启此项后附件、栏目连接、arclist内容启用绝对网址',2,'bool','N'),(58,'cfg_rm_remote','远程图片本地化',7,'bool','Y'),(27,'cfg_dede_log','(是/否)开启管理日志',2,'bool','N'),(28,'cfg_powerby','网站版权信息',1,'bstring',''),(722,'cfg_jump_once','跳转网址是否直接跳转？（否则显示中转页）',7,'bool','Y'),(723,'cfg_task_pwd','系统计划任务客户端许可密码<br/>(需要客户端，通常不会太重要)',7,'string',''),(29,'cfg_arcsptitle','(是/否)开启分页标题，开启会影响HTML生成速度',6,'bool','N'),(30,'cfg_arcautosp','(是/否)开启长文章自动分页',6,'bool','N'),(31,'cfg_arcautosp_size','文章自动分页大小（单位: K）',6,'number','5'),(32,'cfg_auot_description','自动摘要长度（0-250，0表示不启用）',7,'number','240'),(33,'cfg_ftp_host','FTP主机',2,'string',''),(34,'cfg_ftp_port','FTP端口',2,'number','21'),(35,'cfg_ftp_user','FTP用户名',2,'string',''),(36,'cfg_ftp_pwd','FTP密码',2,'string',''),(37,'cfg_ftp_root','网站根在FTP中的目录',2,'string','/'),(38,'cfg_ftp_mkdir','是否强制用FTP创建目录',2,'bool','N'),(39,'cfg_feedback_ck','评论加验证码重确认',5,'bool','Y'),(40,'cfg_list_son','上级列表是否包含子类内容',6,'bool','Y'),(41,'cfg_mb_open','是否开启会员功能',4,'bool','Y'),(42,'cfg_mb_album','是否开启会员图集功能',4,'bool','Y'),(43,'cfg_mb_upload','是否允许会员上传非图片附件',4,'bool','Y'),(44,'cfg_mb_upload_size','会员上传文件大小(K)',4,'number','1024'),(45,'cfg_mb_sendall','是否开放会员对自定义模型投稿',4,'bool','Y'),(46,'cfg_mb_rmdown','是否把会员指定的远程文档下载到本地',4,'bool','Y'),(47,'cfg_cli_time','服务器时区设置',2,'number','8'),(48,'cfg_mb_addontype','会员附件许可的类型',4,'bstring','swf|mpg|mp3|rm|rmvb|wmv|wma|wav|mid|mov|zip|rar|doc|xsl|ppt|wps'),(49,'cfg_mb_max','会员附件总大小限制(MB)',4,'number','500'),(20,'cfg_replacestr','替换词语（词语会被替换成***）<br/>用|分开，但不要在结尾加|',5,'bstring','她妈|它妈|他妈|你妈|去死|贱人'),(719,'cfg_makeindex','发布文章后马上更新网站主页',6,'bool','N'),(51,'cfg_keyword_like','使用关键词关连文章',6,'bool','N'),(52,'cfg_index_max','网站主页调用函数最大索引文档数<br>不适用于经常单栏目采集过多内容的网站<br>不启用本项此值设置为0即可',6,'number','10000'),(53,'cfg_index_cache','arclist标签调用缓存<br />(0 不启用，大于0值为多少秒)',6,'number','86400'),(54,'cfg_tplcache','是否启用模板缓存',6,'bool','Y'),(55,'cfg_tplcache_dir','模板缓存目录',6,'string','/data/tplcache'),(56,'cfg_makesign_cache','发布/修改单个文档是否使用调用缓存',6,'bool','N'),(59,'cfg_arc_dellink','删除非站内链接',7,'bool','N'),(60,'cfg_arc_autopic','提取第一张图片作为缩略图',7,'bool','Y'),(61,'cfg_arc_autokeyword','自动提取关键字',7,'bool','Y'),(62,'cfg_title_maxlen','文档标题最大长度<br>改此参数后需要手工修改数据表',7,'number','60'),(64,'cfg_check_title','发布文档时是否检测重复标题',7,'bool','Y'),(65,'cfg_album_row','图集多行多列样式默认行数',3,'number','3'),(66,'cfg_album_col','图集多行多列样式默认列数',3,'number','4'),(67,'cfg_album_pagesize','图集多页多图每页显示最大数',3,'number','12'),(68,'cfg_album_style','图集默认样式<br />1为多页多图,2为多页单图,3为缩略图列表',3,'number','2'),(69,'cfg_album_ddwidth','图集默认缩略图大小',3,'number','200'),(70,'cfg_mb_notallow','不允许注册的会员id',4,'bstring','www,bbs,ftp,mail,user,users,admin,administrator'),(71,'cfg_mb_idmin','用户id最小长度',4,'number','3'),(72,'cfg_mb_pwdmin','用户密码最小长度',4,'number','3'),(73,'cfg_md_idurl','是否严格限定会员登录id<br>允许会员使用二级域名必须设置此项',4,'bool','N'),(74,'cfg_mb_rank','注册会员默认级别<br>[会员权限管理中]查看级别代表的数字',4,'number','10'),(76,'cfg_feedback_time','两次评论至少间隔时间(秒钟)',5,'number','30'),(77,'cfg_feedback_numip','每个IP一小时内最大评论数',5,'number','30'),(78,'cfg_md_mailtest','是否限制Email只能注册一个帐号',4,'bool','N'),(79,'cfg_mb_spacesta','会员使用权限开通状态<br>(-10 邮件验证 -1 手工审核, 0 没限制)',4,'number','0'),(728,'cfg_mb_allowreg','是否允许新会员注册',4,'bool','Y'),(729,'cfg_mb_adminlock','是否禁止访问管理员帐号的空间',4,'bool','N'),(81,'cfg_vdcode_member','会员投稿是否使用验证码',5,'bool','Y'),(83,'cfg_mb_cktitle','会员投稿是否检测重复标题',5,'bool','Y'),(84,'cfg_mb_editday','投稿多长时间后不能再修改[天]',5,'number','7'),(729,'cfg_sendarc_scores','投稿可获取积分',5,'number','10'),(88,'cfg_caicai_sub','被踩扣除文章好评度',5,'number','2'),(89,'cfg_caicai_add','被顶扣除文章好评度',5,'number','2'),(90,'cfg_feedback_add','详细好评可获好评度',5,'number','5'),(91,'cfg_feedback_sub','详细恶评扣除好评度',5,'number','5'),(730,'cfg_sendfb_scores','参与评论可获积分',5,'number','3'),(92,'cfg_search_max','最大搜索检查文档数',6,'number','50000'),(93,'cfg_search_maxrc','最大返回搜索结果数',6,'number','300'),(94,'cfg_search_time','搜索间隔时间(秒/对网站所有用户)',6,'number','3'),(95,'cfg_baidunews_limit','百度新闻xml更新新闻数量 最大100',8,'string','100'),(223,'cfg_smtp_port','smtp服务器端口',2,'string','25'),(221,'cfg_sendmail_bysmtp','是否启用smtp方式发送邮件',2,'bool','Y'),(222,'cfg_smtp_server','smtp服务器',2,'string','smtp.qq.com'),(224,'cfg_smtp_usermail','SMTP服务器的用户邮箱',2,'string',''),(225,'cfg_smtp_user','SMTP服务器的用户帐号',2,'string',''),(226,'cfg_smtp_password','SMTP服务器的用户密码',2,'string',''),(96,'cfg_updateperi','百度新闻xml更新时间 （单位：分钟）',8,'string','15'),(227,'cfg_online_type','在线支付网关类型',2,'string','nps'),(706,'cfg_upload_switch','删除文章文件同时删除相关附件文件',2,'bool','Y'),(708,'cfg_rewrite','是否使用伪静态',2,'bool','N'),(707,'cfg_allsearch_limit','网站全局搜索时间限制',2,'string','1'),(709,'cfg_delete','文章回收站(是/否)开启',2,'bool','Y'),(710,'cfg_keywords','站点默认关键字',1,'string',''),(711,'cfg_description','站点描述',1,'bstring',''),(712,'cfg_beian','网站备案号',1,'string',''),(713,'cfg_need_typeid2','是否启用副栏目',6,'bool','Y'),(72,'cfg_mb_pwdtype','前台密码验证类型：默认32 — 32位md5，可选：<br />l16 — 前16位， r16 — 后16位， m16 — 中间16位',4,'string','32'),(716,'cfg_cache_type','id 文档ID，content 标签最终内容<br />(修改此变量后必须更新系统缓存)',6,'string','id'),(717,'cfg_max_face','会员上传头像大小限制(单位：KB)',3,'number','1024'),(718,'cfg_typedir_df','栏目网址使用目录名（不显示默认页，即是 /a/abc/ 形式）',2,'bool','Y'),(719,'cfg_make_andcat','发表文章后马上更新相关栏目',6,'bool','N'),(720,'cfg_make_prenext','发表文章后马上更新上下篇',6,'bool','Y'),(721,'cfg_feedback_forbid','是否禁止所有评论(将包括禁止顶踩等)',5,'bool','N'),(724,'cfg_addon_domainbind','附件目录是否绑定为指定的二级域名',7,'bool','N'),(725,'cfg_addon_domain','附件目录的二级域名',7,'string',''),(726,'cfg_df_dutyadmin','默认责任编辑(dutyadmin)',7,'string','admin'),(727,'cfg_mb_allowncarc','是否允许用户空间显示未审核文章',4,'bool','Y'),(730,'cfg_mb_spaceallarc','会员空间中所有文档的频道ID(不限为0)',4,'number','0'),(731,'cfg_face_adds','上传头像增加积分',5,'number','10'),(732,'cfg_moreinfo_adds','填写详细资料增加积分',5,'number','20'),(733,'cfg_money_scores','多少积分可以兑换一个金币',5,'number','50'),(734,'cfg_mb_wnameone','是否允许用户笔名/昵称重复',4,'bool','N'),(735,'cfg_arc_dirname','是否允许用目录作为文档文件名<br />文档命名规则需改为：{typedir}/{aid}/index.html',7,'bool','Y'),(736,'cfg_puccache_time','需缓存内容全局缓存时间(秒)',6,'number','36000'),(737,'cfg_arc_click','文档默认点击数(-1表示随机50-200)',7,'number','-1'),(738,'cfg_addon_savetype','附件保存形式(按data函数日期参数)',3,'string','ymd'),(739,'cfg_qk_uploadlit','异步上传缩略图(空间太不稳定的用户关闭此项)',3,'bool','Y'),(740,'cfg_login_adds','登录会员中心获积分',5,'number','2'),(741,'cfg_userad_adds','会员推广获积分',5,'number','10'),(742,'cfg_ddimg_full','缩略图是否使用强制大小(对背景填充)',3,'bool','N'),(743,'cfg_ddimg_bgcolor','缩略图空白背景填充颜色(0-白,1-黑)',3,'number','0'),(744,'cfg_replace_num','文档内容同一关键词替换次数(0为全部替换)',7,'number','2'),(745,'cfg_uplitpic_cut','上传缩略图后是否马上弹出裁剪框',3,'bool','Y'),(746,'cfg_album_mark','图集是否使用水印(小图也会受影响)',3,'bool','N'),(747,'cfg_mb_feedcheck','会员动态是否需要审核',4,'bool','N'),(748,'cfg_mb_msgischeck','会员状态是否需要审核',4,'bool','N'),(749,'cfg_mb_reginfo','注册是否需要完成详细资料的填写',4,'bool','N'),(750,'cfg_remote_site','是否启用远程站点',2,'bool','N'),(751,'cfg_title_site','是否发布和编辑文档时远程发布(启用远程站点的前提下)',2,'bool','N');
/*!40000 ALTER TABLE `dede_sysconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_tagindex`
--

DROP TABLE IF EXISTS `dede_tagindex`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_tagindex` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag` char(12) NOT NULL default '',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `count` int(10) unsigned NOT NULL default '0',
  `total` int(10) unsigned NOT NULL default '0',
  `weekcc` int(10) unsigned NOT NULL default '0',
  `monthcc` int(10) unsigned NOT NULL default '0',
  `weekup` int(10) unsigned NOT NULL default '0',
  `monthup` int(10) unsigned NOT NULL default '0',
  `addtime` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_tagindex`
--

LOCK TABLES `dede_tagindex` WRITE;
/*!40000 ALTER TABLE `dede_tagindex` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_tagindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_taglist`
--

DROP TABLE IF EXISTS `dede_taglist`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_taglist` (
  `tid` int(10) unsigned NOT NULL default '0',
  `aid` int(10) unsigned NOT NULL default '0',
  `arcrank` smallint(6) NOT NULL default '0',
  `typeid` smallint(5) unsigned NOT NULL default '0',
  `tag` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`tid`,`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_taglist`
--

LOCK TABLES `dede_taglist` WRITE;
/*!40000 ALTER TABLE `dede_taglist` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_taglist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_uploads`
--

DROP TABLE IF EXISTS `dede_uploads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_uploads` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `arcid` mediumint(8) unsigned NOT NULL default '0',
  `title` char(60) NOT NULL default '',
  `url` char(80) NOT NULL default '',
  `mediatype` smallint(6) NOT NULL default '1',
  `width` char(10) NOT NULL default '',
  `height` char(10) NOT NULL default '',
  `playtime` char(10) NOT NULL default '',
  `filesize` mediumint(8) unsigned NOT NULL default '0',
  `uptime` int(10) unsigned NOT NULL default '0',
  `mid` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`aid`),
  KEY `memberid` (`mid`),
  KEY `arcid` (`arcid`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_uploads`
--

LOCK TABLES `dede_uploads` WRITE;
/*!40000 ALTER TABLE `dede_uploads` DISABLE KEYS */;
INSERT INTO `dede_uploads` VALUES (1,0,'1_101208170341_1.jpg','/uploads/allimg/101208/1_101208170341_1.jpg',1,'882','560','0',100118,1291799021,1),(2,1,'旺·吉祥美国行','/uploads/101210/1-101210115A11A.jpg',1,'580','645','0',78963,1291953411,1),(3,2,'最热商品','/uploads/101214/1-10121411553K44.jpg',1,'576','200','0',35424,1292298937,1),(4,2,'最热商品','/uploads/101214/1-101214120159160.jpg',1,'362','200','0',28025,1292299319,1),(5,2,'最热商品','/uploads/101214/1-101214121U3F0.png',1,'362','200','0',155272,1292300333,1),(6,3,'旺吉祥公仔（吉祥款）：进口绒布制作，内含高性能竹炭。','/uploads/101214/1-101214163112312.jpg',1,'127','122','0',10756,1292315472,1),(7,4,'特价商品1','/uploads/allimg/101217/1-10121G439520-L.jpg',1,'0','0','0',31199,1292567992,1),(8,5,'特价商品2','/uploads/allimg/101217/1-10121G440240-L.jpg',1,'0','0','0',30542,1292568024,1),(9,0,'1-10121G446240-L.jpg','/uploads/allimg/101217/1-10121G446240-L.jpg',1,'0','0','0',31199,1292568384,1),(10,6,'商城首页头条1','/uploads/allimg/101217/1-10121G44I70-L.jpg',1,'0','0','0',31199,1292568457,1),(11,7,'商城头条2','/uploads/allimg/101217/1-10121G450180-L.jpg',1,'0','0','0',30542,1292568618,1),(12,8,'人气商品','/uploads/allimg/101224/1-101224124P40-L.jpg',1,'0','0','0',7318,1293166084,1),(13,9,'家族成员','/uploads/allimg/101224/1-101224124T30-L.jpg',1,'0','0','0',8765,1293166123,1),(14,10,'精品造型','/uploads/allimg/101224/1-101224124Z60-L.jpg',1,'0','0','0',8168,1293166146,1),(17,11,'旺吉祥72变——导游','/uploads/110110/1-110110115109564.jpg',1,'1767','1242','0',785688,1294631469,1),(18,11,'旺吉祥72变——导游','/uploads/110110/1-110110115109564-lp.jpg',1,'0','0','0',15410,1294631767,1),(19,11,'旺吉祥72变——导游','/uploads/110110/1-110110115109564-lp.jpg',1,'0','0','0',14428,1294631839,1),(20,11,'旺吉祥72变——导游','/uploads/110110/1-110110123Z0100.jpg',1,'580','408','0',213344,1294634340,1),(42,0,'1_110111141642_1.png','/uploads/allimg/110111/1_110111141642_1.png',1,'881','471','0',84110,1294726602,1),(41,0,'1_110111141341_1.png','/uploads/allimg/110111/1_110111141341_1.png',1,'881','471','0',84110,1294726421,1),(40,0,'1_110111141218_1.jpg','/uploads/allimg/110111/1_110111141218_1.jpg',1,'882','560','0',249131,1294726338,1),(24,13,'新年就要来咯！','/uploads/allimg/110110/1-110110154F50-L.jpg',1,'0','0','0',130091,1294645625,1),(25,14,'旺吉祥72变厨师','/uploads/110111/1-110111103343958.jpg',1,'850','602','0',394296,1294713223,1),(26,14,'旺吉祥72变厨师','/uploads/110111/1-110111103520F2.jpg',1,'580','411','0',206194,1294713320,1),(27,15,'旺吉祥72变导演','/uploads/110111/1-110111104446255.jpg',1,'580','411','0',206194,1294713886,1),(28,15,'旺吉祥72变导演','/uploads/110111/1-110111104531150.jpg',1,'580','411','0',210563,1294713931,1),(29,16,'旺吉祥72变服务员','/uploads/110111/1-110111104Q2555.jpg',1,'580','415','0',200125,1294714092,1),(30,17,'旺吉祥72变花匠','/uploads/110111/1-110111105042Q1.jpg',1,'580','434','0',217507,1294714242,1),(31,18,'旺吉祥72变化学药剂师','/uploads/110111/1-11011110540H11.jpg',1,'580','456','0',237673,1294714447,1),(32,19,'旺吉祥72变画家','/uploads/110111/1-110111105623931.jpg',1,'580','417','0',195079,1294714583,1),(33,20,'旺吉祥72变街头舞者','/uploads/110111/1-1101111101455E.jpg',1,'580','410','0',261959,1294714905,1),(34,21,'旺吉祥72变教师','/uploads/110111/1-110111110426407.jpg',1,'580','439','0',185147,1294715066,1),(35,22,'旺吉祥72变司机','/uploads/110111/1-110111110A9251.jpg',1,'580','415','0',201248,1294715219,1),(36,23,'旺吉祥72变饲养员','/uploads/110111/1-110111110951S7.jpg',1,'580','414','0',207538,1294715391,1),(37,24,'旺吉祥72变特种兵','/uploads/110111/1-110111111150F1.jpg',1,'580','415','0',192690,1294715510,1),(38,25,'旺吉祥72变医生','/uploads/110111/1-110111111400249.jpg',1,'580','399','0',191485,1294715640,1),(39,26,'旺吉祥72变音乐家','/uploads/110111/1-11011111162Y40.jpg',1,'580','391','0',182962,1294715788,1),(43,0,'1_110111143940_1.png','/uploads/allimg/110111/1_110111143940_1.png',1,'881','466','0',77023,1294727980,1),(44,0,'1_110111150750_1.png','/uploads/allimg/110111/1_110111150750_1.png',1,'881','473','0',79298,1294729670,1),(45,0,'1_110111154518_1.png','/uploads/allimg/110111/1_110111154518_1.png',1,'881','487','0',93127,1294731918,1),(46,0,'1_110111173510_1.png','/uploads/allimg/110111/1_110111173510_1.png',1,'881','458','0',68522,1294738510,1),(47,0,'1_110117135115_1.png','/uploads/allimg/110117/1_110117135115_1.png',1,'881','523','0',99506,1295243475,1),(48,0,'1_110117135205_1.png','/uploads/allimg/110117/1_110117135205_1.png',1,'881','444','0',72010,1295243525,1),(49,0,'1_110117135313_1.png','/uploads/allimg/110117/1_110117135313_1.png',1,'881','448','0',67136,1295243593,1),(50,27,'菜包记','/uploads/allimg/110118/1-11011P932350-L.jpg',1,'0','0','0',256005,1295314355,1),(51,27,'菜包记','/uploads/110118/1-11011P93513335.jpg',1,'500','2036','0',256005,1295314513,1),(52,13,'新年就要来咯！','/uploads/110118/1-11011P952442F.jpg',1,'540','255','0',130091,1295315564,1),(53,28,'菜包记','/uploads/allimg/110118/1-11011P955070-L.jpg',1,'0','0','0',168485,1295315707,1),(54,28,'菜包记','/uploads/110118/1-11011P95F3613.jpg',1,'600','600','0',168485,1295315823,1),(55,0,'1_110118111814_1.png','/uploads/allimg/110118/1_110118111814_1.png',1,'881','444','0',73654,1295320694,1),(56,29,'旺吉祥马克杯','/uploads/allimg/110118/1-11011Q435030-L.jpg',1,'0','0','0',24817,1295332503,1),(57,31,'旺吉祥马克杯','/uploads/allimg/110118/1-11011Q441070-L.jpg',1,'0','0','0',24855,1295332867,1),(58,31,'旺吉祥马克杯','/uploads/110118/110118/1-11011Q44145O5.jpg',1,'127','122','0',24855,1295332905,1),(59,32,'旺吉祥马克杯（）','/uploads/110118/1-11011Q44615N6.jpg',1,'127','122','0',32723,1295333175,1),(60,33,'旺吉祥马克杯（糖葫芦）','/uploads/110118/1-11011Q4491I14.jpg',1,'127','122','0',24817,1295333357,1),(61,34,'旺吉祥马克杯（喝酸奶）','/uploads/110118/1-11011Q4512b02.jpg',1,'127','122','0',25823,1295333489,1),(62,35,'旺吉祥马克杯（）','/uploads/110118/1-11011Q45222E1.jpg',1,'127','122','0',29472,1295333542,1),(63,36,'旺吉祥马克杯（捉蝴蝶）','/uploads/110118/1-11011Q4531M59.jpg',1,'127','122','0',26839,1295333597,1),(64,37,'旺吉祥公仔（时尚款）：进口绒布制作，内含高性能竹炭。','/uploads/110118/1-11011Q45I0b2.jpg',1,'127','122','0',42753,1295333850,1),(65,0,'110118/1-11011Q53631a7.jpg','/uploads/110118/1-11011Q53631a7.jpg',1,'904','278','0',110903,1295336191,1),(66,38,'中部banner','/uploads/110118/1-11011Q53R0522.jpg',1,'904','278','0',110903,1295336300,1),(67,39,'旺吉祥纸杯（拜年款）','/uploads/110118/1-11011Q60246415.jpg',1,'127','122','0',32206,1295337766,1),(68,40,'旺吉祥纸杯（运动款）','/uploads/110118/1-11011Q60440Y3.jpg',1,'127','122','0',35534,1295337880,1),(69,41,'旺吉祥纸杯（经典款）','/uploads/110118/1-11011Q60ADW.jpg',1,'127','122','0',33184,1295338016,1),(70,42,'旺吉祥公仔（组合款）：进口绒布制作，内含高性能竹炭。','/uploads/110118/1-11011Q610033b.jpg',1,'127','122','0',41479,1295338203,1),(71,43,'旺吉祥杯垫（经典套）','/uploads/110118/1-11011Q6124V07.jpg',1,'127','122','0',42697,1295338368,1),(72,44,'旺吉祥杯垫（第二套）','/uploads/110118/1-11011Q61431446.jpg',1,'127','122','0',49924,1295338471,1),(73,45,'旺吉祥包袋','/uploads/110118/1-11011Q61630218.jpg',1,'808','776','0',715494,1295338590,1),(74,46,'旺吉祥包袋 反面','/uploads/110118/1-11011Q619139D.jpg',1,'127','122','0',38880,1295338753,1),(75,47,'旺吉祥明信片（职业装）','/uploads/110118/1-11011Q62122I4.jpg',1,'141','136','0',39709,1295338882,1),(76,48,'旺吉祥明信片（金色全家福）','/uploads/110118/1-11011Q62242V2.jpg',1,'141','136','0',45799,1295338962,1),(77,49,'旺吉祥明信片（可爱小屋）','/uploads/110118/1-11011Q62343101.jpg',1,'141','136','0',38970,1295339023,1),(78,50,'旺吉祥明信片（马戏团）','/uploads/110118/1-11011Q62449510.jpg',1,'141','136','0',40712,1295339089,1),(79,51,'旺吉祥明信片（喝酸奶）','/uploads/110118/1-11011Q6253T59.jpg',1,'141','136','0',33635,1295339138,1),(80,52,'旺吉祥锅垫 效果图 1','/uploads/110118/1-11011Q62K5Q4.jpg',1,'127','122','0',38745,1295339275,1),(81,53,'旺吉祥锅垫 效果图 2','/uploads/110118/1-11011Q62T2496.jpg',1,'127','122','0',33762,1295339322,1),(82,54,'旺吉祥锅垫 效果图 3','/uploads/110118/1-11011Q62932X5.jpg',1,'127','122','0',38241,1295339372,1),(83,55,'旺吉祥锅垫 效果图 4','/uploads/110118/1-11011Q63022Z8.jpg',1,'127','122','0',43649,1295339422,1),(84,56,'旺吉祥十二生肖系列-鼠','/uploads/110118/1-11011Q64645413.jpg',1,'580','646','0',90575,1295340405,1),(85,57,'旺吉祥十二生肖系列-牛','/uploads/110118/1-11011QA9304R.jpg',1,'580','646','0',88095,1295341170,1),(86,58,'旺吉祥十二生肖系列-虎','/uploads/110118/1-11011QF200F3.jpg',1,'580','646','0',111137,1295341320,1),(87,59,'旺吉祥十二生肖系列-兔','/uploads/110118/1-11011QF2302Z.jpg',1,'580','646','0',70774,1295341350,1),(88,60,'旺吉祥十二生肖系列-龙','/uploads/110118/1-11011QF3451S.jpg',1,'580','646','0',99639,1295341425,1),(89,61,'旺吉祥十二生肖系列-蛇','/uploads/110118/1-11011QF412R7.jpg',1,'580','646','0',79530,1295341452,1),(90,62,'旺吉祥十二生肖系列-马','/uploads/110118/1-11011QF514354.jpg',1,'580','646','0',79502,1295341514,1),(91,63,'旺吉祥十二生肖系列-羊','/uploads/110118/1-11011QF644T0.jpg',1,'580','646','0',117238,1295341604,1),(92,64,'旺吉祥十二生肖系列-猴','/uploads/110118/1-11011QFG5635.jpg',1,'859','646','0',80274,1295341635,1),(93,65,'旺吉祥十二生肖系列-鸡','/uploads/110118/1-11011QFPH27.jpg',1,'580','646','0',84142,1295341687,1),(94,66,'旺吉祥十二生肖系列-狗','/uploads/110118/1-11011QFU2O5.jpg',1,'580','646','0',85848,1295341732,1),(95,67,'旺吉祥十二生肖系列-猪','/uploads/110118/1-11011QF945D8.jpg',1,'580','646','0',64696,1295341785,1),(96,68,'吉祥十二生肖-猴','/uploads/110118/1-11011QGF1547.jpg',1,'580','646','0',77822,1295342221,1),(97,45,'旺吉祥包袋','/uploads/110119/1-11011Z94K5F5.jpg',1,'127','122','0',25771,1295401675,1),(98,38,'中部banner','/uploads/110119/1-11011910215DM.png',1,'903','263','0',97572,1295403716,1),(99,69,'旺吉祥恭祝大家新春快乐！','/uploads/110203/1-110203134R1O6.jpg',1,'590','390','0',218488,1296712101,1),(100,0,'110209/1-110209141PK06.jpg','/uploads/110209/1-110209141PK06.jpg',1,'593','391','0',125112,1297232287,1),(101,71,'吉祥一周乐队SHOW-星期一（吉他手）','/uploads/110214/1-110214145504559.jpg',1,'601','364','0',117474,1297666504,1),(102,0,'110215/1-110215145Z15T.jpg','/uploads/110215/1-110215145Z15T.jpg',1,'601','370','0',61816,1297753141,1),(103,73,'吉祥乐队-星期三','/uploads/110216/1-11021610093I58.jpg',1,'601','371','0',88285,1297822177,1),(104,74,'吉祥乐队-星期五','/uploads/110218/1-11021Q25R63b.jpg',1,'601','612','0',65399,1298005106,1),(105,75,'吉祥乐队-星期四','/uploads/110218/1-11021Q35315547.jpg',1,'601','370','0',67313,1298008395,1),(106,76,'吉祥乐队-周末狂欢','/uploads/110218/1-11021R00340592.jpg',1,'620','361','0',89219,1298030620,1),(107,77,'划船','/uploads/110419/1-11041Z93622355.jpg',1,'713','364','0',88458,1303176982,1),(108,77,'划船','/uploads/110419/1-11041Z93J2608.jpg',1,'613','312','0',77126,1303177062,1),(109,77,'划船','/uploads/110419/1-11041Z93912433.jpg',1,'532','271','0',68348,1303177152,1);
/*!40000 ALTER TABLE `dede_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_verifies`
--

DROP TABLE IF EXISTS `dede_verifies`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_verifies` (
  `nameid` char(32) NOT NULL default '',
  `cthash` varchar(32) NOT NULL default '',
  `method` enum('local','official') NOT NULL default 'official',
  `filename` varchar(254) NOT NULL default '',
  PRIMARY KEY  (`nameid`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_verifies`
--

LOCK TABLES `dede_verifies` WRITE;
/*!40000 ALTER TABLE `dede_verifies` DISABLE KEYS */;
/*!40000 ALTER TABLE `dede_verifies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dede_vote`
--

DROP TABLE IF EXISTS `dede_vote`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dede_vote` (
  `aid` mediumint(8) unsigned NOT NULL auto_increment,
  `votename` varchar(50) NOT NULL default '',
  `starttime` int(10) unsigned NOT NULL default '0',
  `endtime` int(10) unsigned NOT NULL default '0',
  `totalcount` mediumint(8) unsigned NOT NULL default '0',
  `ismore` tinyint(6) NOT NULL default '0',
  `votenote` text,
  PRIMARY KEY  (`aid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dede_vote`
--

LOCK TABLES `dede_vote` WRITE;
/*!40000 ALTER TABLE `dede_vote` DISABLE KEYS */;
INSERT INTO `dede_vote` VALUES (1,'你是从哪儿得知本站的？',1150646400,1268928000,8,0,'<v:note id=\'1\' count=\'2\'>朋友介绍</v:note>\r\n<v:note id=\'2\' count=\'0\'>门户网站的搜索引擎</v:note>\r\n<v:note id=\'3\' count=\'2\'>Google或百度搜索</v:note>\r\n<v:note id=\'4\' count=\'3\'>别的网站上的链接</v:note>\r\n<v:note id=\'5\' count=\'1\'>其它途径</v:note>\r\n');
/*!40000 ALTER TABLE `dede_vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-04-26  5:50:34
