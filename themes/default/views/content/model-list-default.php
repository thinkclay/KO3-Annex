<?php if ( isset($data) AND isset($model) ) : ?>
<?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
<table cellspacing="0" cellpadding="0" class="administration">
    <thead>
        <tr>
            <th>Field Name</th>
            <th>Field Data (<a href="/admin/content/view/<?php echo $model; ?>/<?php echo $row['_id']; ?>">Details</a>)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $row as $key => $val ) : ?>
        <tr>
            <td><?php echo @$key; ?></td>
            <td>
            <?php if ( isset($row[$key]) AND is_array($row[$key]) ) : ?>
                <pre><?php print_r($row[$key]); ?></pre>
            <?php else : ?>
                <?php echo $row[$key]; ?>
            <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>
<hr />
<?php endforeach; ?>
<?php endif; ?>