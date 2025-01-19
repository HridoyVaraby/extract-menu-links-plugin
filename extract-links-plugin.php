<?php
/**
 * Plugin Name: Extract Links Plugin
 * Description: A plugin to extract all links from a website.
 * Version: 1.2
 * Author: Your Name
 * License: GPL2
 */

// Function to extract links
function extract_links($url) {
    // Initialize a cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $html = curl_exec($ch);
    curl_close($ch);

    if ($html === false) {
        return [];
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);  // Suppress errors due to malformed HTML
    $links = [];
    foreach ($dom->getElementsByTagName('a') as $link) {
        $href = $link->getAttribute('href');
        $text = $link->nodeValue;
        if ($href) {
            $links[] = ['url' => $href, 'text' => $text];
        }
    }

    return $links;
}

// Function to display the form and extracted links
function extract_links_form() {
    // Add custom styles
    echo '<style>
    .extract-links-form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .extract-links-form input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .extract-links-form input[type="submit"] {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #0073aa;
        color: #fff;
        cursor: pointer;
    }
    .extract-links-form input[type="submit"]:hover {
        background-color: #005a8c;
    }
    .extract-links-output ul {
        list-style-type: none;
        padding: 0;
    }
    .extract-links-output li {
        margin-bottom: 5px;
    }
    .extract-links-output a {
        color: #0073aa;
        text-decoration: none;
    }
    .extract-links-output a:hover {
        text-decoration: underline;
    }
    </style>';

    // Check if form is submitted and process the URL
    if (isset($_POST['extract_links_submit'])) {
        $url = esc_url_raw($_POST['extract_links_url']);
        $links = extract_links($url);
        echo '<div class="extract-links-output"><ul>';
        foreach ($links as $link) {
            echo '<li><a href="' . esc_url($link['url']) . '">' . esc_html($link['text']) . '</a></li>';
        }
        echo '</ul></div>';
    }

    // Display the form
    echo '<form method="post" action="" class="extract-links-form">';
    echo '<input type="text" name="extract_links_url" placeholder="Enter URL" required />';
    echo '<input type="submit" name="extract_links_submit" value="Extract Links" />';
    echo '</form>';
}

// Shortcode to display the form and links
add_shortcode('extract_links', 'extract_links_form');
?>
