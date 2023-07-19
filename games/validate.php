<?php
include('includes/app_header.php');
?>
<style>
    input[type="number"] {
        text-align: center;
        -webkit-appearance: none;
        -moz-appearance: none;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }
</style>
<div class="container">

    <!-- Outer Row -->
    <div class="row ">

        <div class="col-md-4 offset-md-4">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-2">Wezesha Akaunti yako</h1>
                                    <p class="mb-4">Tafadhali ingiza msimbo uliotumwa katkia SMS</p>
                                </div>
                                <form id="otpform" class="user" method="POST">
                                    <div class="form-group">
                                        <input type="number" class="form-control form-control-user align-content-center" id="exampleInputEmail" name="otp" aria-describedby="emailHelp" placeholder="X X X X X X" maxlength="6">

                                    </div>
                                    <input type="submit" class="btn btn-primary btn-user btn-block" value="Wezesha account" />
                                    
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="resend.php">Tuma tena</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $otp = $_POST['otp'];
        $functions->activateAccount($otp);
    }
    ?>
</div>
<script src="accounts.js"></script>
<?php include('includes/app_footer.php'); ?>