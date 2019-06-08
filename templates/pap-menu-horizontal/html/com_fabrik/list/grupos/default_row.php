<?php
/**
 * Fabrik List Template: Admin Row
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2016  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

?>
<tr id="<?php echo $this->_row->id;?>" class="<?php echo $this->_row->class;?>">
	<?php foreach ($this->headings as $heading => $label) {
		$style = empty($this->cellClass[$heading]['style']) ? '' : 'style="'.$this->cellClass[$heading]['style'].'"';
		$heading_grupo_grande = "t_grupos___grupo_grande";
		$grupo_grande = (int)($this->_row->data->$heading_grupo_grande);
		if ($grupo_grande==0 && (strpos($heading, "___unidad_docente") || strpos($heading, "___grupo")) ) {
			$tdclass = "tdgrey";
		} else {
			$tdclass = "";
		}
		?>
		<td class="<?php echo $tdclass;?> <?php echo $this->cellClass[$heading]['class']?>" <?php echo $style?>>
			<?php echo isset($this->_row->data) ? $this->_row->data->$heading : '';?>
		</td>
	<?php }?>
</tr>
