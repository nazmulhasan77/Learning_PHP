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

// Initialize search and sort variables
$searchTerm = '';
$orderBy = 'title'; // Default sort order
$orderDirection = 'ASC'; // Default sort direction

// Check if search is set
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Check if order is set
if (isset($_GET['order_by'])) {
    $orderBy = $_GET['order_by'];
}
if (isset($_GET['order_direction'])) {
    $orderDirection = $_GET['order_direction'];
}

// Prepare the SQL query with search and sorting
$sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY $orderBy $orderDirection";
$stmt = $conn->prepare($sql);
$searchWildcard = "%$searchTerm%";
$stmt->bind_param("ss", $searchWildcard, $searchWildcard);
$stmt->execute();
$result = $stmt->get_result();

// Display the table
echo "<h2>Book List</h2>";

// Search form
echo '<form method="GET">
    <input type="text" name="search" placeholder="Search by title or author" value="' . htmlspecialchars($searchTerm) . '">
    <button type="submit">Search</button>
</form>';

// Sort options
echo '<form method="GET" style="display:inline;">
    <select name="order_by">
        <option value="title"' . ($orderBy === 'title' ? ' selected' : '') . '>Title</option>
        <option value="author"' . ($orderBy === 'author' ? ' selected' : '') . '>Author</option>
        <option value="pages"' . ($orderBy === 'pages' ? ' selected' : '') . '>Pages</option>
    </select>
    <select name="order_direction">
        <option value="ASC"' . ($orderDirection === 'ASC' ? ' selected' : '') . '>Ascending</option>
        <option value="DESC"' . ($orderDirection === 'DESC' ? ' selected' : '') . '>Descending</option>
    </select>
    <button type="submit">Sort</button>
</form>';

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

// Close the statement and database connection
$stmt->close();
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
