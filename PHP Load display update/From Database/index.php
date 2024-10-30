<?php
// Database connection details
$host = 'localhost';
$db = 'library';
$user = 'root';
$password = '';

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $password, $db);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare the SQL statement to insert a new book
    $stmt = $conn->prepare("INSERT INTO books (title, author, available, pages, isbn) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiii", $_POST['title'], $_POST['author'], $available, $_POST['pages'], $_POST['isbn']);

    // Checkbox for 'available' might not be set if unchecked
    $available = isset($_POST['available']) ? 1 : 0;

    // Execute the statement and check if successful
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all books from the database
$result = $conn->query("SELECT * FROM books");

// Display the table
echo "<h2>Book List</h2>";
echo "<table border='1'>
<tr>
    <th>Title</th>
    <th>Author</th>
    <th>Available</th>
    <th>Pages</th>
    <th>ISBN</th>
</tr>";

// Loop through each book and display it in the table
while ($book = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$book['title']}</td>
        <td>{$book['author']}</td>
        <td>" . ($book['available'] ? 'Yes' : 'No') . "</td>
        <td>{$book['pages']}</td>
        <td>{$book['isbn']}</td>
    </tr>";
}

echo "</table>";

// Close the database connection
$conn->close();
?>

<!-- Form to add new book -->
<h2>Add New Book</h2>
<form method="POST">
    <label>Title: <input type="text" name="title" required></label><br><br>
    <label>Author: <input type="text" name="author" required></label><br><br>
    <label>Available: <input type="checkbox" name="available" value="true"></label><br><br>
    <label>Pages: <input type="number" name="pages" required></label><br><br>
    <label>ISBN: <input type="number" name="isbn" required></label><br><br>
    <button type="submit">Add Book</button>
</form>
