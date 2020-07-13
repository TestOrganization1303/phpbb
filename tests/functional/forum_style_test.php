<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
*/

/**
* @group functional
*/
class phpbb_functional_forum_style_test extends phpbb_functional_test_case
{
	public function test_font_awesome_style()
	{
		$crawler = self::request('GET', 'viewtopic.php?t=1&f=2');
		$this->assertStringContainsString('font-awesome.min', $crawler->filter('head > link[rel=stylesheet]')->eq(0)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1');
		$this->assertStringContainsString('font-awesome.min', $crawler->filter('head > link[rel=stylesheet]')->eq(0)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1&view=next');
		$this->assertStringContainsString('font-awesome.min', $crawler->filter('head > link[rel=stylesheet]')->eq(0)->attr('href'));
	}

	public function test_default_forum_style()
	{
		$crawler = self::request('GET', 'viewtopic.php?t=1&f=2');
		$this->assertStringContainsString('styles/prosilver/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1');
		$this->assertStringContainsString('styles/prosilver/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1&view=next');
		$this->assertStringContainsString('styles/prosilver/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));
	}

	public function test_custom_forum_style()
	{
		$db = $this->get_db();
		$this->add_style(2, 'test_style');
		$db->sql_query('UPDATE ' . FORUMS_TABLE . ' SET forum_style = 2 WHERE forum_id = 2');

		$crawler = self::request('GET', 'viewtopic.php?t=1&f=2');
		$this->assertStringContainsString('styles/test_style/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1');
		$this->assertStringContainsString('styles/test_style/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));

		$crawler = self::request('GET', 'viewtopic.php?t=1&view=next');
		$this->assertStringContainsString('styles/test_style/', $crawler->filter('head > link[rel=stylesheet]')->eq(2)->attr('href'));

		$db->sql_query('UPDATE ' . FORUMS_TABLE . ' SET forum_style = 0 WHERE forum_id = 2');
		$this->delete_style(2, 'test_style');
	}
}
