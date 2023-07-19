<?php 
include('includes/app_header.php');
$action = (isset($_GET['action']))?$_GET['action']:null;
$games = $home->getGames(); 
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Michezo</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <?php
       if(sizeof($games)<1){
        echo '<h3>Hakuna michezo kwa saka</h3>';
       }else{
        foreach ($games as $index => $game) {
            echo '<div class="col-xl-3 col-md-6 mb-4">';
            echo '<a class="game-link" style="text-decoration: none;" href="' . $game['path'] . '">';
            echo '<div class="card border-bottom-secondary shadow h-100 py-2">';
            echo '<img class="card-img-top" src="' . $game['image'] . '" alt="gameIMG" >';
            echo '<div class="card-footer">' . $game['game_name'] . '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
       }
        
        
        ?>
        
    </div>
    <?php
        $games = $home->getGames();
        $total_pages = ceil(count($games) / 10); // Calculate the total number of pages
        $current_page = 1; // Set the current page to 1

        if (isset($_GET['page'])) {
            $current_page = $_GET['page'];
        }

        echo '<ul class="pagination">';
        for ($i = 1; $i <= $total_pages; $i++) {
            $active = ($current_page == $i) ? 'active' : '';
            echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
        }
        echo '</ul>';
        ?>

<div id="loading-spinner"></div>



</div>
<!-- /.container-fluid -->
<?php
if($action == null){

}else{
    if($action == "login"){
        $swal = "Swal.fire({
            icon: 'success',
            title: 'Hongera',
            text: 'Akaunti yako imewezeshwa Bofya kitufe cha Inigia kisha ingia katika Akaunti yako ufurahie michezo yetu!',
            footer: '<small>akaunti imewezeshwa</small>'
          })";
        echo '<script>'.$swal.'</script>';
    }
}
?>
<?php include('includes/app_footer.php') ?>