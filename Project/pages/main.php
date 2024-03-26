<?php include '../connection/redirect.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main Page</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
<div class="contain-fluid">
<nav class="sticky-top top-0" style="background-color:white; z-index:999;">
      <ul>
        <li>
          <a class="active" href="#">Main</a>
        </li>
        <li>
          <a href="test.php">Test</a>
        </li>
        <li>
          <a href="create-user.php">Create</a>
        </li>
      </ul>
    </nav>
      <div class="main-header px-3 sticky-top bg-light" style='top:60px;'>
        <form class="d-flex" id="searchForm">
          <input class="form-control me-1 w-100" type="search" id="searchInput" placeholder="Search by Name" aria-label="Search">
        </form>
        
        

        <div class="form-inline d-flex flex-row gap-1">
          <button type="button" class="btn btn-primary" onclick="$('#addModal').modal('show')">Create</button>
          <input type="number" id="row" style="width:60px; height: 40px;" class="form-control"/>
          <button type="button" class="btn btn-success" id="filter">Filter</button>
        </div>
        
      </div>
      
      <section>
  <div class="tables container-fluid tbl-container d-flex flex-column justify-content-center align-content-center">
    <div class="row tbl-fixed">
      <table class="table-striped table-condensed" style="width:1920px !important;" id="myTable">
          <thead>
        <tr>
        <?php
            include '../connection/connect.php';

            $sql = "SELECT * FROM tblproduct_transaction  INNER JOIN tblproduct_sales_months ON tblproduct_transaction.product_pk = tblproduct_sales_months.product_fk";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    foreach ($row as $column_name => $value) {
                        if ($column_name == 'product_status' || $column_name == 'product_fk') {
                            // Skip rendering product_status and product_fk
                            continue;
                        }
                        if ($column_name == 'January') {
                            echo "<th class='text-center'>Current Stock <br><span id='current_stock_sum' class='text-danger'>()</span></th>";
                            echo "<th class='text-center'>Total <br><span id='total_sum' class='text-danger'>()</span></th>";
                        }
                        // Check if the current column is product_pk or product_name
                        if ($column_name == 'product_pk' || $column_name == 'product_name') {
                            // Remove the parentheses from the span
                            echo "<th id='" . $column_name . "' class='text-center'>" . $column_name . "<br><span id='" . $column_name . "' style='color:#28ACE8;'>()</span></th>";
                        } else {
                            echo "<th id='" . $column_name . "' class='text-center'>" . $column_name . "<br><span id='" . $column_name . "' class='text-danger'>(<span id='" . $column_name . "_sum'></span>)</span></th>";
                        }
                    }
                    break;
                }
            }
        ?>

        </tr>
      </thead>
          <tbody>
            <?php
            include '../connection/connect.php';
            
            $sql = "SELECT * FROM tblproduct_transaction INNER JOIN tblproduct_sales_months ON tblproduct_transaction.product_pk = tblproduct_sales_months.product_fk";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr id='row_" . $row['product_pk'] . "'>"; // Add unique ID for each row
                    foreach ($row as $column_name => $value) {
                        if ($column_name == 'product_status' || $column_name == 'product_fk') {
                            // Skip rendering product_status and product_fk
                            continue;
                        }
                        if ($column_name == 'January') {
                            echo "<td name='current_stock' class='text-center'></td>"; // Corrected closing tag
                            echo "<td name='total' class='text-center'></td>";
                        }
                        echo "<td class='editable' data-column='" . $column_name . "'>" . $value . "</td>";
                    }
                    echo "</tr>";
                }
            }
            ?>
          </tbody>
      </table>
    </div>
  </div>
</section>

      
    <div class="buttons d-flex align-content-end justify-content-end mt-3 px-2">
      <div class="page-of">Page <span id="current-page">1</span> of <span id="total-pages">1</span></div>
      <button id="prev-btn">Prev</button>
      <input type="number" placeholder="1" id="page-number" disabled>
      <button id="next-btn">Next</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" style="--bs-modal-width: 1000px !important;" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header px-5">
                <h2 class="modal-title" id="addModalLabel">Create</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="process_form.php"> <!-- Specify the PHP script to handle form submission -->
                    <div class="tables container-fluid tbl-container d-flex flex-column justify-content-center align-content-center">
                        <div class="row tbl-fixed">
                            <table class="table-striped table-condensed" id="myTable">
                                <thead>
                                    <tr>
                                    <?php
                                      include '../connection/connect.php';

                                      $sql = "SELECT
                                      COLUMN_NAME AS department_name
                                      FROM INFORMATION_SCHEMA.COLUMNS
                                       WHERE TABLE_NAME = 'tblproduct_transaction'
                                       AND ORDINAL_POSITION >= 2;";
                                      $result = $conn->query($sql); // Execute the query

                                      if ($result && $result->num_rows > 0) {
                                          // Fetch column names from the database
                                          $columns = array();
                                          while ($row = $result->fetch_assoc()) {
                                              $columns[] = $row["department_name"];
                                              echo "<th class='text-center'>" . $row["department_name"] . "</th>";
                                          }
                                      } else {
                                          echo "<th>No results found</th>"; // Output if no results found
                                      }
                                    ?>
                                   </tr>
                                </thead>
                                <?php
                                  // Output empty input fields in the tbody
                                  echo "<tbody><tr>";
                                  if ($result && $result->num_rows > 0) {
                                      foreach ($columns as $index => $column) {
                                          // Check if it's the first column
                                          if ($index === 0) {
                                              echo "<td><input type='text' name='" . $column . "' required></td>";
                                          } else {
                                              echo "<td><input type='text' name='" . $column . "'></td>";
                                          }
                                      }

                                      
                                  } else {
                                      // Output a single cell spanning all columns if no results found
                                      echo "<td colspan='" . count($columns) . "'>No data available</td>";
                                  }
                                  echo "</tr></tbody>";
                                ?> 

                            </table>
                        </div>
                    </div>
                    <div class='form-group mb-3 mt-3 d-flex justify-content-end'>
                        <button type='button' class='btn btn-secondary mx-2' data-bs-dismiss='modal'>Close</button>
                        <button type='submit' name='submit_input' class='btn btn-primary'>Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/jquery.tabledit.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
