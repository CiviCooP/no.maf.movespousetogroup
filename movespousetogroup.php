<?php

require_once 'movespousetogroup.civix.php';

/**
 * Function to retrieve groups in Donor Journey
 *
 * At the moment this function depends on a similair function
 * in the no.maf.ocr extension.
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @return array $groupList
 *
 */
function movespousetogroup_get_donorgroups() {
  return ocr_get_donorgroups();
}

/**
 * Function to check whether a group is a donor journey group.
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @param int $groupId
 * @return bool
 */
function movespousetogroup_is_donor_group($groupId) {
  $donor_groups = movespousetogroup_get_donorgroups();
  $donor_group_ids = array_keys($donor_groups);
  if (in_array($groupId, $donor_group_ids)) {
    return true;
  }
  return false;
}

function movespousetogroup_civicrm_post($op, $objectName, $groupId, $contactIds) {
  $handler = CRM_Movespousetogroup_Handler::getInstance();
  $handler->post($op, $objectName, $groupId, $contactIds);
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function movespousetogroup_civicrm_config(&$config) {
  _movespousetogroup_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function movespousetogroup_civicrm_xmlMenu(&$files) {
  _movespousetogroup_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function movespousetogroup_civicrm_install() {
  _movespousetogroup_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function movespousetogroup_civicrm_uninstall() {
  _movespousetogroup_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function movespousetogroup_civicrm_enable() {
  _movespousetogroup_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function movespousetogroup_civicrm_disable() {
  _movespousetogroup_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function movespousetogroup_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _movespousetogroup_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function movespousetogroup_civicrm_managed(&$entities) {
  _movespousetogroup_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function movespousetogroup_civicrm_caseTypes(&$caseTypes) {
  _movespousetogroup_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function movespousetogroup_civicrm_angularModules(&$angularModules) {
_movespousetogroup_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function movespousetogroup_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _movespousetogroup_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function movespousetogroup_civicrm_preProcess($formName, &$form) {

}

*/
