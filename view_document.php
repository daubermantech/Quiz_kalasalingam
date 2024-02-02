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
    <h1>Preview</h1>

    <?php
    // Check if the file_path parameter is set in the URL
    if (isset($_GET['file_path'])) {
        // Get the file path from the URL parameter
        $file_path = $_GET['file_path'];

        // Read the contents of the CSV file
        $csv_content = file_get_contents($file_path);

        // Parse the CSV data
        $csv_rows = array_map('str_getcsv', explode("\n", $csv_content));

        // Extract headers
        $headers = array_shift($csv_rows);

        // Display the data
        foreach ($csv_rows as $rowIndex => $row) {
            // Skip rows without enough columns (e.g., the extra row)
            if (count($row) < count($headers)) {
                continue;
            }

            echo '<h2>Question ' . ($rowIndex + 1) . '</h2>';
            foreach ($row as $cellIndex => $cell) {
                $header = $headers[$cellIndex];
                switch ($header) {
                    case 'Question':
                        echo '<p>' . htmlspecialchars($cell) . '</p>';
                        break;
                    case 'type':
                        if (strtoupper($cell) === 'MULTIPLE') {
                            echo '<div class="options">';
                            // Display options as checkboxes for multiple-choice questions
                            $options = explode(",", $row[$cellIndex + 1]);
                            foreach ($options as $optionIndex => $option) {
                                echo '<label><input type="checkbox" name="question_' . ($rowIndex + 1) . '[]" value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</label><br>';
                            }
                            echo '</div>';
                        } elseif (strtoupper($cell) === 'SINGLE') {
                            echo '<div class="options">';
                            // Display options as radio buttons for single-choice questions
                            $options = explode(",", $row[$cellIndex + 1]);
                            foreach ($options as $optionIndex => $option) {
                                echo '<label><input type="radio" name="question_' . ($rowIndex + 1) . '" value="' . htmlspecialchars($option) . '">' . htmlspecialchars($option) . '</label><br>';
                            }
                            echo '</div>';
                        } elseif (strtoupper($cell) === 'TRUEFALSE') {
                            // Display radio buttons for True/False questions
                            echo '<label><input type="radio" name="question_' . ($rowIndex + 1) . '" value="True">True</label>';
                            echo '<label><input type="radio" name="question_' . ($rowIndex + 1) . '" value="False">False</label><br>';
                        } else {
                            // Handle other types here
                            echo '<p>' . htmlspecialchars($cell) . '</p>';
                        }
                        break;
                }
            }
        }
    } else {
        echo '<p>No file path provided.</p>';
    }
    ?>
</body>

</html>