<!-- Make tbody editable -->
<script>
    $(document).ready(function() {
        $("td.editable").on("click", function() {
            // Check if the cell is not in the first column (assuming ID column is the first column)
            if (!$(this).prev().length) {
                return; // Exit the function if the cell is in the first column
            }

            var cell = $(this);
            var oldValue = cell.text().trim();

            // Set the contenteditable attribute to true to make the cell editable
            cell.attr("contenteditable", "true").focus();

            // On blur event, send AJAX request to update the value
            cell.on("blur", function() {
                var newValue = cell.text().trim();
                updateValue(cell, newValue, oldValue);
            });

            // On pressing Enter key, confirm the edited value
            cell.on("keydown", function(event) {
                if (event.keyCode === 13) { // 13 corresponds to the Enter key
                    var newValue = cell.text().trim();
                    updateValue(cell, newValue, oldValue);
                }
            });
        });

        function updateValue(cell, newValue, oldValue) {
            var column = cell.attr("data-column");

            // If column is not product_name, validate if newValue is numeric
            if (column !== "product_name" && isNaN(newValue)) {
                alert("Please enter a valid numeric value.");
                cell.text(oldValue); // Revert the cell text to the original value
                cell.removeAttr("contenteditable").off("blur keydown");
                return;
            }

            if (newValue !== oldValue) {
                var productId = cell.closest("tr").attr("id").split("_")[1]; // Extract product ID

                // Send AJAX request to update the value
                $.ajax({
                    url: "update.php",
                    type: "POST",
                    data: { id: productId, column: column, newValue: newValue },
                    dataType: "json",
                    success: function(response) {
                        console.log("AJAX Success:", response);
                        if (response.success) {
                            cell.text(newValue); // Update the cell text with the new value
                        } else {
                            console.error("Update failed:", response.message);
                            cell.text(oldValue); // Revert the cell text to the original value
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        cell.text(oldValue); // Revert the cell text to the original value
                    },
                    complete: function() {
                        cell.removeAttr("contenteditable").off("blur keydown");
                    }
                });
            } else {
                // If the value hasn't changed, simply display the text
                cell.text(oldValue);
                cell.removeAttr("contenteditable").off("blur keydown");
            }
        }
    });
</script>
<!-- Filter Parts -->
<script>
  $(document).ready(function() {
// Define the filtering logic
$('#filter').on('click', function() {
    const rowLimit = $('#row').val();
    // Check if rowLimit is empty, refresh the page if it is
    if (!rowLimit) {
        location.reload();
        return;
    }
    filterTable('myTable', rowLimit);
});

const filterTable = (tableId, rowLimit) => {
    const $table = $('#' + tableId);
    const $rows = $table.find('tbody tr');

    // Hide all tbody rows initially
    $rows.hide();

    // Show only the specified number of tbody rows starting from index 0
    if (rowLimit === '' || parseInt(rowLimit) === 0) {
        // If rowLimit is empty or equal to 0, reload the page
        location.reload();
    } else {
        $rows.slice(0, parseInt(rowLimit)).show();
    }
};

    // Define the pagination logic
    const rowsPerPage = 10;
    const $table = $("#myTable");
    const $tbodyRows = $table.find("tbody tr");
    let totalPages = Math.ceil($tbodyRows.length / rowsPerPage);
    let currentPage = 1;

    const showPage = (page) => {
        // Hide all tbody rows
        $tbodyRows.hide();

        // Calculate start and end indices for the current page
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        // Show only the tbody rows for the current page
        $tbodyRows.slice(startIndex, endIndex).show();

        // Update displayed page number
        $("#current-page").text(page);
    };

    const goToPage = (page) => {
        if (page >= 1 && page <= totalPages) {
            currentPage = page;
            showPage(currentPage);
            $("#page-number").val(currentPage);
        }
    };

    // Event listeners for pagination controls
    $("#prev-btn").on("click", () => {
        goToPage(currentPage - 1);
    });

    $("#next-btn").on("click", () => {
        goToPage(currentPage + 1);
    });

    $("#page-number").on("change", function() {
        const pageNum = parseInt($(this).val());
        if (!isNaN(pageNum)) {
            goToPage(pageNum);
        }
    });

    // Initial setup
    showPage(currentPage);
    $("#total-pages").text(totalPages);

    // Store the original table rows
    var originalTableRows = $('#table-body').html();

    // Function to handle search
    function handleSearch() {
        var searchText = $('#searchInput').val().trim().toLowerCase(); // Trim whitespace from input
        var rowsToShow = 0;

        // Loop through each table row
        $('#myTable tbody tr').each(function() {
            var rowText = $(this).text().trim().toLowerCase(); // Trim whitespace from row text

            // Check if the row contains the search text
            if (searchText === '' || rowText.indexOf(searchText) !== -1) {
                $(this).show(); // Show row if it matches search text or if search text is empty
                rowsToShow++;
            } else {
                $(this).hide(); // Hide row if it doesn't match search text
            }
        });

        // Show no results message if necessary
        if (rowsToShow === 0 && searchText !== '') {
            var noResultsMessage = '<tr><td colspan="' + $('#myTable th').length + '">No results found</td></tr>';
            $('#table-body').html(noResultsMessage);
        } else {
            $('#table-body').html(''); // Clear the no results message if there are results
        }
    }

    // Bind the keyup event of the search input
    $('#searchInput').keyup(handleSearch);
});

</script>
<!-- Calculation Parts -->
<script>
  $(document).ready(function () {
    // Function to calculate and update the current stock
    function updatecurrent_stock() {
      $('#myTable tbody tr').each(function () {
        var current_stock = 0;
        var columnCount = $(this).find('td[name="current_stock"]').index();

        $(this).find('td.editable').each(function (index) {
          if (index !== 0 && index !== 1 && index < columnCount) {
            var value = parseInt($(this).text()) || 0;
            current_stock += value;
            console.log(value);
          }
        });

        $(this).find('td[name="current_stock"]').text(current_stock);
      });
    }

    // Event listener for input changes in the editable cells
    $('#myTable tbody td.editable').on('input', function () {
      updatecurrent_stock(); // Recalculate the current stock when a value changes
      updateTotal(); // Recalculate the total when a value changes
    });

    function updateTotal() {
  $('#myTable tbody tr').each(function () {
    var total = 0;
    $(this).find('td.editable').each(function (index) {
      if (index > 1) {

        var value = parseInt($(this).text()) || 0;
        total += value;
      }
    });

    $(this).find('td[name="total"]').text(total);
  });
}


    // Call updatecurrent_stock() on document ready to ensure it runs when the page loads
    updatecurrent_stock();
    updateTotal();
  });
</script>
<!-- Sum Column -->
<script>
$(document).ready(function() {
    // Function to calculate and update sums
    function updateSums() {
        $('#myTable').find('td.editable').slice(2).each(function() { // Start from the third column
            var columnIndex = $(this).index(); // Get the index of the column
            var total = 0;

            if (columnIndex > 1) { // Exclude the first two columns
                $('#myTable').find('tbody tr').each(function() {
                    var cellValue = parseInt($(this).find('td').eq(columnIndex).text()) || 0;
                    total += cellValue;
                });

                var columnName = $(this).attr('data-column');
                $('span#' + columnName + '_sum').text(total); // Set the sum in the corresponding inner <span> element
            }
        });
    }

    // Initial calculation on page load
    updateSums();

    // Listen for changes in the table and update sums accordingly
    $('#myTable').on('input', 'td.editable', function() {
        updateSums();
    });
});
</script>
<!-- Sum Column of Current_stock and Total-->
<script>
    function updateSums() {
        var rows = document.querySelectorAll("tr[id^='row_']");
        var currentStockSum = 0;
        var totalSum = 0;

        rows.forEach(function(row) {
            var currentStockText = row.querySelector("td[name='current_stock']").innerText;
            var totalText = row.querySelector("td[name='total']").innerText;
            
            // Extract numeric values using regular expressions
            var numericRegex = /[-+]?[0-9]+/g; // Change regex to match integers
            var currentStockMatches = currentStockText.match(numericRegex);
            var totalMatches = totalText.match(numericRegex);

            // Check if matches were found
            if (currentStockMatches && totalMatches) {
                // Use the first match as the numeric value
                var currentStockValue = parseInt(currentStockMatches[0]);
                var totalValue = parseInt(totalMatches[0]);

                // Update the sums
                currentStockSum += currentStockValue;
                totalSum += totalValue;
            }
        });

        // Update the sums in the respective span elements with parentheses
        document.getElementById("current_stock_sum").innerText = "(" + currentStockSum + ")";
        document.getElementById("total_sum").innerText = "(" + totalSum + ")";
    }

    window.onload = function() {
        updateSums(); // Initial update
        
        // Polling to update sums periodically
        setInterval(updateSums, 2000); // Update every 2 seconds (adjust as needed)
    };
</script>

</html>