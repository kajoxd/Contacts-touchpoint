<?php
require_once 'classes/Database.php';
require_once 'classes/Contacts.php';

$db = new Database();
$conn = $db->connect();
$contacts = new Contacts($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 if (isset($_POST['add'])) {
  if (empty($_POST['name'])) {
   $error = "Please enter a name.";
  } elseif (!ctype_digit($_POST['phone']) || strlen($_POST['phone']) > 10) {
   $error = "Please enter a valid phone number containing only digits and up to 10 characters.";
  } elseif (empty($_POST['nickname'])) {
   $error = "Please enter a nickname.";
  } elseif (empty($_POST['email'])) {
   $error = "Please enter an email address.";
  } else {

   $existingContact = $contacts->getContactByName($_POST['name']);
   if ($existingContact) {
    $error = "User already exists.";
   } else {
    $contacts->addContact($_POST['name'], $_POST['phone'], $_POST['nickname'], $_POST['email'], $_POST['group_name']);
    header("Location: index.php");
   }
  }
 } elseif (isset($_POST['back'])) {
  header("Location: index.php");
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
 <h2>Add contact</h2>
 <form method="POST">
  <input type="text" name="name" placeholder="Name">
  <input type="text" name="phone" placeholder="Phone">
  <input type="text" name="nickname" placeholder="Nickname">
  <input type="email" name="email" placeholder="Email">
  <input type="text" name="group_name" placeholder="Group">
  <button type="submit" name="add">Add Contact</button>
  <button type="submit" name="back">Back</button>
 </form>
 <?php if (isset($error)) : ?>
  <p><?php echo $error; ?></p>
 <?php endif; ?>
</body>

</html>