<?php

/**
 * The template for displaying the multiple token price ticker.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/taptools/ticker.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($result) || empty($tokens)) {
    return;
}

?>

<style>
    .marquee-container {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .marquee-content {
        width: 1000%;
        display: flex;
        animation: marquee 50s linear infinite;
    }

    .marquee-item {
        flex: 0 0 auto;
        display: block;
        position: relative;
        padding: 0 10px;
    }

    @keyframes marquee {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    .marquee-container:hover .marquee-content {
        animation-play-state: paused;
    }
</style>

<div class="marquee-container">
    <div class="marquee-content">
        <?php foreach ($result as $token => $price) : ?>
            <span class="marquee-item">
                <?php echo $tokens[$token]['symbol'] ?? ''; ?> = <?php echo $price; ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>
