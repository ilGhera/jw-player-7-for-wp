<?php
/**
 * JW Player carousel widget configuration
 * @author ilGhera
 * @package jw-player-for-vip/jw-widget
 * @version 1.6.0
 */

/*Get data*/
$playlist_id = isset($_GET['playlist-id']) ? $_GET['playlist-id'] : '';
$player_id = isset($_GET['player-id']) ? $_GET['player-id'] : '';
$carousel_style = isset($_GET['carousel-style']) ? unserialize(base64_decode($_GET['carousel-style'])) : '';

/*Style*/
$title = isset($carousel_style['title']) ? $carousel_style['title'] : 'More Videos';
$text_color = isset($carousel_style['text_color']) ? $carousel_style['text_color'] : '#fff';
$background_color = isset($carousel_style['background_color']) ? $carousel_style['background_color'] : '#000';
$icon_color = isset($carousel_style['icon_color']) ? $carousel_style['icon_color'] : '#fff';

if($playlist_id && $player_id) {
  echo '{';
    echo '"widgets": [';
      echo '{';
        echo '"widgetDivId": "jwppp-playlist-carousel-' . $player_id . '",';
        echo '"playlist": "https://cdn.jwplayer.com/v2/playlists/' . $playlist_id . '",';
        echo '"videoPlayerId": "jwppp-video-' . $player_id . '",';
        echo '"header": "' . $title . '",';
        echo '"textColor": "' . $text_color . '",';
        echo '"backgroundColor": "' . $background_color . '",';
        echo '"iconColor": "' . $icon_color . '",';
        echo '"widgetLayout": "shelf",';
        echo '"widgetSize": "medium"';
      echo '}';
    echo ']';
  echo '}';
}