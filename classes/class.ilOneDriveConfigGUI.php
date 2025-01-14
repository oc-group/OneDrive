<?php
require_once('Customizing/global/plugins/Modules/Cloud/CloudHook/OneDrive/vendor/autoload.php');
/**
 * Class ilOneDriveConfigGUI
 *
 * @author Fabian Schmid <fs@studer-raimann.ch>
 */
class ilOneDriveConfigGUI extends ilCloudPluginConfigGUI {

	const IL_CHECKBOX_INPUT_GUI = 'ilCheckboxInputGUI';
	const IL_TEXT_INPUT_GUI = 'ilTextInputGUI';
	const IL_TEXTAREA_INPUT_GUI = 'ilTextAreaInputGUI';
	const IL_NUMBER_INPUT_GUI = 'ilNumberInputGUI';
	const IL_SELECT_INPUT_GUI = 'ilSelectInputGUI';


	/**
	 * @return array
	 */
	public function getFields() {
		return array(
			exodConfig::F_CLIENT_TYPE    => array(
				'type'        => self::IL_SELECT_INPUT_GUI,
				'options'     => array(
					exodApp::TYPE_BUSINESS => 'OneDrive Business',
					exodApp::TYPE_PUBLIC   => 'OneDrive',
				),
				'info'        => 'config_info_client_type',
				'subelements' => null,
			),
			exodConfig::F_CLIENT_ID      => array(
				'type'        => self::IL_TEXT_INPUT_GUI,
				'info'        => 'config_info_client_id',
				'subelements' => null,
			),
			exodConfig::F_CLIENT_SECRET  => array(
				'type'        => self::IL_TEXT_INPUT_GUI,
				'info'        => 'config_info_client_secret',
				'subelements' => null,
			),
			exodConfig::F_TENANT_NAME    => array(
				'type'        => self::IL_TEXT_INPUT_GUI,
				'info'        => 'config_info_tenant_name',
				'subelements' => null,
			),
			exodConfig::F_TENANT_ID      => array(
				'type'        => self::IL_TEXT_INPUT_GUI,
				'info'        => 'config_info_tenant_id',
				'subelements' => null,
			),
			exodConfig::F_IP_RESOLVE_V_4 => array(
				'type'        => self::IL_CHECKBOX_INPUT_GUI,
				'info'        => 'config_info_ip_resolve_v4',
				'subelements' => null,
			),
			exodConfig::F_SSL_VERSION    => array(
				'type'        => self::IL_SELECT_INPUT_GUI,
				'options'     => array(
					CURL_SSLVERSION_DEFAULT => 'Standard',
					CURL_SSLVERSION_TLSv1   => 'TLSv1',
					CURL_SSLVERSION_SSLv2   => 'SSLv2',
					CURL_SSLVERSION_SSLv3   => 'SSLv3',
				),
				'info'        => 'config_info_ssl_version',
				'subelements' => null,
			),
            exodConfig::F_O365_MAPPING => array(
                'type'          => self::IL_SELECT_INPUT_GUI,
                'options'       => $this->getMappingOptions(),
                'info'          => 'config_info_o365_mapping',
                'subelements'   => null
            ),
            exodConfig::F_EMAIL_MAPPING_HOOK_ACTIVE      => array(
                'type'        => self::IL_CHECKBOX_INPUT_GUI,
                'info'        => exodConfig::F_EMAIL_MAPPING_HOOK_ACTIVE . '_info',
                'subelements' => array(
                    exodConfig::F_EMAIL_MAPPING_HOOK_PATH      => array(
                        'type'        => self::IL_TEXT_INPUT_GUI,
                        'subelements' => null,
                        'required'  => true
                    ),
                    exodConfig::F_EMAIL_MAPPING_HOOK_CLASS      => array(
                        'type'        => self::IL_TEXT_INPUT_GUI,
                        'info'        => exodConfig::F_EMAIL_MAPPING_HOOK_ACTIVE . '_' . exodConfig::F_EMAIL_MAPPING_HOOK_CLASS . '_info',
                        'subelements' => null,
                        'required'  => true
                    ),
                ),
            ),
			exodConfig::F_INFO_MESSAGE => array(
                'type'        => self::IL_TEXTAREA_INPUT_GUI,
                'info'        => exodConfig::F_INFO_MESSAGE . '_info',
                'subelements' => null,
        ),
		);
	}


    /**
     * @return ilPropertyFormGUI
     */
    public function initConfigurationForm() {
		global $lng, $ilCtrl;

		include_once("./Services/Form/classes/class.ilPropertyFormGUI.php");
		$this->form = new ilPropertyFormGUI();

		foreach ($this->fields as $key => $item) {
			$field = new $item["type"]($this->plugin_object->txt($key), $key);
			if ($item["type"] == self::IL_SELECT_INPUT_GUI) {
				$field->setOptions($item['options']);
			}
			$field->setInfo($this->plugin_object->txt($item["info"]));
			$field->setRequired(isset($item['required']));
			if (is_array($item["subelements"])) {
				foreach ($item["subelements"] as $subkey => $subitem) {
					$subfield = new $subitem["type"]($this->plugin_object->txt($key . "_"
					                                                           . $subkey), $key
					                                                                       . "_"
					                                                                       . $subkey);
					if ($subitem["info"]) {
                        $subfield->setInfo($this->plugin_object->txt($subitem["info"]));
                    }
                    $subfield->setRequired(isset($subitem['required']));
					$field->addSubItem($subfield);
				}
			}

			$this->form->addItem($field);
		}

		$this->form->addCommandButton("save", $lng->txt("save"));

		$this->form->setTitle($this->plugin_object->txt("common_configuration"));
		$this->form->setFormAction($ilCtrl->getFormAction($this));

		return $this->form;
	}


    /**
     * @return array
     */
    protected function getMappingOptions()
    {
        global $DIC;

        $options = [
            'email' => $DIC->language()->txt('email'),
            'ext_id' => $DIC->language()->txt('user_ext_account')
        ];
        $definitions = array_map(function($element) {
            return $element['field_name'];
        }, ilUserDefinedFields::_getInstance()->getDefinitions());
        return array_merge($options, $definitions);
    }
}

