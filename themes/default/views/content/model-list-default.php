<br /><br />

<?php if ( isset($data) AND isset($model) ) : ?>
<?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
<table cellspacing="0" cellpadding="0" class="administration">
    <thead>
        <tr>
            <th>Field Name</th>
            <th>Field Data (<a href="/admin/content/view/<?php echo $model; ?>/<?= @$row['_id']; ?>">Details</a>)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $row as $key => $val ) : ?>
        <tr>
            <td><?php echo @$key; ?></td>
            <td>
            <?php if ( is_object($row[$key]) AND isset($row[$key]) ) : ?>
                <pre><?php var_dump($row[$key]); ?></pre>
            <?php elseif ( is_array($row[$key]) AND isset($row[$key]) ) : ?>
                <pre><?php print_r($row[$key]); ?></pre>
            <?php elseif ( is_bool($row[$key]) AND isset($row[$key]) ) : ?>
                <?php var_dump($row[$key]); ?>
            <?php elseif ( is_string($row[$key]) AND isset($row[$key]) ) : ?>
                <?= $row[$key]; ?>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<hr />
<?php endforeach; ?>
<?php endif; ?>