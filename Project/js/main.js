
// // Function to calculate and update the current stock
// function updatecurrent_stock() {
//   $('#myTable tbody tr').each(function() {
//     var current_stock = 0;
//     var currentIndex = $(this).find('td.editable').index($(this).find('td.editable:focus')); // Get the index of the currently focused editable cell
//     var threshold = $(this).find('td.editable').length; // Get the number of editable cells in the row
    
//     // Determine the number of columns before the "current_stock" column dynamically
//     var columnCount = $(this).find('td[name="current_stock"]').index();
    
//     // Loop through each editable cell before the current one
//     $(this).find('td.editable').each(function(index) {
//       if (index !== 0 && index !== 1 && index < columnCount) { // Skip product_fk (index 0) and only consider columns before the "current_stock" column
//         var value = parseInt($(this).text()) || 0;
//         current_stock += value;
//       }
//     });
//     // Update the corresponding "current_stock" cell in the row
//     $(this).find('td[name="current_stock"]').text(current_stock);
//   });
// }

// // Event listener for input changes in the editable cells
// $('#myTable tbody td.editable').on('input', function() {
//   updatecurrent_stock(); // Recalculate the current stock when a value changes
// });

// // Call updatecurrent_stock() on document ready to ensure it runs when the page loads
// $(document).ready(function() {
//   updatecurrent_stock();
// });


//   // Function to calculate and update the total
//   function updateTotal() {
//     var totalColumnIndex = $('th:contains("Total")').index() + 1; // Get the index of the "Total" column
//     $('#myTable tbody tr').each(function() {
//       var total = 0;
//       $(this).find('td.editable').each(function(index) {
//         // Skip the first two non-editable columns (current_stock and total)
//         if (index > 1) {
//           var value = parseInt($(this).text()) || 0; // Get the cell value, default to 0 if not a number
//           total += value;
//         }
//       });
//       $(this).find('td[name="total"]').text(total); // Update the total cell with the calculated total
//     });
//   }

// // Event listener for input changes in the editable cells
// $('#myTable tbody td.editable').on('input', function() {
//   updateTotal(); // Recalculate the total when a value changes
// });

// // Function to handle subtraction or addition
// function handleOperation(cell) {
//   var value = -parseInt(cell.text()) || 0; // Get the entered value
//   var totalCell = cell.closest('tr').find('td[name="total"]');
//   var total = parseInt(totalCell.text()) || 0; // Get the current total
//   var newValue = total; // Convert the absolute value to negative
//   totalCell.text(total - value); // Add the negative value to the total
// }

//   // Initial calculation of the total
//   $(document).ready(function() {
//     updateTotal();
//   });


//   $(document).ready(function() {
// // Define the filtering logic
// $('#filter').on('click', function() {
//     const rowLimit = $('#row').val();
//     // Check if rowLimit is empty, refresh the page if it is
//     if (!rowLimit) {
//         location.reload();
//         return;
//     }
//     filterTable('myTable', rowLimit);
// });

// const filterTable = (tableId, rowLimit) => {
//     const $table = $('#' + tableId);
//     const $rows = $table.find('tbody tr');

//     // Hide all tbody rows initially
//     $rows.hide();

//     // Show only the specified number of tbody rows starting from index 0
//     if (rowLimit === '' || parseInt(rowLimit) === 0) {
//         // If rowLimit is empty or equal to 0, reload the page
//         location.reload();
//     } else {
//         $rows.slice(0, parseInt(rowLimit)).show();
//     }
// };

//     // Define the pagination logic
//     const rowsPerPage = 10;
//     const $table = $("#myTable");
//     const $tbodyRows = $table.find("tbody tr");
//     let totalPages = Math.ceil($tbodyRows.length / rowsPerPage);
//     let currentPage = 1;

//     const showPage = (page) => {
//         // Hide all tbody rows
//         $tbodyRows.hide();

