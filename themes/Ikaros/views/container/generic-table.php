<?php if ( isset($data) ) : ?>
<table cellspacing="0" cellpadding="0">
    <thead>
        <tr>
        <?php foreach ( $data[0]->_fields as $key => $val ) : ?>
        <?php if ( isset($val['editable']) AND $val['editable'] ) : ?>
            <th><?php echo $key; ?></th>
        <?php endif; ?>
        <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
        <tr>
        <?php foreach ( $data[0]->_fields as $key => $val ) : ?>
        <?php if ( isset($val['editable']) AND $val['editable'] ) : ?>
            <td>
            <?php if ( @is_array($row[$key]) ) : ?>
                <?php print_r($row[$key]); ?>
            <?php else : ?>
                <?php echo @$row[$key]; ?>
            <?php endif; ?>
            </td>
        <?php endif; ?>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>