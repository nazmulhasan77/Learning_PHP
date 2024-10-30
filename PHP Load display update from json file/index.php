<?php
// Load data from the JSON file
$jsonData = file_get_contents('books.json');
$booksArray = json_decode($jsonData, true);

// Check if form data is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newBook = [
        "title" => $_POST['title'],
        "author" => $_POST['author'],
        "available" => isset($_POST['available']),
        "pages" => (int)$_POST['pages'],
        "isbn" => (int)$_POST['isbn']
    ];

    // Add the new book to the array
    $booksArray[] = $newBook;

    // Save the updated array back to the JSON file
    file_put_contents('books.json', json_encode($booksArray, JSON_PRETTY_PRINT));
}

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

foreach ($booksArray as $book) {
    echo "<tr>
        <td>{$book['title']}</td>
        <td>{$book['author']}</td>
        <td>" . ($book['available'] ? 'Yes' : 'No') . "</td>
        <td>{$book['pages']}</td>
        <td>{$book['isbn']}</td>
    </tr>";
}

echo "</table>";
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
