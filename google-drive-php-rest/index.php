<?php
include_once 'config.php';
$status = $statusMsg = '';
if (!empty($status_response)) {
    $status_response = $_SESSION['status_response']; 
    $status = $status_response['status']; 
    $statusMsg = $status_response['status_msg']; 
    unset($_SESSION['status_response']);
}
?>

<!-- Status message -->
<?php if(!empty($statusMsg)){ ?>
    <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
<?php } ?>

<div class="col-md-12">
    <form method="post" action="upload.php" class="form" enctype="multipart/form-data">
        <div class="form-group">
            <input type="submit" class="form-control btn-primary" name="submit" value="Google Drive"/>
        </div>
    </form>
    <form method="post" action="workspace.php" class="form" enctype="multipart/form-data">
        <div class="form-group">
            <input type="submit" class="form-control btn-primary" name="submit" value="Workspace Admin"/>
        </div>
    </form>
</div>