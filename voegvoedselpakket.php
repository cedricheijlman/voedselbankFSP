<?php
session_start();
$_SESSION['productToevoegenError'] =  "";

require_once 'credentials.php';

$conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check voor info product
$stmtCheck = $conn->prepare('SELECT * FROM klant');
$stmtCheck->execute();
$klanten = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

// Toegang tot de gegevens
if ($klanten) {
} else {
  // Behandel het geval wanneer er geen product wordt gevonden met de gegeven streepjescode
}

$stmtAlleProducten = $conn->prepare('select * from product');
$stmtAlleProducten->execute();
$alleProducten = $stmtAlleProducten->fetchAll(PDO::FETCH_ASSOC);






// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {




  try {
  } catch (PDOException $e) {
    $_SESSION['registratieErrror'] = 'Registratie mislukt';
  }

  // Databaseverbinding sluiten
  $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/voegproduct.css" />
  <link rel="stylesheet" href="./styles/voegvoedselpakket.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />
  <link rel="stylesheet" href="./styles/layout.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div id="overlay" class="overlay">
      <div class="overlayForm" method="POST">
        <h2>Voeg Product(en) aan voedselpakket</h2>

        <div class="productVoegLijst">
          <div class="dropdown">
            <button data-custom-data="" id="productButton" onclick="myFunctionProduct()" type="button" class="dropbtn">Nog geen product geselecteerd</button>
            <div id="myDropdownProduct" class="dropdown-content">
              <input type="text" placeholder="Search.." id="myInputProduct" onkeyup="filterFunctionProduct()">
              <div class="dropdownItems">
                <?php if (!empty($alleProducten)) : ?>
                  <?php foreach ($alleProducten as $product) : ?>
                    <a onclick="selectProduct('<?php echo $product['naam'] ?>', '<?php echo $product['streepjescode'] ?>', '<?php echo $product['aantal'] ?>')"><?php echo $product['naam']  ?></a>
                  <?php endforeach; ?>
                <?php else : ?>
                  <a>Geen producten...</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>


        <div>
          <p>Aantal</p> <input name="voegProductAantal" id="voegProductAantal" required type="number" min="1" max="1" />
        </div>
        <div class="overlayButtonsContainer">
          <a onclick="hideModal()">Cancel</a>
          <button onclick="voegItemToVoedselpakket()">Voeg</button>
        </div>
      </div>
    </div>
    <div class="navbar">
      <h2>Maaskantje Paneel</h2>
      <div class="navbarListContainer">
        <ul>
          <li onclick="location.href = 'homepage.php'">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
          </li>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2 || $_SESSION['soortgebruiker'] == 3) : ?>
            <li onclick="location.href = 'productvoorraad.php'" class="selected">
              <i class="fa-solid fa-shop"></i>
              <p>Productvoorraad</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 3) : ?>
            <li onclick="location.href = 'voedselpakketten.php'">
              <i class="fa-solid fa-bag-shopping"></i>
              <p>Voedselpakketten</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2) : ?>
            <li onclick="location.href = 'leveranciers.php'">
              <i class="fa-solid fa-truck-field"></i>
              <p>Leveranciers</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1) : ?>
            <li>
              <i class="fa-solid fa-users"></i>
              <p>Klanten</p>
            </li>
            <li>
              <i class="fa-solid fa-chart-simple"></i>
              <p>Maand Overzicht</p>
            </li>
            <li onclick="location.href = 'gebruikers.php'">
              <i class="fa-solid fa-user-friends"></i>
              <p>Gebruikers</p>
            </li>
          <?php endif; ?>
          <li onclick="location.href = 'account.php'">
            <i class="fa-regular fa-circle-user"></i>
            <p>Account</p>
          </li>
        </ul>
      </div>
      <div class="uitlogContainer">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <p>Uitloggen</p>
      </div>
    </div>
    <div class="rechts">
      <h2>Voeg Voedselpakket Toe</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label>Klant</label>
        <input type="hidden" name="klantIdPOST" id="klantId">

        <div class="dropdown">
          <button onclick="myFunction()" type="button" class="dropbtn">Nog geen klant geselecteerd</button>
          <div id="myDropdown" class="dropdown-content">
            <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
            <div class="dropdownItems">
              <?php if (!empty($klanten)) : ?>
                <?php foreach ($klanten as $klant) : ?>
                  <a onclick="selectCustomer('<?php echo $klant['naam'] . ' ' . $klant['achternaam']; ?>', <?php echo $klant['id_klant'] ?>)"><?php echo $klant['naam'] . " " . $klant['achternaam']  ?></a>
                <?php endforeach; ?>
              <?php else : ?>
                <a>Geen klanten aangemeld</a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="producten">
          <p>Producten</p>
          <span onclick="showModal()">Voeg Product</span>
        </div>
        <div class="productLijst">

        </div>


        <div class="formButtons">
          <button id="cancelKnop">
            <a href="productvoorraad.php">Cancel</a>
          </button>
          <button id="voegKnop" type="submit">Voeg Voedselpakket Toe</button>
        </div>
      </form>
    </div>
  </div>
  <script>
    let voedselpakketProducten = [];

    function myFunction() {

      let element = document.getElementById('overlay');
      if (window.getComputedStyle(element).display === "none") {
        document.getElementById("myDropdown").classList.toggle("show");
      }
    }

    function myFunctionProduct() {
      document.getElementById("myDropdownProduct").classList.toggle("show");
    }

    function deleteProduct(index) {
      // Remove the product from the array
      voedselpakketProducten.splice(index, 1);

      // Remove the product from the display
      var container = document.querySelector(".productLijst");
      var products = document.querySelectorAll(".product");

      if (index >= 0 && index < products.length) {
        var productDiv = products[index];
        productDiv.remove();

        // Update the indexes of the remaining products
        for (var i = index; i < products.length; i++) {
          products[i].dataset.index = i;
        }
      }
    }

    function voegItemToVoedselpakket() {
      let streepjescode = document.getElementById("productButton").getAttribute('data-custom-data');
      let naam = document.getElementsByClassName('dropbtn')[0].textContent;
      let aantal = document.getElementById('voegProductAantal').value;

      voedselpakketProducten.push([streepjescode, naam, aantal]);

      hideModal()

      // Clear the productLijst container
      var container = document.querySelector(".productLijst");
      container.innerHTML = "";

      // Loop through the voedselpakketProducten array
      for (var i = 0; i < voedselpakketProducten.length; i++) {
        var product = voedselpakketProducten[i];

        // Create the product div
        var productDiv = document.createElement("div");
        productDiv.classList.add("product");

        // Create the product name element
        var nameP = document.createElement("p");
        nameP.textContent = product[1]; // Access the name at index 1
        productDiv.appendChild(nameP);

        // Create the quantity div
        var quantityDiv = document.createElement("div");

        // Create the quantity label
        var quantityLabelP = document.createElement("p");
        quantityLabelP.textContent = "Aantal:";
        quantityDiv.appendChild(quantityLabelP);

        // Create the quantity input
        var quantityInput = document.createElement("input");
        quantityInput.type = "number";
        quantityInput.value = product[2]; // Access the quantity at index 2

        // Add an onchange event listener to the quantity input
        quantityInput.onchange = function(event) {
          // Get the new quantity value
          var newQuantity = event.target.value;

          // Update the quantity in the voedselpakketProducten array
          voedselpakketProducten[index][2] = newQuantity;
        };

        quantityDiv.appendChild(quantityInput);

        // Append the quantity div to the product div
        productDiv.appendChild(quantityDiv);

        // Create the delete paragraph
        var deleteP = document.createElement("p");
        deleteP.textContent = "Delete";
        deleteP.onclick = function() {
          // Handle delete action
          // ...
          // Get the index of the product to be deleted
          var index = Array.from(container.children).indexOf(productDiv);

          // Call the deleteProduct function to delete the product
          deleteProduct(index);
        };
        productDiv.appendChild(deleteP);

        // Append the product div to the container
        container.appendChild(productDiv);
      }
      document.getElementsByClassName('dropbtn')[0].textContent = "Nog geen product geselecteerd"
      document.getElementById('voegProductAantal').value = ""
    }




    function selectCustomer(customerName, klantId) {
      document.getElementById("myDropdown").classList.remove("show");
      document.getElementsByClassName("dropbtn")[1].textContent = customerName;
      document.getElementById("klantId").value = klantId;
    }

    function selectProduct(productNaam, streepjescode, aantal) {
      document.getElementById("myDropdownProduct").classList.remove("show");
      document.getElementsByClassName("dropbtn")[0].textContent = productNaam;
      document.getElementById("voegProductAantal").max = aantal;

      document.getElementById('productButton').setAttribute('data-custom-data', streepjescode);

    }


    function filterFunction() {
      var input, filter, ul, li, a, i;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      div = document.getElementById("myDropdown");
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

    function filterFunctionProduct() {
      var input, filter, ul, li, a, i;
      input = document.getElementById("myInputProduct");
      filter = input.value.toUpperCase();
      div = document.getElementById("myDropdownProduct");
      a = div.getElementsByTagName("a");
      for (i = 0; i < a.length; i++) {
        txtValue = a[i].textContent || a[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          a[i].style.display = "";
        } else {
          a[i].style.display = "none";
        }
      }
    }

    function showModal() {
      document.getElementById("overlay").style.display = "flex";
    }

    function hideModal() {
      document.getElementById("overlay").style.display = "none";
    }
    window.onclick = function(event) {
      let modal = document.getElementById("overlay");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };
  </script>
</body>

</html>