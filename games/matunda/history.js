$(document).ready(function() {
    $('#historyModal').on('shown.bs.modal', function() {
      // Make an AJAX request
      $.ajax({
        url: 'history.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          // Update the table with the fetched data
          var tableBody = $('#historyModal').find('tbody');
          tableBody.empty();
  
          if (response.length > 0) {
            for (var i = 0; i < response.length; i++) {
              var row = response[i];
              var html = '<tr>' +
                '<td>' + row.reel + '</td>' +
                '<td>' + row.cost + '</td>' +
                '<td>' + row.result_reel + '</td>' +
                '<td>' + row.status + '</td>' +
                '<td>' + row.date + '</td>' +
                '</tr>';
  
              tableBody.append(html);
            }
          } else {
            tableBody.append('<tr><td colspan="5">No data available</td></tr>');
          }
        },
        error: function() {
          // Handle the error case
          var tableBody = $('#historyModal').find('tbody');
          tableBody.empty();
          tableBody.append('<tr><td colspan="5">Error occurred while fetching data</td></tr>');
        }
      });
    });
  });
  