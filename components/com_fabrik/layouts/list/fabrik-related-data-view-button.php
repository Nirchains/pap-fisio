<?php
/**
 * List related data view list data button.
 */

defined('JPATH_BASE') or die;

$d       = $displayData;
$trigger = $d->popUp ? 'data-fabrik-view="list"' : '';
$label   = '<span class="fabrik_related_data_count"> (' . $d->count . ') </span> ' ;
//$label   = '(' . $d->count . ')</span> ' . $d->label;
$icon    = FabrikHelperHTML::icon('icon-list-view', $label);
?>

<?php if ($d->canView) :
	if ($d->count === 0) : ?>
		<div style="text-align:center" class="related_data_norecords">
			<?php echo $d->totalLabel; ?>
		</div>
		<?php
	endif;

	if ($d->showRelated) :
		?>
		<a class="related_data" <?php echo $trigger; ?> href="<?php echo $d->url; ?>" title="<?php echo $d->label; ?>"><?php echo $label; ?></a>
		<?php
	endif;

	if ($d->showAddLink) :
		?>
		<?php echo $d->addLink; ?>
		<?php
	endif;
else :?>
	<div style="text-align:center">
		<a title="<?php echo FText::_('COM_FABRIK_NO_ACCESS_PLEASE_LOGIN'); ?>">
			<img src="<?php echo COM_FABRIK_LIVESITE; ?>media/com_fabrik/images/login.png"
				alt="<?php echo FText::_('COM_FABRIK_NO_ACCESS_PLEASE_LOGIN'); ?>" />
		</a>
	</div>
<?php endif; ?>
