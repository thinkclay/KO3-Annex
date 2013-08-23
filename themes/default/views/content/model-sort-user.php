<?php if ( isset($data) AND count($data) ) : ?>
<div class="wysiwyg" data-cms="cms.model-list-user.before">{{&cms.model-list-user.before}}</div>

<table cellspacing="0" cellpadding="0" class="sortable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $data as $row ) : $row = $row->as_array(); ?>
        <tr id="<?= $row['_id']; ?>">
            <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name']; ?></td>
            <td><?= $row['email']; ?></td>
            <td>
                <a href="/admin/become-user/<?php echo @$row['_id']; ?>">become</a> |
                <a href="/admin/user/<?php echo @$row['_id']; ?>">edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>