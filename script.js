var columnSortOrder = {};

function sortTable(columnIndex) {
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("productTable");
  switching = true;

  var currentSortOrder = columnSortOrder[columnIndex] || "no-sort";
  var nextSortOrder = getNextSortOrder(currentSortOrder);
  var sortIndicator = table.querySelector(
    `th[data-column-index="${columnIndex}"] .sort-indicator`
  );

  while (switching) {
    switching = false;
    rows = table.rows;

    for (i = 1; i < rows.length - 1; i++) {
      shouldSwitch = false;
      x = rows[i].getElementsByTagName("td")[columnIndex];
      y = rows[i + 1].getElementsByTagName("td")[columnIndex];

      var xValue = x.innerHTML.toLowerCase();
      var yValue = y.innerHTML.toLowerCase();

      if (columnIndex === 3) {
        // "Aantal in voorraad" column
        xValue = parseInt(xValue);
        yValue = parseInt(yValue);
      }

      if (nextSortOrder === "desc") {
        if (xValue < yValue) {
          shouldSwitch = true;
          break;
        }
      } else if (nextSortOrder === "asc") {
        if (xValue > yValue) {
          shouldSwitch = true;
          break;
        }
      }
    }

    if (shouldSwitch) {
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
    }
  }

  // Update column sort order
  columnSortOrder[columnIndex] = nextSortOrder;

  // Reset sort indicators for other columns
  var sortIndicators = table.getElementsByClassName("sort-indicator");
  for (var j = 0; j < sortIndicators.length; j++) {
    var indicatorColumnIndex =
      sortIndicators[j].parentNode.getAttribute("data-column-index");
    if (indicatorColumnIndex !== columnIndex.toString()) {
      sortIndicators[j].innerHTML = "";
      delete columnSortOrder[indicatorColumnIndex];
    }
  }

  // Update sort indicator for the current column
  if (nextSortOrder === "no-sort") {
    sortIndicator.innerHTML = ""; // Remove sort indicator
  } else {
    sortIndicator.innerHTML = nextSortOrder === "desc" ? "&darr;" : "&uarr;"; // Downward or upward arrow
  }
}
function getNextSortOrder(currentSortOrder) {
  if (currentSortOrder === "no-sort") {
    return "desc";
  } else if (currentSortOrder === "desc") {
    return "asc";
  } else {
    return "no-sort";
  }
}

function searchProduct() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("productTable");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0]; // Index 0 for barcode column
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchGebruiker() {
  var input, filter, table, tr, td, i;
  input = document.getElementById("searchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("gebruikerTable");
  tr = table.getElementsByTagName("tr");

  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3]; // Index 3 for barcode column
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
