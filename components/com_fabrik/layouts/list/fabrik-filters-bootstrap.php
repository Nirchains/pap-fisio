<?php
/**
 * Layout: List filters
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2016  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @since       3.4
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Fabrik\Helpers\ArrayHelper;

$d             = $displayData;
$underHeadings = $d->filterMode === 3 || $d->filterMode === 4;
$clearFiltersClass = $d->gotOptionalFilters ? "clearFilters hasFilters" : "clearFilters";
$style = $d->toggleFilters ? 'style="display:none"' : '';

?>
<div class="fabrikFilterContainer" <?php echo $style ?>>
	<?php
	if (!$underHeadings) :
	?>
        <div class="row-fluid">
            <fieldset class="leyenda avanced-search">
                <legend>
                <?php echo FText::_('COM_FABRIK_SEARCH'); ?> &nbsp;&nbsp;<?php if ($d->showClearFilters) : ?>
                    <a class="<?php echo $clearFiltersClass; ?>" href="#">
                        <?php echo FabrikHelperHTML::icon('icon-refresh', FText::_('COM_FABRIK_CLEAR')); ?>
                    </a>
                <?php endif; ?>
                </legend>
            <?php
            $chunkedFilters = array();
            $span = floor(12 / $d->filterCols);
            $countFilters = 0;
            foreach ($d->filters as $key => $filter) :
                if ($key !== 'all') :
                    $required = $filter->required == 1 ? ' notempty' : '';
                    if ($d->filterCols === 1) :
                        $chunkedFilters[] = <<<EOT
                    <div class="row-fluid" data-filter-row="$key">
                        <div class="span6">{$filter->label}</div>
                        <div class="span6">{$filter->element}</div>
                    </div>
EOT;
                    else :
                        $chunkedFilters[] = <<<EOT
                    <div class="row-fluid search-filters" data-filter-row="$key">
                        <div class="span6 lbl">{$filter->label}</div>
                        <div class="span6 element">{$filter->element}</div>
                    </div>
EOT;
                    endif;
                endif;
            endforeach;

            // last arg controls whether rows and cols are flipped (pivot)
            $chunkedFilters = ArrayHelper::chunk($chunkedFilters, $d->filterCols, true);

            foreach ($chunkedFilters as $chunk) :
                foreach ($chunk as $filter) :
                    if ($countFilters==0) {
                        echo "<div class='row-fluid 0'>";
                    } else {
                        if ( ((int)$countFilters % (int)($d->filterCols)) == 0) {
                            echo "</div><div class='row-fluid'>";
                        }
                    }
                    ?>
                    <div class="span<?php echo $span; ?>">
                    <?php
                        echo $filter;
                    ?>
                    </div>
                    <?php
                    $countFilters += 1;
                endforeach;
            endforeach;
        ?>
                </div>
            </fieldset>
            
        </div>
        <div class="row-fluid">

        </div>
            <?php
    endif;
    if ($d->filter_action != 'onchange') :
        ?>
        <div class="row-fluid">
            <div class="span12">
                <input type="button" class="pull-right  btn-info btn fabrik_filter_submit button"
                        value="<?php echo FText::_('COM_FABRIK_GO'); ?>" name="filter">
            </div>
        </div>
        <?php
    endif;
    ?>
    <br>
</div>