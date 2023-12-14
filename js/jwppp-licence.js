/**
 * The licence key to use with the self-hosted player
 *
 * @author ilGhera
 * @package jw-player-7-for-wp/js
 *
 * @since 2.3.3
 */

var licence = data.licence;

if ( typeof  jwplayer != 'undefined' ) {
    jwplayer.key = licence;
}
