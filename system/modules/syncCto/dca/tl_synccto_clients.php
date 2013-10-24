<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    syncCto
 * @license    GNU/LGPL 
 * @filesource
 */
$GLOBALS['TL_DCA']['tl_synccto_clients'] = array(
    // Config
    'config' => array(
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'onload_callback'  => array(
            array('tl_synccto_clients', 'checkClientStatus'),
            array('tl_synccto_clients', 'checkPermissionClient'),
            array('tl_synccto_clients', 'checkPermissionClientCreate'),
        ),
    ),
    // List
    'list' => array(
        'sorting' => array(
            'mode'   => 1,
            'fields' => array('title'),
            'flag'        => 2,
            'panelLayout' => 'filter;search,limit',
        ),
        'label'       => array(
            'fields' => array('title', 'id', 'address', 'path', 'id'),
            'format'            => '<img class="ping" src="system/modules/syncCto/html/js/images/gray.png" alt="" /> %s <span style="color: #aaaaaa; padding-left: 3px;">(' . $GLOBALS['TL_LANG']['tl_syncCto_clients']['id'][0] . ': %s, ' . $GLOBALS['TL_LANG']['tl_syncCto_clients']['address'][0] . ': <span>%s%s</span><span class="client-id invisible">%s</span>)</span>',
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"'
            ),
//            'syncToAll' => array(
//                'label' => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['syncToAll'],
//                'href'  => '?do=synccto_clients&table=tl_syncCto_clients_syncTo&act=start&step=0&mode=all&init=1',
//                'class' => 'header_sync_all',                 
//                'icon'  => 'system/modules/syncCto/html/icons/nav/iconSyncTo.png',                
//            )
        ),
        'operations' => array(
            'edit' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['edit'],
                'href'            => 'act=edit',
                'icon'            => 'edit.gif',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_edit'),
            ),
            'copy' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['copy'],
                'href'            => 'act=copy',
                'icon'            => 'copy.gif',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_copy'),
            ),
            'delete' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['delete'],
                'href'            => 'act=delete',
                'icon'            => 'delete.gif',
                'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_delete'),
            ),
            'show' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['show'],
                'href'            => 'act=show',
                'icon'            => 'show.gif',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_show'),
            ),
            'showExtern' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['showExtern'],
                'href'            => '&table=tl_syncCto_clients_showExtern&act=start',
                'icon'            => 'system/modules/syncCto/html/icons/nav/iconCheck.png',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_showExtern'),
            ),
            'syncFrom' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['syncFrom'],
                'href'            => '&table=tl_syncCto_clients_syncFrom&act=create',
                'icon'            => 'system/modules/syncCto/html/icons/nav/iconSyncFrom.png',
                'attributes'      => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['tl_syncCto_clients']['syncFromConfirm'] . '\')) return false; Backend.getScrollOffset();"',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_syncFrom'),
            ),
            'syncTo' => array(
                'label'           => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['syncTo'],
                'href'            => '&table=tl_syncCto_clients_syncTo&act=create',
                'icon'            => 'system/modules/syncCto/html/icons/nav/iconSyncTo.png',
                'button_callback' => array('tl_synccto_clients', 'checkPermission_client_syncTo'),
            ),
        )
    ),
    // Palettes
    'palettes' => array(
        '__selector__' => array('http_auth'),
        'default'     => '{apikey_legend},apikey;{title_legend},title,description;{connection_legend},address,path,port,codifyengine;{expert_legend:hide},http_auth',
    ),
    'subpalettes' => array(
        'http_auth' => 'http_username,http_password',
    ),
    // Fields
    'fields'    => array(
        'apikey' => array(
            'label'       => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['apikey'],
            'explanation' => 'apiKey',
            'inputType'   => 'text',
            'exclude'     => true,
            'eval'        => array('helpwizard' => true, 'mandatory'  => true, 'maxlength'  => '64', 'tl_class'   => 'long')
        ),
        'title'      => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['title'],
            'inputType' => 'text',
            'search'    => true,
            'exclude'   => true,
            'eval'      => array('mandatory'   => true, 'maxlength'   => '64', 'tl_class'    => 'long')
        ),
        'description' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['description'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => array('style'   => 'height:80px')
        ),
        'address' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['address'],
            'inputType' => 'text',
            'default'   => 'http://',
            'search'    => true,
            'exclude'   => true,
            'eval'      => array('trailingSlash' => false, 'mandatory'     => true, 'tl_class'      => 'w50')
        ),
        'path'          => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['path'],
            'inputType' => 'text',
            'exclude'   => true,
            'eval'      => array('trailingSlash' => false, 'tl_class'      => 'w50'),
            'save_callback' => array(
                array('tl_synccto_clients', 'checkFirstSlash')
            )
        ),
        'port' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['port'],
            'inputType' => 'text',
            'search'    => true,
            'default'   => '80',
            'exclude'   => true,
            'eval'      => array('rgxp'         => 'digit', 'mandatory'    => true, 'tl_class'     => 'w50')
        ),
        'codifyengine' => array(
            'label'            => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['codifyengine'],
            'inputType'        => 'select',
            'explanation'      => 'security',
            'exclude'          => true,
            'options_callback' => array("tl_synccto_clients", "callCodifyengines"),
            'eval' => array('mandatory'  => true, 'tl_class'   => 'w50', 'helpwizard' => true),
        ),
        'http_auth'  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['http_auth'],
            'inputType' => 'checkbox',
            'exclude'   => true,
            'eval'      => array('submitOnChange' => true, 'tl_class'       => 'clr'),
        ),
        'http_username'  => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['http_username'],
            'inputType' => 'text',
            'exclude'   => true,
            'eval'      => array('mandatory'     => true, 'tl_class'      => 'w50'),
        ),
        'http_password' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients']['http_password'],
            'inputType' => 'text',
            'exclude'   => true,
            'eval'      => array('mandatory' => true, 'encrypt'   => true, 'tl_class'  => 'w50'),
        ),
    )
);

