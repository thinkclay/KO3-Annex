<?php if ( isset($data) AND isset($model) ) : ?>
<?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
<table cellspacing="0" cellpadding="0" class="administration">
    <thead>
        <tr>
            <th>Field Name</th>
            <th>Field Data</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $data[0]->_fields as $key => $val ) : ?>
        <?php if ( isset($val['editable']) AND $val['editable'] ) : $keys[] = $key; ?>
        <tr>
            <td><?php echo @$key; ?></td>
            <td><?php echo @$row[$key]; ?></td>
            <td><a href="/admin/content/view/<?php echo $model; ?>/<?php echo $row['_id']; ?>">Details</a></td>
        </tr>
        <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<hr />
<?php endforeach; ?>
<?php endif; ?>