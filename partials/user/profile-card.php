<?php
    $userdata = getCurrentUser();
    $username = $userdata['username'];
    $email = $userdata['email'];
    $imagename = $userdata['image'];
   
?>
<div class="card mb-4">
    <div class="card-body text-center d-flex">
        <!-- image profile-->
        <a href="<?php echo BASE_URL; ?>/profile">
    <img class="user-image" src="uploads/users/<?php echo $imagename; ?>" alt="User Image">
</a>

        <h5 class="my-3">
            <?php echo $username; ?>
        </h5>
        <!-- <p class="text-muted mb-1">
            <?php echo $email; ?>
        </p> -->
        <!-- <div class="d-flex justify-content-center mb-2">
            <a class="btn btn-primary" id="user-edit" href="<?php echo BASE_URL; ?>/edit-profile"
            role="button">Edit</a>
        </div> -->
    </div>
</div>