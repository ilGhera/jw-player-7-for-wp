/**
 * The licence key to use with the self-hosted player
 * @author ilGhera
 * @package jw-player-for-vip/js
 * @since 2.0.0
 */

var licence = data.licence;

if ( typeof  jwplayer != 'undefined' ) {
    jwplayer.key = licence;
}
