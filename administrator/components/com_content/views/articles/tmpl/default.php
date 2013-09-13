<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_content&task=articles.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();

$langs = isset($app->languages_enabled);
$assoc = isset($app->item_associations);

// Options for the search tools
$this->stoolsOptions = array(
	'filtersApplied' => !empty($this->activeFilters)
);

JHtml::_(
	'searchtools.grid',
	array(
		'direction' => $listDirn,
		'ordering'  => $listOrder
	)
);
?>

<form action="<?php echo JRoute::_('index.php?option=com_content&view=articles'); ?>" method="post" name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
	<?php endif; ?>
	<div id="j-main-container"<?php echo !empty($this->sidebar) ? ' class="span10"' : ''; ?>>
		<?php echo JLayoutHelper::render('joomla.searchtools.default', $this); ?>
		<?php if (empty($this->items)): ?>
			<div class="alert alert-no-items"><?php echo JText::sprintf('JLIB_HTML_NO_ITEMS_FOUND', JText::_('JGLOBAL_ARTICLES')); ?></div>
		<?php else: ?>
			<table class="table table-striped" id="articleList">
				<thead>
					<tr>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
						</th>
						<th width="1%" class="hidden-phone center">
							<?php echo JHtml::_('grid.checkall'); ?>
						</th>
						<th width="1%" style="min-width:55px" class="nowrap center">
							<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
						</th>
						<th>
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<?php if ($langs) : ?>
							<th width="10%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<?php if ($assoc) : ?>
							<th width="10%" class="nowrap hidden-phone center">
								<?php echo JHtml::_('searchtools.sort', 'COM_CONTENT_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="center">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap hidden-phone center">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($this->items as $i => $item) :
						$ordering = ($listOrder == 'a.ordering');
						$canCreate = $user->authorise('core.create', 'com_content.category.' . $item->catid);
						$canEdit = $user->authorise('core.edit', 'com_content.article.' . $item->id);
						$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
						$canEditOwn = $user->authorise('core.edit.own', 'com_content.article.' . $item->id) && $item->created_by == $userId;
						$canChange = $user->authorise('core.edit.state', 'com_content.article.' . $item->id) && $canCheckin;
						?>
						<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid; ?>">
							<td class="order nowrap center hidden-phone">
								<?php
								$iconClass = '';
								if (!$canChange)
								{
									$iconClass = ' inactive';
								}
								elseif (!$saveOrder)
								{
									$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
								}
								?>
								<span class="sortable-handler<?php echo $iconClass ?>">
								<i class="icon-menu"></i>
							</span>
								<?php if ($canChange && $saveOrder) : ?>
									<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
								<?php endif; ?>
							</td>
							<td class="center hidden-phone">
								<?php echo JHtml::_('grid.id', $i, $item->id); ?>
							</td>
							<td class="center">
								<div class="btn-group">
									<?php echo JHtml::_('jgrid.published', $item->state, $i, 'articles.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
									<?php echo JHtml::_('contentadministrator.featured', $item->featured, $i, $canChange); ?>
									<?php
									// Create dropdown items
									$action = $archived ? 'unarchive' : 'archive';
									JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'articles');

									$action = $trashed ? 'untrash' : 'trash';
									JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'articles');

									// Render dropdown list
									echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
									?>
								</div>
							</td>
							<td class="nowrap has-context">
								<div class="pull-left">
									<?php if ($item->checked_out) : ?>
										<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'articles.', $canCheckin); ?>
									<?php endif; ?>
									<?php if ($canEdit || $canEditOwn) : ?>
										<a href="<?php echo JRoute::_('index.php?option=com_content&task=article.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
											<?php echo $this->escape($item->title); ?></a>
									<?php else : ?>
										<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
									<?php endif; ?>
									<div class="small">
										<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
									</div>
								</div>
							</td>
							<?php if ($langs) : ?>
								<td class="hidden-phone">
									<?php if ($item->language == '*'): ?>
										<?php echo JText::alt('JALL', 'language'); ?>
									<?php else: ?>
										<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>
							<?php if ($assoc) : ?>
								<td class="hidden-phone center">
									<?php if ($item->association) : ?>
										<?php echo JHtml::_('contentadministrator.association', $item->id); ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>
							<td class="hidden-phone">
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>">
									<?php echo $this->escape($item->author_name); ?></a>
								<?php if ($item->created_by_alias) : ?>
									<div class="small">(<?php echo $this->escape($item->created_by_alias); ?>)</div>
								<?php endif; ?>
							</td>
							<td class="hidden-phone">
								<?php echo $this->escape($item->access_level); ?>
							</td>
							<td class="nowrap hidden-phone">
								<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
							</td>
							<td class="center">
								<?php echo (int) $item->hits; ?>
							</td>
							<td class="center hidden-phone">
								<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php echo $this->pagination->getListFooter(); ?>
			<?php //Load the batch processing form. ?>
			<?php echo $this->loadTemplate('batch'); ?>
		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
