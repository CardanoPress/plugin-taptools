<?php

/**
 * The template for displaying the single token price label.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/taptools/label.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($result) || empty($token)) {
    return;
}

?>

<div><b><?php echo $token['symbol']; ?></b>: <?php echo $result; ?></div>
