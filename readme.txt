=== JW Player for WordPress ===
Contributors: ghera74
Tags: jw player, video player, embed video, video preroll, video subtitles
Version: 2.3.4
Stable tag: 2.3.4
Requires at least: 4.0
Tested up to: 6.4
License: GPLv2

**JW Player for WordPress** enables you to publish videos on your WordPress posts and pages using the most popular video player on the web.

== Description ==

**JW Player for WordPress** enables you to publish videos on your WordPress posts and pages using the most popular video player on the web. Take complete control of your player, from branding to size and dimensions. Allow users share and embed your videos from your WordPress pages & posts.
 
**Free Features (NEW!):**

* Connect to the JW Player Dashboard using your API v2 credentials
* Select and publish videos hosted on JW Player
* Select and publish playlist hosted on JW Player
* Support shortcodes like [jwplayer fPHnET5D]

**Cloud**

* Register a JW Player account at https://www.jwplayer.com/pricing/
* Once you’re logged in, copy your **Cloud Hosted Player Libraries** from https://dashboard.jwplayer.com/#/players/downloads
* Paste your library url to the plugin options page.

**Self hosted**

* https://www.jwplayer.com/pricing/
* Once you’re logged in, download the player and copy your License key from https://dashboard.jwplayer.com/#/players/downloads
* Upload the folder to your site
* Add the full url of **jwplayer.js** in the plugin options page (ex. https://example.com/FOLDER-UPLOADED/jwplayer.js)
* Paste your License Key to the admin page of the plugin.

After that, set your general preferences, choose the post types where you want to add videos and start to add content using the JW Player for WordPress box that you’ll find there. Just add the url of your self-hosted video or select cloud-hosted content from n your JW Player Dashboard.

That’s it, you’re ready to go!


== Installation ==

* Download JW Player for WordPress
* Upload the 'jw-player-7-for-wp’ directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
* Activate JW Player for WordPress from your Plugins page.
* Once Activated, go to <strong>JW Player</strong> menu and set you preferences.

**From your WordPress dashboard**

* Visit *Plugins > Add New*
* Search for *JW Player 7 for WordPress* and download it.
* Activate JW Player 7 for WordPress from your Plugins page.
* Once Activated, go to <strong>JW Player</strong> menu and set you preferences.

**From WordPress.org or ilghera.com**

* Download JW Player 7 for WordPress
* Upload the *jw-player-7-for-wp* directory to your */wp-content/plugins/* directory, using your favorite method (ftp, sftp, scp, etc...)
* Activate JW Player 7 for WordPress from your Plugins page.
* Once Activated, go to <strong>JW Player</strong> menu and set you preferences.


== Screenshots ==

1. Publishing videos and playlists
2. Select videos and playlists hosted on JW Player
3. Self hosted video options
4. Cloud player setup and API Credentials
5. Playlist carousel options
6. Security options
7. Ads options
8. Self hosted player general options
9. JWP7 skin customization
10. JWP8 skin customization
11. Subtitles options
12. Sharing options
13. Related post options 


== Changelog ==

= 2.3.4 =
Release Date: 7 March 2024

* Enhancement : Coding standard


= 2.3.3 =
Release Date: 14 December 2023 

* Update: (Premium) Plugin Update Checker
* Bug: (Premium) Removing element in case of multiple videos in the post/page 
* Bug: Creation of dynamic property deprecated in PHP 8.2


= 2.3.2 =
Release Date: 5 January 2023

* Bug: Only one video displayed in archive pages


= 2.3.1 =
Release Date: 13 December 2022

* Enhancement : Support shortcodes like *[jwplayer file="https://example.com/videos/video.mp4"]*


= 2.3.0 =
Release Date: 26 September 2022

* Enhancement: JW Player dashboard connection using the API v2 credentials
* Enhancement: Select and publish videos hosted on JW Player
* Enhancement: Select and publish playlist hosted on JW Player


= 2.2.1 =
Release Date: 22 September 2022

* Bug: (Premium) Security options require API v1 Secret key  


= 2.2.0 =
Release Date: 26 July 2022

* Enhancement : (Premium) New API v2 authentication with Site ID and Secret  
* Enhancement : (Premium) New API v2 endpoints 
* Enhancement : (Premium) Full list of ad partners available for player bidding option
* Enhancement : (Premium) Price range options for Google Ad Manager mediation 
* Enhancement : (Premium) GDPR options 
* Enhancement : (Premium) CCPA options 
* Bug: JW Player doesn't support FLV videos 


= 2.1.3 =
Release Date: 15 June 2021

* Enhancement : Different endpoints for single-videos and playlists


= 2.1.2 =
Release Date: 28 April 2021

* Bug fix: JS error by selecting admin options


= 2.1.1 =
Release Date: 17 February 2021

* Bug fix: Video itemprop duration not in ISO 8601 


= 2.1.0 =
Release Date: 4 February 2021

* Enhancement : SEO improvements with schema markup
* Enhancement : New switch buttons in plugin options page
* Enhancement : (Premium) Set video poster-image as WordPress featured image
* Bug fix: (Premium) Thumbnails with different width in playlist carousel  


= 2.0.2 =
Release Date: 13 April 2020

* Bug fix: (Premium) Default subtitles option missing
* Bug fix: (Premium) Quotation marks subtitles labels removed


= 2.0.1 =
Release Date: 26 August 2019

* Bug fix : Array check on getting videos for the single post


= 2.0.0 =
Release Date: 30 May 2019

* Enhancement: Support shortcodes like *[jwplayer fPHnET5D]*
* Enhancement: Facebook Instant Articles support
* Enhancement: WP Coding Sandard 
* Enhancement (Premium): JW Player dashboard connection by API Key and Secret
* Enhancement (Premium): Select and publish videos hosted on JW Player
* Enhancement (Premium): Select and publish playlist hosted on JW Player
* Enhancement (Premium): For every embeded video, select and use a specific player set on JW Player
* Enhancement (Premium): Secure video URLs
* Enhancement (Premium): Secure player embeds
* Enhancement (Premium): Playlist carousel customizable
* Enhancement (Premium): Unlimited ad tags
* Enhancement (Premium): Select a specific ad tag for every single video
* Enhancement (Premium): Player bidding support with SpotX
* Enhancement (Premium): Possibility to use an advertising embed block variable
* Enhancement (Premium): Video/ Playlist description in meta box
* Enhancement (Premium): Video/ Playlist thumbanil in meta box


= 1.5.3 =
Release Date: 8 Dec 2018
* Bug Fix: 	   Add/ Delete video buttons not visible in WordPress 5.0


= 1.5.2 =
Release Date: 3 May 2018

* Enhancement: Security improvements.
* Bug Fix: 	   PHP Notices.


= 1.5.1 =
Release Date: 24 Jan 2018

* Enhancement: More skins available for JWP7.
* Enhancement: (Premium) Adding custom skins and color customization for JWP7.
* Enhancement: (Premium) Full skin colors customization for JWP8.
* Bug Fix: 	   PHP Notices.


= 1.5.0 =
Release Date: 6 Nov 2017

* Enhancement: Plugin name changed to JW Player for WordPress, version 7 removed.
* Enhancement: Now it's possible to use the cloud player library, without the need to upload the player.
* Enhancement: JW Player 8 ready.
* Enhancement: New shortcode jwp-video.
* Bug Fix: 	   jQuery library conflict.
* Bug Fix: 	   PHP Notices.
* Bug Fix: 	   Wrong position question mark in plugin box


= 1.4.1 =
Release Date: 3 May 2017

* Enhancement: Better tabs navigation.
* Enhancement: (Premium) New option tab for subtitles style customization.
* Enhancement: (Premium) Possibility to activate subtitles by default.


= 1.4.0 =
Release Date: 7 February 2017

* Enhancement: Add custom measures to every single player with the new shortcode options.
* Enhancement: Social sharing now includes Facebook, Twitter, GooglePlus, LinkedIn, Pinterest, Tumblr, Reddit and email of course.
* Enhancement (Premium): Load different subtitles files (vtt, srt, dfxp) that can be chosen from the CC selection menu.
* Enhancement (Premium): Add different sources with label and let the user toggles the desired video quality.
* Enhancement (Premium): Now you can let the user download your video only by adding a flag.
* Enhancement (Premium): Now you can mute your videos during playback.
* Enhancement (Premium): Configures if the player should loop the content.
* Enhancement (Premium): Autostart, Mute and Repeat options are now available for playlists too by using the new shortcode options.
* Enhancement (Premium): Next up tooltip and more elements are now localized.
* Bug Fix: 	   Special chars in video title and description.


= 1.3.3 =
Release Date: 18 November, 2016

* Bug Fix: YouTube video thumbnail check returned always true.


= 1.3.2 =
Release Date: 17 November, 2016

* Enhancement: Now is possible use the post thumbnail as video poster image.
* Enhancement: YouTube videos now get thumbnails automatically.
* Enhancement: (Premium) Removed the limit of six chapters and subtitles, you can add as many elements as you need.
* Enhancement: (Premium) Now you can add Preview Thumbnails of your video, visible on mouseover on the timeline.


= 1.3.1 =
Release Date: 01 August, 2016

* Bug Fix: Error Missing argument 2 for jwppp_video_code()... with Video Player position in pre/ post content.


= 1.3.0 =
Release Date: 01 August, 2016

* Enhancement: When the file extension is missing or not recognized, now you can force a media type with a simple tool
* Enhancement: The plugin is now Google Analytics ready
* Enhancement: Now you can call videos from outside of the loop, by indicating the post/ page id into the shortcode
* Enhancement: Use the [jw7-video] shortcode into the text widget
* Enhancement: Choose where the logo has to be visualized into the player
* Enhancement: Now you can add subtitles to your videos


= 1.2.0 =
Release Date: 05 April, 2016

* Enhancement (Premium): Now is possible to add a playlist using a shortcode. 
* Enhancement: Added the possibility to indicate a second video URL for mobile devices.


= 1.1.1 =
Release Date: January 03, 2015

* Bug Fix: Missed database update
* Bug Fix: Missed chapters update


= 1.1.0 =
Release Date: January 01, 2015

* Enhancement (Premium): Added Autostart on page load option. 
* Enhancement (Premium): Added the possibility to publish more than one video per post/page (Ajax)
* Enhancement (Premium): Updated the shortcode functionality that gives the ability to call the single video by number
* Bug Fix: Blank line in the header, generated by getting player key and library script.
* Bug Fix: YouTube preview image now fill the player dimensions.
* Bug Fix: Problem on showing more than one video in archive pages.


= 1.0.1 = 
Release Date: October 12, 2015

* Enhancement: Added Custom Player Position option with shortcode
* Enhancement: Added detailed instructions in how to setup JW Player self-hosted to your site
* Enhancement (Premium): Added the possibility to add a playlist using a feed.
 

= 1.0.0 = 
* First release
