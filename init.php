<?php

/*
Plugin Name: Banner on
Description: Tool to enable a large, dismissable footer banner on website
Author:      Matt Bedford
Author URI:  https://app.mattbedford.work
Version:     1.0
License:     GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 
2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
with this program. If not, visit: https://www.gnu.org/licenses/

*/


namespace BannerOn;

if (!defined('ABSPATH')) {
	exit;
}

require_once plugin_dir_path(__FILE__) . 'admin/SetUp.php';

// Run back-end scripts
SetUp::init();

// Run front-end scripts
new Controller();
