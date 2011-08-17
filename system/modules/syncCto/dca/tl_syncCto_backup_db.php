<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  MEN AT WORK 2011
 * @author     Stefan Heimes <heimes@men-at-work.de>,
 *             MEN AT WORK   <cms@men-at-work.de>
 * @package    syncCto
 * @license    GNU/LGPL
 * @filesource
 */

$GLOBALS['TL_DCA']['tl_syncCto_backup_db'] = array(

    // Config
    'config' => array
        (
        'dataContainer' => 'File',
        'closed' => true,        
    ),
	
    // Palettes
    'palettes' => array
        (
        'default' => '{table_recommend_legend},table_list_recommend;{table_none_recommend_legend:hide},table_list_none_recommend;'
    ),
	
    // Fields
    'fields' => array(
        'table_list_recommend' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_backup_db']['table_list_recommend'],
            'inputType' => 'checkboxWizard',
            'eval' => array('multiple' => true),            
            'options_callback' => array('SyncCtoCallback', 'optioncallTablesRecommend'),
        ),
        'table_list_none_recommend' => array
            (
            'label' => &$GLOBALS['TL_LANG']['tl_syncCto_backup_db']['table_list_none_recommend'],
            'inputType' => 'checkboxWizard',
            'eval' => array('multiple' => true),            
            'options_callback' => array('SyncCtoCallback', 'optioncallTablesNoneRecommend'),
        ),
    )
);

?>