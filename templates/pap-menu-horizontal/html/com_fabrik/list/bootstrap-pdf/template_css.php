<?php
/**
 * Fabrik List Template: Bootstrap
 *
 * @package     Joomla
 * @subpackage  Fabrik
 * @copyright   Copyright (C) 2005-2016  Media A-Team, Inc. - All rights reserved.
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

header('Content-type: text/css');
$c = $_REQUEST['c'];
$buttonCount = (int) $_REQUEST['buttoncount'];
$buttonTotal = $buttonCount === 0 ? '100%' : 30 * $buttonCount ."px";
echo "

.fabrikDataContainer {
	clear:both;
	/*
		dont use this as it stops dropdowns from showing correctly
		overflow: auto;*/
}

.fabrikDataContainer .pagination a{
	float: left;
}

ul.fabrikRepeatData {
	list-style: none;
	list-style-position:inside;
	margin: 0;
	padding-left: 0;
}
.fabrikRepeatData > li {
	white-space: nowrap;
	max-width:350px;
	overflow:hidden;
	text-overflow: ellipsis;
}
td.repeat-merge div, td.repeat-reduce div,
td.repeat-merge i, td.repeat-reduce i {
padding: 5px !important;
}

.nav li {
list-style: none;
}

.filtertable_horiz {
	display: inline-block;
	vertical-align: top;
}

.fabrikListFilterCheckbox {
	text-align: left;
}

.fabrikDateListFilterRange {
	text-align: left;
	display: inline-block;
}
.tdgrey, .grupo_grande {
	background-color: #efefef;
}
.grupo_pequeno {
	background-color: #ffffff;
}
.header-logo img
{
	max-width: 250px;
}
.balancepositivo
{
	background-color:#ffeaea;
}
.balancenegativo
{
	background-color:#f1fbff;
}
.fabrikDataContainer .table th, .fabrikDataContainer .table td {
	vertical-align: middle;
}

.fabrikDataContainer .table th {
	text-transform: uppercase;
	border-bottom: 2px solid #ddd;
}
.table th span {
	white-space: pre-line;
}

fieldset.leyenda {
	border: 1px solid #ddd;
    padding: 10px;
    margin: auto;
}

fieldset.leyenda legend {
    display: inherit;
    width: auto;
    padding: 0px 10px;
    margin-bottom: auto;
    font-size: 14px;
    line-height: 14px;
    color: inherit;
    border: none;
    border-bottom: 0px solid #e5e5e5;
}
.fabrikButtonsContainer.navbar .nav > li > a:focus {
	outline: 2px solid transparent;
}

.fabrikButtonsContainer.navbar .nav > li > a:hover {
	background-color: #e6e6e6;
}

.fabrikButtonsContainer.navbar .dropdown-menu > li > a:hover {
	cursor: pointer;
	background-color: #01A7B7;
}

table.estados td {
    border: 1px solid #ccc;
    font-size: 10px;
    padding: 3px 10px;
}

fieldset.fabrikGroup, fieldset.radio.btn-group {
	padding: 0;
	margin: 0;
	border: 0;
}

fieldset.fabrikGroup legend, fieldset.radio.btn-group legend {
	display: block;
	width: 100%;
	padding: 0;
	margin-bottom: 18px;
	font-size: 19.5px;
	line-height: 36px;
	color: #333;
	border: 0;
	border-bottom: 1px solid #e5e5e5;
}

.nav-buttons {
	margin-bottom: 0px;
}

.related_data:hover {
	text-decoration: none;
}

.fabrik_groupheading.info > td
{
	background-color: #66727D !important;
    background-repeat: repeat-x;
	padding:8px;
}
.fabrik_groupheading {
	
}

.fabrik_groupheading a {
	color: #ffffff;
	text-transform: uppercase;
}
h1, h2 {
	font-family: Arial, sans-serif;
}
";

?>

