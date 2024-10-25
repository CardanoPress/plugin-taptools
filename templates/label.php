<?php

/**
 * The template for displaying the single token price label.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/taptools/label.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($result)) {
    return;
}

if (empty($token)) {
    $token = reset($result);
}

?>

<pre>Result: <?php print_r($result[$token]); ?></pre>
