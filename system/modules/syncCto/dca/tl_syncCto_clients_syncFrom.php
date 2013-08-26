<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    syncCto
 * @license    GNU/LGPL 
 * @filesource
 */
$GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom'] = array(
    // Config
    'config' => array(
        'dataContainer'   => 'General',
        'disableSubmit'   => false,
        'onload_callback' => array(
            array('tl_syncCto_clients_syncFrom', 'onload_callback')
        ),
        'onsubmit_callback' => array(
            array('tl_syncCto_clients_syncFrom', 'onsubmit_callback'),
        )
    ),
    'dca_config'  => array(
        'data_provider' => array(
            'default' => array(
                'class'  => 'GeneralDataSyncCto',
                'source' => 'tl_syncCto_clients_syncTo'
            ),
        ),
    ),
    // Palettes
    'palettes' => array(
        '__selector__' => array('systemoperations_check'),
        'default'     => '{sync_legend},lastSync,disabledCache,sync_options;{table_legend},database_check;{systemoperations_legend:hide},systemoperations_check,attentionFlag;',
    ),
    // Sub Palettes
    'subpalettes' => array(
        'systemoperations_check' => 'systemoperations_maintenance',
    ),
    // Fields
    'fields'                 => array(
        'lastSync' => array
            (
            'label'         => " ",
            'inputType'     => 'statictext',
        ),
        'disabledCache' => array
            (
            'label'        => " ",
            'inputType'    => 'statictext',
        ),
        'sync_options' => array(
            'label'            => $GLOBALS['TL_LANG']['tl_syncCto_clients_syncFrom']['sync_options'],
            'inputType'        => 'checkbox',
            'exclude'          => true,
            'reference'        => &$GLOBALS['TL_LANG']['SYC'],
            'options_callback' => array('SyncCtoHelper', 'getFileSyncOptions'),
            'eval' => array(
                'multiple'       => true
            ),
        ),
        'database_check' => array(
            'label'                  => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncFrom']['database_check'],
            'inputType'              => 'checkbox',
            'exclude'                => true,
        ),
        'systemoperations_check' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncFrom']['systemoperations_check'],
            'inputType' => 'checkbox',
            'exclude'   => true,
            'eval'      => array(
                'submitOnChange'               => true,
                'tl_class'                     => 'clr'
            ),
        ),
        'systemoperations_maintenance' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncFrom']['systemoperations_maintenance'],
            'inputType' => 'checkbox',
            'exclude'   => true,
            'reference' => &$GLOBALS['TL_LANG']['SYC'],
            'eval'      => array(
                'multiple'         => true,
                'checkAll'         => true
            ),
            'options_callback' => array('SyncCtoHelper', 'getMaintanceOptions'),
        ),
        'attentionFlag' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_syncCto_clients_syncFrom']['attention_flag'],
            'inputType' => 'checkbox',
            'exclude'   => true
        )
    )
);

/**
 * Class for syncFrom configurations
 */
class tl_syncCto_clients_syncFrom extends Backend
{

    // Helper Classes
    protected $objSyncCtoHelper;
    protected $objSyncCtoCommunicationClient = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->BackendUser      = BackendUser::getInstance();
        $this->objSyncCtoHelper = SyncCtoHelper::getInstance();