/**
 * Class for syncCto clients
 */
class tl_synccto_clients extends Backend
{

    // Objects
    protected $objBackendUser;
    protected $objBackendHistory;
    // Vars
    protected $blnUserBackendHistory = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->objBackendUser = BackendUser::getInstance();

        // Check if we have 'BackendUserHistory'
        if (in_array('backendUserHistory', Config::getInstance()->getActiveModules()))
        {
            $this->blnUserBackendHistory = true;
            $this->objBackendHistory     = BackendUserHistory::getInstance();
        }
    }

    /**
     * User for permission check from operation callbacks
     * 
     * @param string $name Name of function
     * @param array $arguments Arguments
     * @return mixed 
     */
    public function __call($name, $arguments)
    {
        $arrSplitName = explode("_", $name);

        if (count($arrSplitName) != 3)
        {
            return FALSE;
        }

        //checkPermission_clients_edit
        if ($arrSplitName[0] == 'checkPermission' && $arrSplitName[1] == "client")
        {
            return $this->checkPermissionClientButton($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5], $arrSplitName[2]);
        }
    }

    /**
     * Set the javascript file for client ping 
     */
    public function checkClientStatus()
    {
        $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/syncCto/html/js/ping.js';
    }

    /**
     * Permission check for the client overview page
     * 
     * @param type $row
     * @param type $href
     * @param type $label
     * @param type $title
     * @param type $icon
     * @param type $attributes
     * @param type $operations
     * @return type 
     */
    public function checkPermissionClientButton($row, $href, $label, $title, $icon, $attributes, $operations)
    {
        $blnUserIsWorking = false;
        $arrNotices = array();

        // Check if we have the userBackendHistory
        if ($this->blnUserBackendHistory)
        {
            $objResult = $this->objBackendHistory->searchUser(array('%synccto_clients%', "%$operations%", '%' . $row['id'] . '%'));

            if ($objResult->numRows != 0)
            {
                while ($objResult->next())
                {
                    $blnFoundOne = false;
                    $arrUrl = deserialize($objResult->url);

                    // Check do and id
                    if ($arrUrl['do'] == 'synccto_clients' && $arrUrl['id'] == $row['id'])
                    {
                        if ($operations == 'edit' && $arrUrl['act'] == $operations && empty($arrUrl['table']))
                        {
                            $blnFoundOne = true;
                        }
                        else if ($operations == 'showExtern' && $arrUrl['table'] == 'tl_syncCto_clients_showExtern')
                        {
                            $blnFoundOne = true;
                        }
                        else if ($operations == 'showExtern' && $arrUrl['table'] == 'tl_syncCto_clients_showExtern')
                        {
                            $blnFoundOne = true;
                        }
                        else if ($operations == 'syncFrom' && $arrUrl['table'] == 'tl_syncCto_clients_syncFrom')
                        {
                            $blnFoundOne = true;
                        }
                        else if ($operations == 'syncTo' && $arrUrl['table'] == 'tl_syncCto_clients_syncTo')
                        {
                            $blnFoundOne = true;
                        }
                        
                        if($blnFoundOne == true)
                        {
                            $blnUserIsWorking = true;
                            $arrNotices[] = sprintf($GLOBALS['TL_LANG']['MSC']['editWarning'], $objResult->username,  date($GLOBALS['TL_CONFIG']['timeFormat'], $objResult->tstamp), $row['id']);
                        }
                    }
                }
            }
        }
        
        if ($this->objBackendUser->hasAccess($operations, 'syncCto_clients_p') == true)
        {
            if ($blnUserIsWorking)
            {
                switch ($icon)
                {
                    case 'system/modules/syncCto/html/icons/nav/iconCheck.png':
                        $icon = 'system/modules/syncCto/html/icons/nav/iconCheckDisabled.png';
                        break;

                    case 'system/modules/syncCto/html/icons/nav/iconSyncFrom.png':
                    case 'system/modules/syncCto/html/icons/nav/iconSyncTo.png':
                        $icon = 'system/modules/syncCto/html/icons/nav/iconSyncDisabled.png';
                        break;

                    case 'edit.gif':
                        $icon = 'system/modules/syncCto/html/icons/nav/iconEditDisabled.gif';
                        break;
                }

                $title = implode("<br/>", $arrNotices);
                return '<a class="user-history" href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
            }
            else
            {
                return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
            }
        }
        else if (preg_match("/\.png/i", $icon))
        {
            return $this->generateImage(preg_replace('/\.png$/i', '_.png', $icon)) . ' ';
        }
        else if (preg_match("/\.gif/i", $icon))
        {
            return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)) . ' ';
        }
        else
        {
            return $this->generateImage($icon) . ' ';
        }
    }

    /**
     * Check permissions to edit table tl_content
     */
    public function checkPermissionClient()
    {
        if ($this->objBackendUser->isAdmin)
        {
            return;
        }

        // Set root IDs
        if (!is_array($this->objBackendUser->syncCto_clients) || count($this->objBackendUser->syncCto_clients) < 1)
        {
            $root = array(0);
        }
        else
        {
            $root = $this->objBackendUser->syncCto_clients;
        }

        $GLOBALS['TL_DCA']['tl_synccto_clients']['list']['sorting']['root'] = $root;

        if ($this->objBackendUser->hasAccess($this->Input->get('act'), 'syncCto_clients_p') == true || strlen($this->Input->get('act')) == 0)
        {
            return;
        }
        else
        {
            $this->log('Not enough permissions to ' . $this->Input->get('act') . ' syncCto clients', 'tl_syncCto_clients checkPermissionClient', TL_ERROR);
            $this->redirect('contao/main.php?act=error');
        }
    }

    /**
     * Check user permissions on every client
     */
    public function checkPermissionClientCreate()
    {
        if (!$this->objBackendUser->hasAccess('create', 'syncCto_clients_p'))
        {
            $GLOBALS['TL_DCA']['tl_synccto_clients']['config'] = array_unique(array_merge(array('closed' => true), $GLOBALS['TL_DCA']['tl_synccto_clients']['config']));
        }
    }

    /**
     * Call ctoCommunication engines
     */
    public function callCodifyengines()
    {
        $arrReturn = array();

        foreach ($GLOBALS["CTOCOM_ENGINE"] as $key => $value)
        {
            if ($value["invisible"] == TRUE)

            {
                continue;
            }

            $arrReturn[$key] = $value["name"];
        }

        asort($arrReturn);

        return $arrReturn;
    }

    /**
     * Check and delete the first slash
     * 
     * @param string $strValue
     * @param DataContainer $dc
     * @return string 
     */
    public function checkFirstSlash($strValue, DataContainer $dc)
    {
        if (empty($strValue))
        {
            return "";
        }
        else
        {
            if (preg_match("/^\//", $strValue))
            {
                return $strValue;
            }
            else
            {
                return "/" . $strValue;
            }
        }
    }

}