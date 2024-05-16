<?php
require_once 'classes/Database.php';
require_once 'classes/Contacts.php';

$db = new Database();
$conn = $db->connect();
$contacts = new Contacts($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_POST['add'])) {
  header("Location: add_contact.php");
 } elseif (isset($_POST['editt'])) {
  header("Location: edit_contact.php");
 } elseif (isset($_POST['delete'])) {
  $contacts->deleteContact($_POST['id']);
 } elseif (isset($_POST['filter_group'])) {
  $selected_group = $_POST['group'];
  if ($selected_group === 'all') {
   $filtered_contacts = $contacts->getContacts();
  } else {
   $filtered_contacts = $contacts->getContactsByGroup($selected_group);
  }
 }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Contact List</title>
 <link rel="stylesheet" href="style.css">
</head>

<body>
 <h2>Contact List</h2>
 <form method="POST">
  <button type="submit" name="add">Add Contact</button>
 </form>

 <form method="POST">
  <label for="group">Filter by Group:</label>
  <select name="group" id="group">
   <option value="all">All</option>
   <?php
   $groups = $contacts->getUniqueGroups();
   foreach ($groups as $group) {
    if($group['group_name'] == ""){
     echo "<option value='" . $group['group_name'] . "'>" . "Ungrouped" . "</option>";
    }else{
     echo "<option value='" . $group['group_name'] . "'>" . $group['group_name'] . "</option>";
    }
    
   }
   ?>
  </select>
  <button type="submit" name="filter_group">Filter</button>
 </form>

 <table>
  <tr>
   <th>Name</th>
   <th>Phone</th>
   <th>Nickname</th>
   <th>Email</th>
   <th>Group</th>
   <th>Action</th>
  </tr>
  <?php
  if (isset($filtered_contacts)) {
   foreach ($filtered_contacts as $contact) {
    echo "<tr>";
    echo "<td>" . $contact['name'] . "</td>";
    echo "<td>" . $contact['phone'] . "</td>";
    echo "<td>" . $contact['nickname'] . "</td>";
    echo "<td>" . $contact['email'] . "</td>";
    echo "<td>" . $contact['group_name'] . "</td>";
    echo "<td>
                        <form method='POST' action='edit_contact.php' style='display: inline;'>
                            <input type='hidden' name='id' value='" . $contact['id'] . "'>
                            <button type='submit' name='editt'>Edit</button>
                        </form>
                        <form method='POST' style='display: inline;'>
                            <input type='hidden' name='id' value='" . $contact['id'] . "'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                    </td>";
    echo "</tr>";
   }
  } else {
   foreach ($contacts->getContacts() as $contact) {
    echo "<tr>";
    echo "<td>" . $contact['name'] . "</td>";
    echo "<td>" . $contact['phone'] . "</td>";
    echo "<td>" . $contact['nickname'] . "</td>";
    echo "<td>" . $contact['email'] . "</td>";
    echo "<td>" . $contact['group_name'] . "</td>";
    echo "<td>
                        <form method='POST' action='edit_contact.php' style='display: inline;'>
                            <input type='hidden' name='id' value='" . $contact['id'] . "'>
                            <button type='submit' name='editt'>Edit</button>
                        </form>
                        <form method='POST' style='display: inline;'>
                            <input type='hidden' name='id' value='" . $contact['id'] . "'>
                            <button type='submit' name='delete'>Delete</button>
                        </form>
                    </td>";
    echo "</tr>";
   }
  }
  ?>
 </table>
</body>

</html>