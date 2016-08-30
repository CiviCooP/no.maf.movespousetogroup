<?php
/**
 * Class to handle the the spouse
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */
class CRM_Movespousetogroup_Handler {

  /**
   * @var array
   */
  private $allreadyProcessedContacts = array();

  /**
   * @var CRM_Movespousetogroup_Handler
   */
  private static $instance;

  private $spouseRelationshipTypeId;

  private function __construct() {

  }

  /**
   * @return \CRM_Movespousetogroup_Handler
   */
  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new CRM_Movespousetogroup_Handler();
    }
    return self::$instance;
  }

  public function post($op, $objectName, $groupId, $contactIds) {
    if (!$this->isPostHookValid($op, $objectName, $groupId, $contactIds)) {
      return;
    }

    // Check whether which contacts are already processed.
    $contactsToProcess = array();
    foreach($contactIds as $cid) {
      if (!in_array($cid, $this->allreadyProcessedContacts)) {
        $contactsToProcess[] = $cid;
        $this->allreadyProcessedContacts[] = $cid;
      }
    }

    if ($op == 'create' || $op == 'edit') {
      $this->addSpousesToGroup($groupId, $contactsToProcess);
    } elseif ($op == 'delete') {
      // Determine the method and the status. This is need to keep the
      // subscription history in sync. E.g. a hard delete should also result
      // in a hard delete for the spouses.
      $method = 'Admin';
      $deleteStatus = 'Deleted';
      $sql = "SELECT * FROM civicrm_subscription_history WHERE group_id = %1 AND contact_id = %2 ORDER BY `date` DESC LIMIT 0,1";
      $params[1] = array($groupId, 'Integer');
      $params[2] = array(reset($contactsToProcess), 'Integer');
      $dao = CRM_Core_DAO::executeQuery($sql, $params);
      if ($dao->fetch()) {
        $method = $dao->method;
        $deleteStatus = $dao->status;
      }

      $this->removeSpousesFromGroup($groupId, $contactsToProcess, $method, $deleteStatus);
    }
  }

  /**
   * Add the spouses of the contacts also to the same group.
   *
   * @param $groupId
   * @param array $contactids
   */
  private function addSpousesToGroup($groupId, $contactids) {
    $spouses = $this->getSpouses($contactids);
    if (count($spouses)) {
      CRM_Contact_BAO_GroupContact::addContactsToGroup($spouses, $groupId);
    }
  }

  /**
   * Remove spouses of the contacts also from the same group.
   *
   * @param $groupId
   * @param array $contactids
   * @param string $method
   * @param string $deleteStatus
   */
  private function removeSpousesFromGroup($groupId, $contactids, $method, $deleteStatus) {
    $spouses = $this->getSpouses($contactids);
    if (count($spouses)) {
      CRM_Contact_BAO_GroupContact::removeContactsFromGroup($spouses, $groupId, $method, $deleteStatus);
    }
  }

  /**
   * Returns an array with all related spouses.
   * The array contains only the contact ids of the related spouses.
   *
   * @param $contactIds
   * @return array
   */
  private function getSpouses($contactIds) {
    $spouses = array();

    $spouseRelationshipType = $this->getSpouseRelationshipTypeId();
    if (!$spouseRelationshipType || !count($contactIds)) {
      return $spouses;
    }

    $sql = "SELECT contact_id_a as contact_id
            FROM civicrm_relationship 
            WHERE relationship_type_id = %1 
            AND contact_id_b IN (".implode(", ", $contactIds).")
            AND is_active = 1 
            AND (civicrm_relationship.start_date is null or civicrm_relationship.start_date <= NOW()) 
            AND (civicrm_relationship.end_date is null OR civicrm_relationship.end_date >= NOW())
            UNION SELECT contact_id_b as contact_id
            FROM civicrm_relationship 
            WHERE relationship_type_id = %1 
            AND contact_id_a IN (".implode(", ", $contactIds).")
            AND is_active = 1 
            AND (civicrm_relationship.start_date is null or civicrm_relationship.start_date <= NOW()) 
            AND (civicrm_relationship.end_date is null OR civicrm_relationship.end_date >= NOW())
            ";
    $params[1] = array($spouseRelationshipType, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    while ($dao->fetch()) {
      if (!in_array($dao->contact_id, $this->allreadyProcessedContacts)) {
        $spouses[] = $dao->contact_id;
      }
    }
    return $spouses;
  }

  /**
   * Returns the relationship type id for a Spouse Of relationship.
   *
   * @return int
   */
  private function getSpouseRelationshipTypeId() {
    if (!$this->spouseRelationshipTypeId) {
      $this->spouseRelationshipTypeId = CRM_Core_DAO::singleValueQuery("SELECT id FROM civicrm_relationship_type where name_a_b = 'Spouse of'");
    }
    return $this->spouseRelationshipTypeId;
  }

  /**
   * Returns true whether the posthook is valid for processing GroupContact
   * Also checks whether the group is a donor journey group.
   *
   * @param $op
   * @param $objectName
   * @param $groupId
   * @param $contactIds
   * @return bool
   */
  private function isPostHookValid($op, $objectName, $groupId, $contactIds) {
    if ($objectName != 'GroupContact') {
      return false;
    }
    if ($op != 'create' && $op != 'delete' && $op != 'edit') {
      return false;
    }
    if (!movespousetogroup_is_donor_group($groupId)) {
      return false;
    }
    return true;
  }

}