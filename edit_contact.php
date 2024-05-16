<?php
require_once 'classes/Database.php';
require_once 'classes/Contacts.php';

$db = new Database();
$conn = $db->connect();
$contacts = new Contacts($conn);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $error = '';
 if (isset($_POST['id'])) {
  $error = '';
  $id = $_POST['id'];
  if (isset($_POST['edit'])) {
   if (empty($_POST['name'])) {
    $error = "Please enter a name.";
   } elseif (empty($_POST['phone'])) {
    $error = "Please enter a phone number.";
   } elseif (empty($_POST['nickname'])) {
    $error = "Please enter a nickname.";
   } elseif (empty($_POST['email'])) {
    $error = "Please enter an email address.";
   } else {
    $error = '';
    $contacts->editContact($id, $_POST['name'], $_POST['phone'], $_POST['nickname'], $_POST['email']);
    if (!empty($_POST['group_name'])) {
     $contacts->updateGroupName($id, $_POST['group_name']);
    }

    header("Location: index.php");
    exit();
   }
  } elseif (isset($_POST['back'])) {
   $error = '';
   header("Location: index.php");
   exit();
  }
 } else {
  $error = "ID is not set.";
 }
} else {
 $error = '';
}

$contactDetails = [];
if (isset($_POST['id'])) {
 $id = $_POST['id'];
 $contactDetails = $contacts->getContactById($id);
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
 <h2>Edit contact</h2>
 <form method="POST">
  <input type="text" name="name" placeholder="Name" value="<?php echo isset($contactDetails['name']) ? $contactDetails['name'] : ''; ?>">
  <input type="text" name="phone" placeholder="Phone" value="<?php echo isset($contactDetails['phone']) ? $contactDetails['phone'] : ''; ?>">
  <input type="text" name="nickname" placeholder="Nickname" value="<?php echo isset($contactDetails['nickname']) ? $contactDetails['nickname'] : ''; ?>">
  <input type="email" name="email" placeholder="Email" value="<?php echo isset($contactDetails['email']) ? $contactDetails['email'] : ''; ?>">
  <input type="text" name="group_name" placeholder="Group" value="<?php echo isset($_POST['id']) ? $contacts->getGroupNameByContactId($_POST['id']) : ''; ?>">
  <input type="hidden" name="id" value="<?php echo isset($_POST['id']) ? $_POST['id'] : ''; ?>">
  <button type="submit" name="edit">Edit Contact</button>
  <button type="submit" name="back">Back</button>
 </form>
 <?php if (!empty($error) && $_SERVER['REQUEST_METHOD'] == 'POST') : ?>
  <p><?php echo $error; ?></p>
 <?php endif; ?>
</body>

</html>