<?php
/*
Plugin Name: Init My Blog
Plugin URI: http://github.com/tchemineau/initmyblog/
Description: This plugin allow you to init your wordpress to whatever content you defined.
Version: 0.1
Author: Thomas Chemineau
Author URI: http://www.aepik.net/about/
License: BSD
*/
/*
Copyright (c) 2012, Thomas Chemineau <thomas.chemineau@gmail.com>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

// Include the main class.
require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'initmyblog.class.php';

// Register action to be used when activating/deactivating this plugin.
register_activation_hook(__FILE__, array('InitMyBlog', 'activate'));
register_deactivation_hook(__FILE__, array('InitMyBlog', 'deactivate'));

// Add the main action to be ran when this plugin is launched.
add_action('init', array('InitMyBlog', 'run'));

