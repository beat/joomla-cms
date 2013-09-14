<?php
/**
 * @package     Joomla.CMS
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//@TODO: MOve the single extension grid into a reusable JLayout
defined('JPATH_BASE') or die;
$componentParams = JComponentHelper::getParams('com_apps');
$app = JFactory::getApplication();
$data	= array();
$breadcrumbs = $displayData['breadcrumbs'];
$extensions_perrow = $componentParams->get('extensions_perrow', 4);
$spanclass = 'span' . (12 / $extensions_perrow);

$view = $app->input->getCmd('view');
if ($view != 'dashboard') {
	$firstcrumb = '<a class="transcode" href="<?php echo AppsHelper::getAJAXUrl(\'view=dashboard\'); ?>">' . JText::_('COM_APPS_EXTENSIONS') . '</a>';
}
else {
	$firstcrumb = JText::_('COM_APPS_EXTENSIONS_DASHBOARD');
}
?>

<?php if (!count($displayData['extensions'])) : ?>
<div class="row-fluid">
	<div class="item-view span12">
		<div class='grid-container'>
			<div class="grid-header">
				<div class="breadcrumbs">
					<?php echo JText::_('COM_APPS_NO_RESULTS'); ?>
				</div>
			</div>
			<div class="row-fluid">
				<blockquote><h4><?php echo JText::_('COM_APPS_NO_RESULTS_DESCRIPTION'); ?></h4></blockquote>
			</div>
		</div>
	</div>
</div>
<?php return; endif; ?>

<div class="row-fluid">
	<div class="item-view span12">
		<div class='grid-container'>

		<ul class="breadcrumb">
			<li><?php echo $firstcrumb; ?></li>
			<?php foreach ($breadcrumbs as $bc) : ?>
			<span class="divider"> / </span>
			</li><a class="transcode" href="<?php echo AppsHelper::getAJAXUrl("view=category&id={$bc->id}"); ?>"><?php echo $bc->name; ?></a></li>
			<?php endforeach; ?>
		</ul>

		<ul class="thumbnails">
			<?php
				// Looping thru all the extensions, closing and starting a new row after every $extensions_perrow items
				// The single extension box is loaded using the JLayout
				$i = 0;
				foreach ($displayData['extensions'] as $extension) :
					$ratingwidth = round(70 * ($extension->rating / 5));
					if ($i != 0 && $i%$extensions_perrow == 0) { 
			?>
			</ul>	
			<hr />
			<ul>
			<?php 
					}

					$data	= array('spanclass' => $spanclass,'extension' => $extension);
					$extensions_singlegrid = new JLayoutFile('joomla.apps.extensions_singlegrid');
					echo $extensions_singlegrid->render($data);
					
					$i++;
				endforeach;
			?>
		</ul>
	</div>
</div>
