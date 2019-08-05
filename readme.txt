=== JW Player for Wordpress - VIP ===
Contributors: jwplayer, ghera74
Tags: jw player, jw player 7, jw player 8, jwplayer, jwplayer 7, jwplayer 8, video, embed video, youtube, video preroll, video chapters, video subtitles
Version: 2.0.1
Requires at least: 4.0
Tested up to: 5.2
License: GPLv2

**JW Player for Wordpress - VIP** is the complete solution for using JW Player into Wordpress.
It works with the latest version of world’s most popular video player and enables full power of the JW Player dashboard and APIs directly into your WordPress CMS.
Enable player customization, related videos, social sharing and advertising directly into your content management system to speed up your video workflow.


== Description ==

**JW Player for Wordpress - VIP** is the complete solution for using JW Player into Wordpress.
It works with the latest version of world’s most popular video player and enables full power of the JW Player dashboard and APIs directly into your WordPress CMS.
Enable player customization, related videos, social sharing and advertising directly into your content management system to speed up your video workflow.

**NEW ON THIS VERSION!**

* JW Player dashboard connection via API Key and Secret
* Select and publish videos hosted on JW Player
* Select and publish playlists hosted on JW Player
* Select and use a specific player for individual video embeds
* Secure video URLs
* Secure player embeds
* Customize playlists
* Unlimited ad tags
* Select a specific ad tag for every single video
* Video player bidding support with SpotX
* Possibility to use an advertising embed block variable
* Video / Playlist description
* Video / Playlist thumbnail
* Support shortcodes like *[jwplayer fPHnET5D]*
* WP Coding Sandard 

JW Player for Wordpress - VIP can be used with the cloud or even the self-hosted version of the player.<br>

**Cloud**

* Register a JW Player account at https://www.jwplayer.com/pricing/
* Once you're logged in, copy your **Cloud Hosted Player Libraries** from https://dashboard.jwplayer.com/#/players/downloads
* Paste your library url to the plugin options page.

**Self hosted**

