<?php 
session_start();
require('../../vendor/autoload.php');
use Emleons\Games\SecureLogin;
use Emleons\Games\Functions;


$secure = new SecureLogin;
$function = new Functions;
$reels = $function->get_reels('1');
$function->setGamesession();
$secure->gamelogics();
?>
<!DOCTYPE html>
<html >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Matunda | Bonanza</title>
  <link rel="stylesheet" href="../../node_modules/animate/animate.min.css">
  <link rel="stylesheet" href="../../node_modules/rocket/rocket.css">
  <link rel="stylesheet" href="../../node_modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="../../node_modules/rocket/rocket.js"></script>
 
  <script src="../../node_modules/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="../../node_modules/sweetalert/dist/sweetalert.min.js"></script>
  <script src="../../node_modules/jquery/jquery.min.js"></script>
</head>
<body>
  <div class="container">
    
  <div class="row">
    <div class="col-md-5 offset-md-4">
    <div class="card">
      <div class="card-header">
      <div class="row">
  <div id="num-board" class="col col ">
    <h5 id="gift" >0</h5>
  </div>
  <div id="num-board" class="col col ">
    <h5 id="h" >0</h5>
    
  </div>
  
</div>
<button type="button" class="btn btn-primary d-inline-flex p-2" data-bs-toggle="modal" data-bs-target="#historyModal">
    <i class="fa fa-history"></i>
  </button>
  <button type="button" id="play-pause" class="btn btn-primary d-inline-flex p-2" >
    <i class="fa fa-volume-up"></i>
  </button>
  <button type="button" id="clear-bet" class="btn btn-primary d-inline-flex p-2" >
    <i class="fa fa-trash-o "></i>
  </button>
      </div>
      <div id="real-board" class="card-body">
      <div class="row">
      <?php
$fruits = $reels; 


foreach ($fruits as $index => $fruit) {
  $imageName = strtolower($fruit["reel_name"]) . ".png";
  echo "<div id='reel-".($index+1)."' class='reel col-md-3'>";
  echo '<img class="fruits-image" src="reels/'.$imageName.'">';
  echo '<span >'. "".'</span>';
  echo "</div>";
  
}

?>
      </div>
      </div>
      <div class="card-footer">
        <center>
        <button id="spin-btn" class="btn btn-primary">Start</button>
        </center>
      </div>
    </div>
    </div>
    </div>
  </div>
  <!-- Modal -->
  <div class="modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyModalLabel">History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="">
          <div class="card">
            <div class="">
              <table class="table" id="historyTable">
                <thead>
                  <tr>
                    <th>Reel</th>
                    <th>Cost</th>
                    <th>Result</th>
                    <th>Status</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
              <div class="card-footer">
                <nav aria-label="Pagination">
                  <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table" id="historyTable">
                            <thead>
                                <tr>
                                    <th>Bet</th>
                                    <th>Cost</th>
                                    <th>Result</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Pagination">
                            <ul class="pagination justify-content-center" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var currentPage = 1;
        var rowsPerPage = 8; // Number of rows to display per page

        $('#historyModal').on('shown.bs.modal', function() {
            fetchData(currentPage);
        });

        function fetchData(page) {
            $.ajax({
                url: 'history.php',
                method: 'GET',
                dataType: 'json',
                data: { page: page, rowsPerPage: rowsPerPage },
                success: function(response) {
                    var totalRows = response.length;
                    var totalPages = Math.ceil(totalRows / rowsPerPage);

                    var tableBody = $('#historyTable tbody');
                    tableBody.empty();

                    if (response && response.length > 0) {
                        response.forEach(function(row) {
                            var tr = $('<tr>');
                            tr.append('<td>' + row.reel + '</td>');
                            tr.append('<td>' + row.cost + '</td>');
                            tr.append('<td>' + row.result_reel + '</td>');
                            if(row.status =="lost"){
                              tr.append('<td class="text-danger">' + row.status + '</td>');
                            }else if(row.status =="won"){
                              tr.append('<td class="text-success" >' + row.status + '</td>');
                            }else{
                              tr.append('<td  >' + row.status + '</td>');
                            }
                            tr.append('<td>' + row.date + '</td>');

                            tableBody.append(tr);
                        });
                    } else {
                        tableBody.html('<tr><td colspan="5">No data available</td></tr>');
                    }

                    renderPagination(totalPages, page);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    var tableBody = $('#historyTable tbody');
                    tableBody.empty();
                    tableBody.html('<tr><td colspan="5">Error occurred while fetching data</td></tr>');
                }
            });
        }

        function renderPagination(totalPages, currentPage) {
            var paginationElement = $('#pagination');
            paginationElement.empty();

            var previousButton = $('<li class="page-item"><a class="page-link" href="#">Previous</a></li>');
            if (currentPage === 1) {
                previousButton.addClass('disabled');
            } else {
                previousButton.on('click', function() {
                    fetchData(currentPage - 1);
                });
            }
            paginationElement.append(previousButton);

            for (var i = 1; i <= totalPages; i++) {
                var pageButton = $('<li class="page-item"><a class="page-link" href="#">' + i + '</a></li>');
                if (i === currentPage) {
                    pageButton.addClass('active');
                } else {
                    pageButton.on('click', function() {
                        fetchData(parseInt($(this).text()));
                    });
                }
                paginationElement.append(pageButton);
            }

            var nextButton = $('<li class="page-item"><a class="page-link" href="#">Next</a></li>');
            if (currentPage === totalPages) {
                nextButton.addClass('disabled');
            } else {
                nextButton.on('click', function() {
                    fetchData(currentPage + 1);
                });
            }
            paginationElement.append(nextButton);
        }
    });
</script>



  <audio id="kosa" hidden src="assets/sounds/error.mp3"></audio>
  <audio id="spining" hidden src="assets/sounds/spin.mp3"></audio>
  <audio id="insert" hidden src="assets/sounds/insert.mp3"></audio>
  <audio id="pata" hidden src="assets/sounds/win.mp3"></audio>
  
  <audio id="biti" hidden src="assets/sounds/jungle-bg.mp3" autoplay="autoplay" loop="loop"></audio>
<script src="script.js"></script>
</body>
</html>
