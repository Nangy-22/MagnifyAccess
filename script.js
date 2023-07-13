// Fetch data and populate the table
function fetchData() {
  fetch("server_script.php")
    .then((response) => response.json())
    .then((data) => {
      array = data; // Update the array variable with fetched data
      showTable(array); // Populate the table with the updated data
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
      document.getElementById("error").innerHTML =
        "<span class='text-danger'>Error fetching data</span>";
    });
}

// Show Table
function showTable(curArray) {
  var table = document.getElementById("mytable");
  table.innerHTML = `
    <tr class="bg-success text-white fw-bold">
      <td>ID</td>
      <td>First Name</td>
      <td>Last Name</td>
      <td>Email</td>
      <td>Department</td>
      <td>Status</td>
    </tr>
  `;

  // Check array for empty
  if (curArray.length === 0) {
    document.getElementById("error").innerHTML =
      "<span class='text-danger'>Employee Data Not Found</span>";
  } else {
    document.getElementById("error").innerHTML = "";
    for (var i = 0; i < curArray.length; i++) {
      table.innerHTML += `
        <tr>
          <td>${curArray[i].id}</td>
          <td>${curArray[i].empFName}</td>
          <td>${curArray[i].empLName}</td>
          <td>${curArray[i].email}</td>
          <td>${curArray[i].department}</td>
          <td>${curArray[i].employmentStatus}</td>
        </tr>
      `;
    }
  }
}

// Client-side form validation and sanitization
function validateAndSubmitForm() {
  var empFNameInput = document.getElementById("empFName");
  var empLNameInput = document.getElementById("empLName");
  var emailInput = document.getElementById("email");

  var empFName = sanitizeInput(empFNameInput.value);
  var empLName = sanitizeInput(empLNameInput.value);
  var email = sanitizeInput(emailInput.value);

  var isValid = true;
  var errorMessage = "";

  if (empFName.trim() === "") {
    isValid = false;
    errorMessage += "First Name is required. ";
  }

  if (empLName.trim() === "") {
    isValid = false;
    errorMessage += "Last Name is required. ";
  }

  // Validate the email format
  var emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
  if (!emailRegex.test(email.trim())) {
    isValid = false;
    errorMessage += "Invalid Email format. ";
  }

  // If the form is valid, submit it
  if (isValid) {
    // Construct the form data
    var formData = new FormData();
    formData.append("empFName", empFName);
    formData.append("empLName", empLName);
    formData.append("email", email);

    // Send the form data to the server
    submitForm(formData);
  } else {
    // Display the error message
    document.getElementById("error").innerHTML =
      "<span class='text-danger'>" + errorMessage + "</span>";
  }
}

// Filter Table
function filterTable() {
  var searchInput = document.getElementById("search");
  var filter = searchInput.value.toLowerCase();
  var filteredArray = array.filter(function (item) {
    return (
      item.empFName.toLowerCase().includes(filter) ||
      item.empLName.toLowerCase().includes(filter) ||
      item.email.toLowerCase().includes(filter) ||
      item.department.toLowerCase().includes(filter) ||
      item.employmentStatus.toLowerCase().includes(filter)
    );
  });
  showTable(filteredArray);
}

// Reset Table
function resetTable() {
  var searchInput = document.getElementById("search");
  searchInput.value = "";
  showTable(array);
}

// Call the fetchData function to retrieve and populate data initially
fetchData();
