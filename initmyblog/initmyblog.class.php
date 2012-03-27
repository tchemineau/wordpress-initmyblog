<?php
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

class InitMyBlog
{

	const OPTION_INIT = 'initmyblog_option_init';

	/**
	 * Call when this plugin is activated.
	 */
	public static function activate ()
	{
		if (get_option(self::OPTION_INIT) === false)
		{
			add_option(self::OPTION_INIT, false);
		}
		self::log('activate');
	}

	/**
	 * Check and delete links.
	 * @return boolean
	 */
	public static function check_and_delete_links ()
	{
		require_once(ABSPATH.'wp-admin/includes/bookmark.php');
		$links_url = array(
			'http://codex.wordpress.org/',
			'http://wordpress.org/news/',
			'http://wordpress.org/extend/ideas/',
			'http://wordpress.org/support/',
			'http://wordpress.org/extend/plugins/',
			'http://wordpress.org/extend/themes/',
			'http://planet.wordpress.org/'
		);
		$status = true;
		foreach (get_bookmarks() as $link)
		{
			if (sizeof(array_keys($links_url, $link->link_url)) > 0)
			{
				self::log('delete default link: '.$link->link_url,true);
				$status &= wp_delete_link($link->link_id);
			}
		}
		return $status;
	}

	/**
	 * Check and delete pages.
	 * @return boolean
	 */
	public static function check_and_delete_pages ()
	{
		$status = true;
		if (!is_null($page = get_page_by_path($page_path = __('sample-page'))))
		{
			self::log('delete default page: '.$page_path.' ('.$page->ID.')');
			$status &= wp_delete_post($page->ID, true);
		}
		return $status;
	}

	/**
	 * Check and delete plugins.
	 * @return boolean
	 */
	public static function check_and_delete_plugins ()
	{
		require_once(ABSPATH.'wp-admin/includes/file.php');
		require_once(ABSPATH.'wp-admin/includes/plugin.php');
		$plugins = array();
		if (file_exists(WP_PLUGIN_DIR.'/hello.php'))
		{
			$plugins[] = 'hello.php';
		}
		if (is_dir(WP_PLUGIN_DIR.'/akismet'))
		{
			$plugins[] = 'akismet/akismet.php';
		}
		if (sizeof($plugins) > 0)
		{
			self::log('check_and_delete_plugins');
			return delete_plugins($plugins);
		}
		return true;
	}

	/**
	 * Check and delete posts.
	 * @return boolean
	 */
	public static function check_and_delete_posts ()
	{
		$status = true;
		if (!is_null($post = get_page_by_path($post_path = sanitize_title(_x('hello-world', 'Default post slug')), OBJECT, 'post')))
		{
			self::log('delete default post: '.$post_path.' ('.$post->ID.')');
			$status &= wp_delete_post($post->ID, true);
		}
		return $status;
	}

	/**
	 * Call when this plugin is deactivated.
	 */
	public static function deactivate ()
	{
		if (get_option(self::OPTION_INIT) !== false)
		{
			delete_option(self::OPTION_INIT);
		}
		self::log('deactivate');
	}

	/**
	 * Tell wether or not this plugin is initialized.
	 * If value is not null, then set the initialisation state of the plugin.
	 * @param $value The initialisation state of the plugin
	 * @return boolean
	 */
	public static function isinit ( $value = null )
	{
		if (is_null($value))
		{
			return get_option(self::OPTION_INIT);
		}
		update_option(self::OPTION_INIT, $value == true);
	}

	/**
	 * Function only used to trace plugin actions.
	 * @param $message A message to trace.
	 */
	public static function log ( $message )
	{
		//file_put_contents('/tmp/initmyblog.log', date(DATE_RFC822).' - '.$message."\n", FILE_APPEND);
	}

	/**
	 * Call everytime this plugin is launched.
	 */
	public static function run ()
	{
		if (self::isinit())
		{
			return;
		}
		$status = true;
		$callbacks = array(
			'check_and_delete_plugins',
			'check_and_delete_posts',
			'check_and_delete_pages',
			'check_and_delete_links'
		);
		for ($i = 0; $i < sizeof($callbacks) && $status; $i++)
		{
			$callback = $callbacks[$i];
			$status = self::$callback();
		}
		self::isinit($status);
		self::log('run');
	}

}

