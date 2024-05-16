<?php
require_once 'classes/Database.php';
require_once 'classes/Contacts.php';

$db = new Database();
$conn = $db->connect();
$contacts = new Contacts($conn);
$groupFilter = isset($_GET['group']) ? $_GET['group'] : null;
$filteredContacts = $groupFilter ? $contacts->getContactsByGroup($groupFilter) : $contacts->getContacts();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_POST['add'])) {
  header("Location: add_contact.php");
 } elseif (isset($_POST['editt'])) {
  header("Location: edit_contact.php");
 } elseif (isset($_POST['delete'])) {
  $contacts->deleteContact($_POST['id']);
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

 <form method="GET">
  <label for="group">Filter by Group:</label>
  <select name="group" id="group">
   <option value="">All</option>
   <?php foreach ($contacts->getAllGroups() as $group) : ?>
    <option value="<?php echo $group['group_name']; ?>" <?php echo $groupFilter === $group['group_name'] ? 'selected' : ''; ?>><?php echo $group['group_name']; ?></option>
   <?php endforeach; ?>
  </select>
  <button type="submit">Filter</button>
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
  <?php foreach ($filteredContacts as $contact) : ?>
   <tr>
    <td><?php echo $contact['name']; ?></td>
    <td><?php echo $contact['phone']; ?></td>
    <td><?php echo $contact['nickname']; ?></td>
    <td><?php echo $contact['email']; ?></td>
    <td><?php echo $contact['group_name']; ?></td>
    <td>
     <form method="POST" action="edit_contact.php" style="display: inline;">
      <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
      <button type="submit" name="editt">Edit</button>
     </form>
     <form method="POST" style="display: inline;">
      <input type="hidden" name="id" value="<?php echo $contact['id']; ?>">
      <button type="submit" name="delete">Delete</button>
     </form>
    </td>
   </tr>
  <?php endforeach; ?>
 </table>
</body>

</html>