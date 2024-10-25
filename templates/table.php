<?php

/**
 * The template for displaying the multiple token price table.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/taptools/table.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($result)) {
    return;
}

?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Unit</th>
            <th>Price</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($result as $token => $price) : ?>
            <tr>
                <td>
                    <?php echo $token; ?>
                </td>
                <td>
                    <?php echo $price; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
