<?php
/**
*
* @package testing
* @copyright (c) 2012 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

require_once dirname(__FILE__) . '/../../phpBB/includes/functions.php';
require_once dirname(__FILE__) . '/../../phpBB/includes/utf/utf_tools.php';

class phpbb_content_visibility_get_forums_visibility_sql_test extends phpbb_database_test_case
{
	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/get_forums_visibility_sql.xml');
	}

	public function get_forums_visibility_sql_data()
	{
		return array(
			array(
				'phpbb_topics',
				'topic', array(1, 2, 3), '',
				array(
					array('m_approve', true, array(1 => true, 2 => true, 3 => true)),
					array('m_restore', true, array(1 => true, 2 => true, 3 => true)),
				),
				array(
					array('topic_id' => 1),
					array('topic_id' => 2),
					array('topic_id' => 3),
					array('topic_id' => 4),
					array('topic_id' => 5),
					array('topic_id' => 6),
					array('topic_id' => 7),
					array('topic_id' => 8),
					array('topic_id' => 9),
				),
			),
			array(
				'phpbb_topics',
				'topic', array(1, 2), '',
				array(
					array('m_approve', true, array(1 => true, 2 => true)),
					array('m_restore', true, array(1 => true, 2 => true)),
				),
				array(
					array('topic_id' => 1),
					array('topic_id' => 2),
					array('topic_id' => 3),
					array('topic_id' => 4),
					array('topic_id' => 5),
					array('topic_id' => 6),
				),
			),
			array(
				'phpbb_topics',
				'topic', array(1, 2, 3), '',
				array(
					array('m_approve', true, array(2 => true)),
					array('m_restore', true, array(1 => true)),
				),
				array(
					array('topic_id' => 2),
					array('topic_id' => 3),
					array('topic_id' => 4),
					array('topic_id' => 5),
					array('topic_id' => 8),
				),
			),
			array(
				'phpbb_posts',
				'post', array(1, 2, 3), '',
				array(
					array('m_approve', true, array(1 => true, 2 => true, 3 => true)),
					array('m_restore', true, array(1 => true, 2 => true, 3 => true)),
				),
				array(
					array('post_id' => 1),
					array('post_id' => 2),
					array('post_id' => 3),
					array('post_id' => 4),
					array('post_id' => 5),
					array('post_id' => 6),
					array('post_id' => 7),
					array('post_id' => 8),
					array('post_id' => 9),
				),
			),
			array(
				'phpbb_posts',
				'post', array(1, 2), '',
				array(
					array('m_approve', true, array(1 => true, 2 => true)),
					array('m_restore', true, array(1 => true, 2 => true)),
				),
				array(
					array('post_id' => 1),
					array('post_id' => 2),
					array('post_id' => 3),
					array('post_id' => 4),
					array('post_id' => 5),
					array('post_id' => 6),
				),
			),
			array(
				'phpbb_posts',
				'post', array(1, 2, 3), '',
				array(
					array('m_approve', true, array(2 => true)),
					array('m_restore', true, array(1 => true)),
				),
				array(
					array('post_id' => 2),
					array('post_id' => 3),
					array('post_id' => 4),
					array('post_id' => 5),
					array('post_id' => 8),
				),
			),
		);
	}

	/**
	* @dataProvider get_forums_visibility_sql_data
	*/
	public function test_get_forums_visibility_sql($table, $mode, $forum_ids, $table_alias, $permissions, $expected)
	{
		global $db, $auth;

		$db = $this->new_dbal();

		// Create auth mock
		$auth = $this->getMock('phpbb_auth');
		$auth->expects($this->any())
			->method('acl_getf')
			->with($this->stringContains('_'), $this->anything())
			->will($this->returnValueMap($permissions));

		$result = $db->sql_query('SELECT ' . $mode . '_id
			FROM ' . $table . '
			WHERE ' . phpbb_content_visibility::get_forums_visibility_sql($mode, $forum_ids, $table_alias) . '
			ORDER BY ' . $mode . '_id ASC');

		$this->assertEquals($expected, $db->sql_fetchrowset($result));
	}
}
