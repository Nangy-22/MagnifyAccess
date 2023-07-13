<?php
// Establish database connection
$host = "localhost";
$username = "admin";
$password = "password";
$database = "magnifyaccess";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data and sanitize inputs
  $empFName = mysqli_real_escape_string($conn, $_POST["empFName"]);
  $empLName = mysqli_real_escape_string($conn, $_POST["empLName"]);
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $emplNumber = mysqli_real_escape_string($conn, $_POST["emplNumber"]);
  $department = mysqli_real_escape_string($conn, $_POST["department"]);
  $employmentStatus = mysqli_real_escape_string($conn, $_POST["employmentStatus"]);

  // Handle file upload
  $targetDirectory = "C:/xampp/htdocs/MagnifyAccess/uploads";
  $uploadOk = 1;
  $fileType = strtolower(pathinfo($_FILES["accommodationFile"]["name"], PATHINFO_EXTENSION));

  // Check if the file is a valid document type
  $allowedExtensions = array("pdf", "doc", "docx", "pages");
  if (!in_array($fileType, $allowedExtensions)) {
    echo "Invalid file type. Only PDF, Word, and Pages documents are allowed.";
    $uploadOk = 0;
  }

  $targetFile = $targetDirectory . '/' . uniqid() . '.' . $fileType;

  // Check if the file already exists
  if (file_exists($targetFile)) {
    echo "File already exists.";
    $uploadOk = 0;
  }

  // Check file size (max 2MB)
  $maxFileSize = 2 * 1024 * 1024;
  if ($_FILES["accommodationFile"]["size"] > $maxFileSize) {
    echo "File size exceeds the limit (2MB).";
    $uploadOk = 0;
  }

  // If the file passes all checks, move it to the target directory
  if ($uploadOk) {
    if (move_uploaded_file($_FILES["accommodationFile"]["tmp_name"], $targetFile)) {
      echo "File uploaded successfully.";
    } else {
      echo "Error uploading file.";
    }
  }

  // Prepare and execute the SQL query to insert data into the database
  $stmt = $conn->prepare("INSERT INTO employee_data (empFName, empLName, email, emplNumber, department, employmentStatus) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->bind_param("ssssss", $empFName, $empLName, $email, $emplNumber, $department, $employmentStatus);
  $stmt->execute();

  // Check if the data is inserted successfully
  if ($stmt->affected_rows > 0) {
    echo "Data inserted successfully!";
  } else {
    echo "Error inserting data: " . htmlspecialchars($stmt->error);
  }

  // Close the prepared statement
  $stmt->close();
}

// Close the database connection
$conn->close();