        parent::__construct();
    }

    public function __destruct()
    {
        $this->stopConnection();
    }

    /**
     * Set new and remove old buttons
     * 
     * @param DataContainer $dc 
     */
    public function onload_callback(DataContainer $dc)
    {
        $strInitFilePath = '/system/config/initconfig.php';

        if (file_exists(TL_ROOT . $strInitFilePath))
        {
            $strFile        = new File($strInitFilePath);
            $arrFileContent = $strFile->getContentAsArray();
            $booLocated     = FALSE;
            foreach ($arrFileContent AS $strContent)
            {
                if (!preg_match("/(\/\*|\*|\*\/|\/\/)/", $strContent))
                {
                    //system/tmp
                    if (preg_match("/system\/tmp/", $strContent))
                    {
                        // Set data
//                        $dc->setData("disabledCache", "<p class='tl_info'>" . $GLOBALS['TL_LANG']['MSC']['disabled_cache'] . "</p>");
                        $booLocated = TRUE;
                    }
                }
            }

            if (!$booLocated)
            {
                $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default'] = str_replace(",disabledCache", "", $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default']);
            }
        }
        else
        {
            $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default'] = str_replace(",disabledCache", "", $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default']);
        }

        $dc->removeButton('save');
        $dc->removeButton('saveNclose');

        $arrData = array
            (
            'id'              => 'start_sync',
            'formkey'         => 'start_sync',
            'class'           => '',
            'accesskey'       => 'g',
            'value'           => specialchars($GLOBALS['TL_LANG']['MSC']['sync']),
            'button_callback' => array('tl_syncCto_clients_syncFrom', 'onsubmit_callback')
        );

        $dc->addButton('start_sync', $arrData);

        // Update a field with last sync information
        $objSyncTime = $this->Database->prepare("SELECT cl.syncFrom_tstamp as syncFrom_tstamp, user.name as syncFrom_user, user.username as syncFrom_alias
                                            FROM tl_synccto_clients as cl 
                                            INNER JOIN tl_user as user
                                            ON cl.syncTo_user = user.id
                                            WHERE cl.id = ?")
                ->limit(1)
                ->execute($this->Input->get("id"));

        if ($objSyncTime->syncFrom_tstamp != 0 && strlen($objSyncTime->syncFrom_user) != 0 && strlen($objSyncTime->syncFrom_alias) != 0)
        {
            $strLastSync = vsprintf($GLOBALS['TL_LANG']['MSC']['last_sync'], array(
                date($GLOBALS['TL_CONFIG']['timeFormat'], $objSyncTime->syncFrom_tstamp),
                date($GLOBALS['TL_CONFIG']['dateFormat'], $objSyncTime->syncFrom_tstamp),
                $objSyncTime->syncFrom_user,
                $objSyncTime->syncFrom_alias)
            );

            // Set data
//            $dc->setData("lastSync", "<p class='tl_info'>" . $strLastSync . "</p>");
        }
        else
        {
            $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default'] = str_replace(",lastSync", "", $GLOBALS['TL_DCA']['tl_syncCto_clients_syncFrom']['palettes']['default']);
        }
    }

    public function startConnection()
    {
        if ($this->objSyncCtoCommunicationClient == null)
        {
            // Build communication class
            $this->objSyncCtoCommunicationClient = SyncCtoCommunicationClient::getInstance();
            $this->objSyncCtoCommunicationClient->setClientBy($this->Input->get("id"));

            // Start connection
            $this->objSyncCtoCommunicationClient->startConnection();
        }
        else
        {
            return;
        }
    }

    public function stopConnection()
    {
        if ($this->objSyncCtoCommunicationClient != null)
        {
            try
            {
                // Stop connection
                $this->objSyncCtoCommunicationClient->stopConnection();
            }
            catch (Exception $exc)
            {
                // Do nothing
            }
        }
    }

    /**
     * Handle syncFrom configurations
     * 
     * @param DataContainer $dc
     * @return array 
     */
    public function onsubmit_callback(DataContainer $dc)
    {
        if(get_class($dc) != 'Dc_General')
		{
			return;
		}
        
        $strWidgetID     = $dc->getWidgetID();
        $arrSyncSettings = array();

        // Synchronization type
        if (is_array($this->Input->post("sync_options_" . $strWidgetID)) && count($this->Input->post("sync_options_" . $strWidgetID)) != 0)
        {
            $arrSyncSettings["syncCto_Type"] = $this->Input->post('sync_options_' . $strWidgetID);
        }
        else
        {
            $arrSyncSettings["syncCto_Type"] = array();
        }

        if ($this->Input->post("database_check_" . $strWidgetID) == 1)
        {
            $arrSyncSettings["syncCto_SyncDatabase"] = true;
        }
        else
        {
            $arrSyncSettings["syncCto_SyncDatabase"] = false;
        }

        // Systemoperation execute
        if ($this->Input->post("systemoperations_check_" . $strWidgetID) == 1)
        {
            if (is_array($this->Input->post("systemoperations_maintenance_" . $strWidgetID)) && count($this->Input->post("systemoperations_maintenance_" . $strWidgetID)) != 0)
            {
                $arrSyncSettings["syncCto_Systemoperations_Maintenance"] = $this->Input->post("systemoperations_maintenance_" . $strWidgetID);
            }
            else
            {
                $arrSyncSettings["syncCto_Systemoperations_Maintenance"] = array();
            }
        }
        else
        {
            $arrSyncSettings["syncCto_Systemoperations_Maintenance"] = array();
        }

        // Attention flag
        if ($this->Input->post("attentionFlag_" . $strWidgetID) == 1)
        {
            $arrSyncSettings["syncCto_AttentionFlag"] = true;
        }
        else
        {
            $arrSyncSettings["syncCto_AttentionFlag"] = false;
        }

        // Write all data
        foreach ($_POST as $key => $value)
        {
            $strClearKey                                = str_replace("_" . $strWidgetID, "", $key);
            $arrSyncSettings["post_data"][$strClearKey] = $this->Input->post($key);
        }

        $this->Session->set("syncCto_SyncSettings_" . $dc->id, $arrSyncSettings);

        $this->objSyncCtoHelper->checkSubmit(array(
            'postUnset' => array('start_sync'),
            'error' => array(
                'key'         => 'syncCto_submit_false',
                'message'     => $GLOBALS['TL_LANG']['ERR']['missing_tables']
            ),
            'redirectUrl' => $this->Environment->base . "contao/main.php?do=synccto_clients&amp;table=tl_syncCto_clients_syncFrom&amp;act=start&amp;step=0&amp;id=" . $this->Input->get("id")
        ));
    }

}