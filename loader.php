<?php
/*
 Plugin Name: BuddyForms Anonymous Author
 Plugin URI: http://buddyforms.com/downloads/buddyforms-anonymous-author/
 Description: This BuddyForms Extension allows you to select a default Author and give your users the option to publish Anonymous under the default Author.
 Version: 1.0
 Author: Sven Lehnert
 Author URI: https://profiles.wordpress.org/svenl77
 License: GPLv2 or later
 Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */

 function buddyforms_the_author($authordata){

    return 'Anonymous';
 }
 add_filter( 'the_author', 'buddyforms_the_author', 10, 1 );

 function admin_author_link($link, $author_id, $author_nicename) {
    if( $author_id==1 ) {
        $link = 'http://google.de';
    }
    return $link;
 }
 add_filter( 'author_link', 'admin_author_link', 10, 3);
