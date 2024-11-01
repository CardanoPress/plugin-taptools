<?php

/**
 * The template for displaying the single token price label.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/taptools/label.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($result) || empty($asset)) {
    return;
}

if (empty($token)) {
    $token = reset($result);
}

?>

<div><b><?php echo $asset['symbol']; ?></b>: <?php echo $result[$token]; ?></div>