* Register a JW Player account at https://www.jwplayer.com/pricing/
* Once you're logged in, download the player and copy your Licence key from https://dashboard.jwplayer.com/#/players/downloads
* Upload the folder to your site
* Add the full url of jwplayer.js in the plugin options page (ex. https://example.com/FOLDER-UPLOADED/jwplayer.js)
* Paste your Licence Key to the admin page of the plugin.

After that, set your general preferences, choose the post types where you want to add videos and start to add content using the JW Player for WordPress box that you’ll find there. Just add the url of your self-hosted video or select cloud-hosted content from n your JW Player Dashboard.
 
That’s it, you’re ready to go!



== Installation ==

* Download JW Player for Wordpress - VIP
* Upload the 'jw-player-7-for-wp-VIP’ directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
* Activate JW Player for Wordpress - VIP from your Plugins page.
* Once Activated, go to <strong>JW Player</strong> menu and set you preferences.


== Changelog ==


= 2.0.1 =
Release Date: 05 August 2019

* Bug fix : Plugin activation function hooked at setup_theme


= 2.0.0 =
Release Date: 30 May 2019

* Enhancement : JW Player dashboard connection by API Key and Secret
* Enhancement : Select and publish videos hosted on JW Player
* Enhancement : Select and publish playlist hosted on JW Player
* Enhancement : For every embeded video, select and use a specific player set on JW Player
* Enhancement : Secure video URLs
* Enhancement : Secure player embeds
* Enhancement : Playlist carousel customizable
* Enhancement : Facebook Instant Articles support
* Enhancement : Unlimited ad tags
* Enhancement : Select a specific ad tag for every single video
* Enhancement : Player bidding support with SpotX
* Enhancement : Possibility to use an advertising embed block variable
* Enhancement : Video/ Playlist description in meta box
* Enhancement : Video/ Playlist thumbanil in meta box
* Enhancement : Support shortcodes like *[jwplayer fPHnET5D]*
* Enhancement : WP Coding Sandard 


= 1.5.4 =
Release Date: 6 Mar 2019
* Bug Fix: 	   Fields disabled in plugin options page.


= 1.5.3 =
Release Date: 8 Dec 2018
* Bug Fix: 	   Add/ Delete video buttons not visible in Wordpress 5.0


= 1.5.2 =
Release Date: 9 Gen 2018

* Enhancement: Adding custom skins and color customization for JWP7
* Enhancement: Full skin colors customization for JWP8.
* Bug Fix: 	   Wrong order in playlist with more than 9 videos.


= 1.5.1 =
Release Date: 7 Nov 2017

* Bug Fix: 	   With video-position in before/ after content, the player disappear..


= 1.5.0 =
Release Date: 4 Nov 2017

* Enhancement: Plugin name changed to JW Player for Wordpress, version 7 removed.
* Enhancement: Now it's possible to use the cloud player library, without the need to upload the player.
* Enhancement: New plugin update checker.
* Enhancement: JW Player 8 ready.
* Enhancement: New shortcode jwp-video.
* Bug Fix: 	   jQuery library conflict.
* Bug Fix: 	   PHP Notices.


= 1.4.3.1 =
Release Date: 4 May 2017

* Bug Fix: 	   Subtitles default option visible also with chapters. 


= 1.4.3 =
Release Date: 4 May 2017

* Bug Fix: 	   Subtitles default option visible also with chapters. 
* Bug Fix: 	   Subtitles default option value not deleted when necessary.
* Bug Fix: 	   Subtitles load values not deleted when necessary. 


= 1.4.2 =
Release Date: 3 May 2017

* Enhancement: New option tab for subtitles style customization.
* Enhancement: Possibility to activate subtitles by default.
* Enhancement: Better tabs navigation.
* Bug Fix: 	   jQuery error in option page. 


= 1.4.1 =
Release Date: 16 April 2017

* Bug Fix: 	   Embed code with no video url.


= 1.4.0 =
Release Date: 7 February 2017

* Enhancement: Load different subtitles files (vtt, srt, dfxp) that can be chosen from the CC selection menu.
* Enhancement: Add different sources with label and let the user toggles the desired video quality
* Enhancement: Now you can let the user download your video only by adding a flag.
* Enhancement: Add custom measures or aspect ratio to every single player with the new shortcode options.
* Enhancement: Now you can mute your videos during playback.
* Enhancement: Configures if the player should loop the content.
* Enhancement: Autostart, Mute and Repeat options are now available for playlists too by using the new shortcode options.
* Enhancement: Social sharing now includes Facebook, Twitter, GooglePlus, LinkedIn, Pinterest, Tumblr, Reddit and email of course.
* Enhancement: Next up tooltip and more elements are now localized.
* Bug Fix: 	   Special chars in video title and description. 
* Bug Fix: 	   YouTube videos poster images in playlits.
* Bug Fix: 	   Embed option in single box must be flagged if the main option is activated.
* Bug Fix: 	   Wrong videos order with the "Player position" option not in Custom.


= 1.3.3 =
Release Date: 18 November, 2016

* Bug Fix: 	   YouTube video thumbnail check returned always true.
* Bug Fix: 	   Duplicated function deleted.


= 1.3.2 =
Release Date: 17 November, 2016

* Enhancement: Now is possible use the post thumbnail as video poster image.
* Enhancement: YouTube videos now get thumbnails automatically.
* Enhancement: Removed the limit of six chapters and subtitles, you can add as many elements as you need.
* Enhancement: Now you can add Preview Thumbnails of your video, visible on mouseover on the timeline.


= 1.3.1 =
Release Date: 01 August, 2016

* Bug Fix: 	   Error Missing argument 2 for jwppp_video_code()... with Video Player position in pre/ post content.


= 1.3.0 =
Release Date: 31 July, 2016

* Enhancement: Choose where the logo has to be visualized into the player
* Enhancement: Now you can add subtitles to your videos
* Enhancement: When the file extension is missing or not recognized, now you can force a media type with a simple tool
* Enhancement: The plugin is now Google Analytics ready
* Enhancement: Now you can call videos from outside of the loop, by indicating the post/ page id into the shortcode
* Enhancement: Use the [jw7-video] shortcode into the text widget


= 1.2.0 =
Release Date: 05 April, 2016

* Enhancement: Now is possible to add a playlist using a shortcode. 
* Enhancement: Added the possibility to indicate a second video URL for mobile devices


= 1.1.1 =
Release Date: 04 January, 2016

* Bug Fix: 	   Error getting the first video of a post/ page


= 1.1.0 =
Release Date: 01 January, 2016

* Enhancement: Added Autostart on page load option. 
* Enhancement: Added the possibility to publish more than one video per post/page (Ajax)
* Enhancement: Updated the shortcode functionality that gives the ability to call the single video by number
* Bug Fix: 	   Blank line in the header, generated by getting player key and library script.
* Bug Fix: 	   YouTube preview image now fill the player dimensions.
* Bug Fix: 	   Problem on showing more than one video in archive pages.


= 1.0.2 =
Release Date: November 15, 2015

* Enhancement: Added Aspect Ratio 16:10 (e.g. Apple MacBook Air)
* Enhancement: Added Chapters markers with time start, end and title description.
* Enhancement: Added rtmp file support.
* Bug Fix: 	   Single embed video now disappears if general share option is off.


= 1.0.1 = 
Release Date: October 13, 2015

* Enhancement: Added Custom Player Position option with shortcode
* Enhancement: Added detailed instructions in how to setup JW Player self-hosted to your site 
* Enhancement: Added the possibility to add a playlist using a feed.
* Bug Fix: 	   Fixed error while checking for the free version


= 1.0.0 = 

* First release
