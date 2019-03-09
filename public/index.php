<?php
# ***** BEGIN LICENSE BLOCK *****
# MINZ - A free PHP framework
# Copyright (C) 2011 Marien Fressinaud
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
# 
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
# ***** END LICENSE BLOCK *****

// Constantes de chemins
define ('ROOT_PATH', realpath (dirname (__FILE__) . DIRECTORY_SEPARATOR . '..'));
define ('PUBLIC_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'public');
define ('LIB_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'lib');
define ('DATA_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'data');
define ('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
define ('LOG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'log');
define ('CACHE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'cache');

define ('DATABASE_FILENAME', DATA_PATH . DIRECTORY_SEPARATOR . 'db.sqlite');

define ('CLEAN_INTERVAL_SEC', 3600);

set_include_path (get_include_path ()
                 . PATH_SEPARATOR
                 . LIB_PATH
                 . PATH_SEPARATOR
                 . LIB_PATH . '/minz'
                 . PATH_SEPARATOR
                 . APP_PATH);

require_once(APP_PATH . '/App_FrontController.php');

$front_controller = new App_FrontController ();
$front_controller->init ();
$front_controller->run ();
