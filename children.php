<?php
require "functions.php";
require "Database.php";

$config = require("config.php");

$db = new Database($config["database"]);

// Fetch all children and letters from the database
$children = $db->query("SELECT * FROM children")->fetchAll();
$letters = $db->query("SELECT * FROM letters")->fetchAll();

// Start outputting HTML and CSS directly from PHP
echo '<style>
    /* General card container styling */
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive grid */
        gap: 20px;
        padding: 20px;
        justify-items: center;
        position: relative; /* Ensures the snowflakes are positioned behind the cards */
    }

    /* Snow effect - Create snowflakes falling behind the cards */
    .snowflake {
        position: absolute;
        top: -10px;
        width: 8px;
        height: 8px;
        background-color: #ffffff;
        border-radius: 50%;
        opacity: 0.9;
        animation: snow 6s linear infinite;
    }

    /* Keyframes for snow falling animation */
    @keyframes snow {
        0% {
            transform: translateX(0) translateY(0);
            opacity: 0.9;
        }
        100% {
            transform: translateX(100px) translateY(100vh); /* Snowflakes fall vertically */
            opacity: 0.4;
        }
    }

    /* Style for each card */
    .card {
        background-color: #ffffff;
        border: 2px solid #d4af37; /* Gold border */
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        background: linear-gradient(to right, #c0392b, #27ae60); /* Red and green gradient for Christmas theme */
        color: white;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 300px; /* Ensure all cards have at least 300px height */
        z-index: 2; /* Ensure cards are above the snowflakes */
    }

    /* Hover effect to make the card "pop" */
    .card:hover {
        transform: scale(1.05);
    }

    /* Header style for each card */
    .card h3 {
        font-size: 1.5em;
        margin-bottom: 10px;
        font-weight: bold;
    }

    /* Subheader for age */
    .card p {
        font-size: 1.1em;
        margin-bottom: 10px;
    }

    /* Style for the letter section */
    .letter-content {
        font-size: 1.2em;
        margin-top: 20px;
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        color: #2c3e50;
        flex-grow: 1; /* Ensures the letter section takes up remaining space */
    }

    /* Highlighting the "No letter found" message */
    .no-letter {
        color: #e74c3c; /* Red color for "No letter found" message */
        font-size: 1.2em;
    }

    /* Optional: Add a festive font for the title */
    h3 {
        font-family: "Arial", sans-serif;
        text-transform: uppercase;
    }

    /* Body styling for background */
    body {
        background-color: #e74c3c; /* Christmas red background */
        margin: 0;
        padding: 0;
        font-family: "Arial", sans-serif;
    }

    /* Allow scrolling */
    html, body {
        height: 100%;
    }

    /* Add padding to body to allow the content to stay inside the viewport */
    .content-wrapper {
        min-height: 100%; /* Make sure content wrapper covers the full height */
        padding-bottom: 50px; /* To ensure that the snowflakes are still visible at the bottom */
    }
</style>';

echo "<div class='content-wrapper'>";  // Wrap content to allow scrolling

echo "<div class='card-container'>";

// Loop over the children to display their cards
foreach ($children as $child) {
    // Fetch the letter for the current child
    $sender_id = $child["id"];
    $letter = null;
    
    // Search for the letter for this specific child
    foreach ($letters as $l) {
        if ($l["sender_id"] == $sender_id) {
            $letter = $l;
            break;
        }
    }

    // Create the Christmas card for each child
    echo "<div class='card'>";
    echo "<h3>" . $child["firstname"] . " " . $child["middlename"] . " " . $child["surname"] . "</h3>";
    echo "<p>Age: " . $child["age"] . "</p>";
    
    // Display the letter content if it exists, otherwise show a message
    if ($letter) {
        echo "<div class='letter-content'>";
        echo "<strong>Letter to Santa:</strong><p>" . nl2br(htmlspecialchars($letter["letter_text"])) . "</p>";
        echo "</div>";
    } else {
        echo "<div class='letter-content no-letter'>";
        echo "<strong>No letter found for this child.</strong>";
        echo "</div>";
    }
    
    echo "</div>"; // Close the card div
}

echo "</div>"; // Close the card-container div

// Create a number of snowflakes falling in the background
for ($i = 0; $i < 100; $i++) {  // Increase snowflakes for more frequent snowfall
    echo "<div class='snowflake' style='left: " . rand(0, 100) . "%; animation-duration: " . rand(4, 8) . "s; animation-delay: -" . rand(0, 10) . "s;'></div>";
}

echo "</div>";  // Close the content wrapper div
?>