//         // Calculate start and end indices for the current page
//         const startIndex = (page - 1) * rowsPerPage;
//         const endIndex = startIndex + rowsPerPage;

//         // Show only the tbody rows for the current page
//         $tbodyRows.slice(startIndex, endIndex).show();

//         // Update displayed page number
//         $("#current-page").text(page);
//     };

//     const goToPage = (page) => {
//         if (page >= 1 && page <= totalPages) {
//             currentPage = page;
//             showPage(currentPage);
//             $("#page-number").val(currentPage);
//         }
//     };

//     // Event listeners for pagination controls
//     $("#prev-btn").on("click", () => {
//         goToPage(currentPage - 1);
//     });

//     $("#next-btn").on("click", () => {
//         goToPage(currentPage + 1);
//     });

//     $("#page-number").on("change", function() {
//         const pageNum = parseInt($(this).val());
//         if (!isNaN(pageNum)) {
//             goToPage(pageNum);
//         }
//     });

//     // Initial setup
//     showPage(currentPage);
//     $("#total-pages").text(totalPages);

//     // Store the original table rows
//     var originalTableRows = $('#table-body').html();

//     // Function to handle search
//     function handleSearch() {
//         var searchText = $('#searchInput').val().toLowerCase();
//         var rowsToShow = 0;

//         // Loop through each table row
//         $('#myTable tbody tr').each(function() {
//             var rowText = $(this).text().toLowerCase();

//             // Check if the row contains the search text
//             if (searchText === '' || rowText.indexOf(searchText) !== -1) {
//                 $(this).show(); // Show row if it matches search text or if search text is empty
//                 rowsToShow++;
//             } else {
//                 $(this).hide(); // Hide row if it doesn't match search text
//             }
//         });

//         // Show no results message if necessary
//         if (rowsToShow === 0 && searchText !== '') {
//             var noResultsMessage = '<tr><td colspan="' + $('#myTable th').length + '">No results found</td></tr>';
//             $('#table-body').html(noResultsMessage);
//         }
        
//         // Reload the page when search input is cleared
//         if (searchText === '') {
//             location.reload();
//         }
//     }

//     // Bind the keyup event of the search input
//     $('#searchInput').keyup(handleSearch);

//     $("td.editable").on("click", function() {
//         // Check if the cell is not in the first column (assuming ID column is the first column)
//         if (!$(this).prev().length) {
//             return; // Exit the function if the cell is in the first column
//         }
    
//         var oldValue = $(this).text().trim();
    
//         // Set the contenteditable attribute to true to make the cell editable
//         $(this).attr("contenteditable", "true").focus();
    
//         // On blur event, send AJAX request to update the value
//         $(this).on("blur", function() {
//             var newValue = $(this).text().trim();
//             if (newValue !== oldValue) {
//                 var productId = $(this).closest("tr").attr("id");
//                 var column = $(this).attr("data-column");
    
//                 // Send AJAX request to update the value
//                 $.ajax({
//                     url: "update.php",
//                     type: "POST",
//                     data: { id: productId, column: column, newValue: newValue },
//                     dataType: "json",
//                     success: function(response) {
//                         console.log("AJAX Success:", response);
//                         if (response.success) {
//                             $(this).text(newValue); // Update the cell text with the new value
//                         } else {
//                             console.error("Update failed:", response.message);
//                             $(this).text(oldValue); // Revert the cell text to the original value
//                         }
//                     }.bind(this), // Ensure correct context inside the success callback
//                     error: function(xhr, status, error) {
//                         console.error("AJAX Error:", error);
//                         $(this).text(oldValue); // Revert the cell text to the original value
//                     }.bind(this) // Ensure correct context inside the error callback
//                 });
//             } else {
//                 // If the value hasn't changed, simply display the text
//                 $(this).text(oldValue);
//             }
    
//             // Remove the blur event handler after editing
//             $(this).removeAttr("contenteditable").off("blur");
//         });
//     });
// });

