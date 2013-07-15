<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Table class supporting modified pre-order tree traversal behavior.
 *
 * @package     Joomla
 * @subpackage  Table
 * @link        http://docs.joomla.org/JTableObserver
 * @since       3.1.2
 */
abstract class JTableObserver
{
	/**
	 * The observed table
	 *
	 * @var   JTable
	 */
	protected $table;

	public function __construct(JTable $table)
	{
		$table->addObserver($this);
		$this->table = $table;
	}

	/**
	 * Pre-processor for $table->load($keys, $reset)
	 *
	 * @param   mixed    $keys   An optional primary key value to load the row by, or an array of fields to match.  If not
	 *                           set the instance property value is used.
	 * @param   boolean  $reset  True to reset the default values before loading the new row.
	 *
	 * @return  void
	 */
	public function onBeforeLoad($keys, $reset)
	{
	}

	/**
	 * Post-processor for $table->load($keys, $reset)
	 *
	 * @param   boolean   $result   The result of the load
	 * @param   array     $row      The loaded (and already binded to $this->table) row of the database table
	 *
	 * @return  void
	 */
	public function onAfterLoad(&$result, $row)
	{
	}

	/**
	 * Pre-processor for $table->store($updateNulls)
	 *
	 * @param   boolean   $updateNulls   The result of the load
	 * @param   string    $tableKey      The key of the table
	 *
	 * @return  void
	 */
	public function onBeforeStore($updateNulls, $tableKey)
	{
	}

	/**
	 * Post-processor for $table->store($updateNulls)
	 *
	 * @param   boolean   $result   The result of the store
	 */
	public function onAfterStore(&$result)
	{
	}
	/**
	 * Pre-processor for $table->delete($pk)
	 *
	 * @param   mixed    $pk         An optional primary key value to delete.  If not set the instance property value is used.
	 * @param   string   $tableKey   The normal key of the table
	 *
	 * @return  void
	 *
	 * @throws  UnexpectedValueException
	 */
	public function onBeforeDelete($pk, $tableKey)
	{
	}

	/**
	 * Post-processor for $table->delete($pk)
	 *
	 * @param   mixed    $pk         The deleted primary key value.
	 */
	public function onAfterDelete($pk)
	{
	}
}
