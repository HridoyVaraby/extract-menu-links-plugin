<?php
/**
 * Plugin Name: Extract Links Plugin
 * Description: A plugin to extract all links from a website.
 * Version: 1.1
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
    if (isset($_POST['extract_links_submit'])) {
        $url = esc_url_raw($_POST['extract_links_url']);
        $links = extract_links($url);
        echo '<ul>';
        foreach ($links as $link) {
            echo '<li><a href="' . esc_url($link['url']) . '">' . esc_html($link['text']) . '</a></li>';
        }
        echo '</ul>';
    }

    echo '<form method="post" action="">';
    echo '<input type="text" name="extract_links_url" placeholder="Enter URL" required />';
    echo '<input type="submit" name="extract_links_submit" value="Extract Links" />';
    echo '</form>';
}

// Shortcode to display the form and links
add_shortcode('extract_links', 'extract_links_form');
?>
