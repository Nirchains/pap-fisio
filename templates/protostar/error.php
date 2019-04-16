<?php
defined( '_JEXEC' ) or die('Restricted access');
if (($this->error->getCode()) == '404') {
  echo file_get_contents(JUri::root() .'index.php?option=com_content&view=article&id=2');
}