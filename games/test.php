<?php 
include('includes/app_header.php');
?>
<?php use Emleons\Games\Changanya; ?>
<?php 
$changa = new Changanya;
var_dump($changa->shuffleit([2,4,2,2,4,4]));
?>

<?php include('includes/app_footer.php') ?>