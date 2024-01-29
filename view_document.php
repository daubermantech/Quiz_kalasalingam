<!-- view_document.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>CSV File Content</h1>

    <?php
    // Check if the file_path parameter is set in the URL
    if (isset($_GET['file_path'])) {
        // Get the file path from the URL parameter
        $file_path = $_GET['file_path'];

        // Read the contents of the CSV file
        $csv_content = file_get_contents($file_path);

        // Parse the CSV data
        $csv_rows = array_map('str_getcsv', explode("\n", $csv_content));

        // Display the data in a table
        echo '<table>';
        foreach ($csv_rows as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . htmlspecialchars($cell) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No file path provided.</p>';
    }
    ?>
</body>

</html>