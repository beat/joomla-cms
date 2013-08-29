<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$this->fieldsets = $this->form->getFieldsets('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'plugin.cancel' || document.formvalidator.isValid(document.id('style-form'))) {
			Joomla.submitform(task, document.getElementById('style-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_plugins&layout=edit&extension_id=' . (int) $this->item->extension_id); ?>" method="post" name="adminForm" id="style-form" class="form-validate">
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_PLUGINS_PLUGIN', true)); ?>

		<div class="row-fluid">
			<div class="span9">

				<?php if ($this->item->xml) : ?>
					<?php if ($this->item->xml->description) : ?>
						<h4>
							<?php
							if ($this->item->xml)
							{
								echo ($text = (string) $this->item->xml->name) ? JText::_($text) : $this->item->module;
							}
							else
							{
								echo JText::_('COM_PLUGINS_XML_ERR');
							}
							?>
							<br />
							<span class="label"><?php echo $this->form->getValue('folder'); ?></span> /
							<span class="label"><?php echo $this->form->getValue('element'); ?></span>
						</h4>
						<div>
							<?php
							echo JText::_($this->item->xml->description);

							$this->fieldset = 'description';
							$description = JLayoutHelper::render('joomla.edit.fieldset', $this);

							if ($description)
							{
								echo '<p class="readmore">'
									. '<a href="#" onclick="jQuery(\'.nav-tabs a[href=#description]\').tab(\'show\');">'
									. JText::_('JGLOBAL_SHOW_FULL_DESCRIPTION')
									. '</a>'
									. '</p>';
							}
							?>
						</div>
					<?php endif; ?>
				<?php else : ?>
					<div class="alert alert-error"><?php echo JText::_('COM_PLUGINS_XML_ERR'); ?></div>
				<?php endif; ?>
				<?php
				$this->fieldset = 'basic';
				$html = JLayoutHelper::render('joomla.edit.fieldset', $this);
				echo $html ? '<hr />' . $html : '';
				?>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.main', $this); ?>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php if ($description) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'description', JText::_('JGLOBAL_FIELDSET_DESCRIPTION', true)); ?>
				<?php echo $description; ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php
			$this->fieldsets = array();
			$this->ignore_fieldsets = array('basic');
			echo JLayoutHelper::render('joomla.edit.params', $this);
			?>

			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
		</div>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
</form>
