<?php if ( isset($data) AND isset($model) ) : ?>
<table cellspacing="0" cellpadding="0" class="administration">
    <thead>
        <tr>
        <?php foreach ( $data[0]->_fields as $key => $val ) : ?>
        <?php if ( isset($val['editable']) AND $val['editable'] ) : $keys[] = $key; ?>
            <th><?php echo @$val['label']; ?></th>
        <?php endif; ?>
        <?php endforeach; ?>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
        <tr>
            <?php foreach ( $keys as $key ) : ?>
            <td><?php echo (string) @$row[$key]; ?></td>
            <?php endforeach; ?>
            <td><a href="/admin/content/view/<?php echo $model; ?>/<?php echo $row['_id']; ?>">Details</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>