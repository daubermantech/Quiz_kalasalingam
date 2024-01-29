<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['file_path'])) {
        $filePath = $_POST['file_path'];

        // Function to convert docx to HTML using mammoth.js
        function convertDocxToHtml($filePath)
        {
            // Convert docx to HTML using mammoth.js
            $result = shell_exec("mammoth " . escapeshellarg($filePath) . " --output-format=html");
            return $result;
        }

        // Function to add radio buttons to options
        function addRadioButtons($htmlContent)
        {
            // Add radio buttons to options
            $htmlContent = preg_replace_callback('/<p>\s*[A-D]\.\s*(.*?)<\/p>/', function ($match) {
                $options = explode("\n", $match[1]);
                array_shift($options); // Remove the empty first element

                $radioButtons = '';
                foreach ($options as $option) {
                    $radioButtons .= '<input type="radio" name="question_' . uniqid() . '" value="' . htmlspecialchars(trim($option)) . '">' . htmlspecialchars(trim($option)) . '<br>';
                }

                return '<p>' . $radioButtons . '</p>';
            }, $htmlContent);

            return $htmlContent;
        }

        // Load and echo the HTML content with added radio buttons
        $htmlContent = convertDocxToHtml($filePath);
        $htmlContentWithRadioButtons = addRadioButtons($htmlContent);
        echo $htmlContentWithRadioButtons;
        exit();
    }
}
