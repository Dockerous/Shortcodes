<?php
/**
 * Plugin Name: Dockerous Shortcodes
 * Description: Collection of Shortcodes
 * Version: 1.0
 * Author: Dockerous
 * Author URI: http://do.ckero.us
 * License: GPL3 or Later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

/*
 * Includes:
 *     List child pages
 */
require_once 'collection_01.php';
global $dockerous_collection_01;
$dockerous_collection_01 = new DockerousShorcode01();