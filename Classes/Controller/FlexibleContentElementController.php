<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Claus Due <claus@wildside.dk>, Wildside A/S
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Flexible Content Element Plugin Rendering Controller
 *
 * @package Fed
 * @subpackage Controller
 */
class Tx_Fed_Controller_FlexibleContentElementController extends Tx_Fed_MVC_Controller_AbstractController {

	/**
	 * Show template as defined in flexform
	 * @return string
	 */
	public function renderAction() {
		/** @var Tx_Flux_MVC_View_ExposedTemplateView $view */
		$view = $this->objectManager->create('Tx_Flux_MVC_View_ExposedTemplateView');
		$cObj = $this->configurationManager->getContentObject();
		$this->flexform->setContentObjectData($cObj->data);
		/** @var Tx_Fed_Configuration_ConfigurationManager $configurationManager */
		$configurationManager = $this->objectManager->get('Tx_Fed_Configuration_ConfigurationManager');
		list ($extensionName, $filename) = explode(':', $cObj->data['tx_fed_fcefile']);
		$paths = $configurationManager->getContentConfiguration($extensionName);
		$absolutePath = $paths['templateRootPath'] . '/' . $filename;
		$view->setLayoutRootPath($paths['layoutRootPath']);
		$view->setPartialRootPath($paths['partialRootPath']);
		$view->setTemplatePathAndFilename($absolutePath);
		$view->setControllerContext($this->controllerContext);
		$config = $view->getStoredVariable('Tx_Flux_ViewHelpers_FlexformViewHelper', 'storage', 'Configuration');
		$variables = $this->flexform->getAllAndTransform($config['fields']);
		$variables['page'] = $GLOBALS['TSFE']->page;
		$variables['record'] = $cObj->data;
		$variables['contentObject'] = $cObj;
		$variables['settings'] = $this->settings;
		return $view->renderStandaloneSection('Main', $variables);
	}

}
