<?php

class Contacts
{
 private $conn;

 public function __construct($db)
 {
  $this->conn = $db;
 }

 public function getContacts()
 {
  $query = "SELECT c.*, g.group_name FROM contact_list c LEFT JOIN group_name g ON c.name = g.name";
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }




 public function addContact($name, $phone, $nickname, $email, $groupName = null)
 {
  $query = "INSERT INTO contact_list (name, phone, nickname, email) VALUES (:name, :phone, :nickname, :email)";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":phone", $phone);
  $stmt->bindParam(":nickname", $nickname);
  $stmt->bindParam(":email", $email);
  $stmt->execute();


  if ($groupName !== null) {
   $this->addGroupName($name, $groupName);
  }

  return true;
 }
 public function getGroupNameByContactId($id)
 {
  $query = "SELECT group_name FROM group_name WHERE name = (SELECT name FROM contact_list WHERE id = :id)";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result ? $result['group_name'] : null;
 }
 public function updateGroupName($id, $groupName)
 {
  $query = "UPDATE group_name SET group_name = :group_name WHERE name = (SELECT name FROM contact_list WHERE id = :id)";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->bindParam(":group_name", $groupName);
  return $stmt->execute();
 }

 private function addGroupName($name, $groupName)
 {
  $query = "INSERT INTO group_name (name, group_name) VALUES (:name, :group_name)";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":group_name", $groupName);
  $stmt->execute();
 }

 public function getContactByName($name)
 {
  $query = "SELECT * FROM contact_list WHERE name = :name";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":name", $name);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
 }

 public function getContactById($id)
 {
  $query = "SELECT * FROM contact_list WHERE id = :id";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
 }
 public function editContact($id, $name, $phone, $nickname, $email)
 {
  $query = "UPDATE contact_list SET name=:name, phone=:phone, nickname=:nickname, email=:email WHERE id=:id";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->bindParam(":name", $name);
  $stmt->bindParam(":phone", $phone);
  $stmt->bindParam(":nickname", $nickname);
  $stmt->bindParam(":email", $email);
  return $stmt->execute();
 }


 public function deleteContact($id)
 {
  $contact = $this->getContactById($id);
  if (!$contact) {
   return false;
  }
  $name = $contact['name'];

  $query = "DELETE FROM contact_list WHERE id=:id";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":id", $id);
  $stmt->execute();

  $query = "DELETE FROM group_name WHERE name=:name";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":name", $name);
  $stmt->execute();

  return true;
 }
 public function getContactsByGroup($groupName)
 {
  $query = "SELECT c.*, g.group_name FROM contact_list c LEFT JOIN group_name g ON c.name = g.name WHERE g.group_name = :group_name";
  $stmt = $this->conn->prepare($query);
  $stmt->bindParam(":group_name", $groupName);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
 public function getUniqueGroups()
 {
  $query = "SELECT DISTINCT group_name FROM group_name";
  $stmt = $this->conn->prepare($query);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
 }
}
