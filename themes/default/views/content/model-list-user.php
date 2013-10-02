<?php if (isset($data) AND isset($model)) : ?>
<div class="wysiwyg" data-cms="cms.model-list-user.before">{{&cms.model-list-user.before}}</div>

<?php foreach ($data as $row) : $row = $row->as_array(); ?>
<table cellspacing="0" cellpadding="0" class="administration" width="100%">
    <thead>
        <tr>
            <th colspan="2">
                <?= @$row['first_name'].' '.@$row['last_name']; ?>
                (<a href="/admin/content/view/<?php echo $model; ?>/<?= @$row['_id']; ?>">edit</a>)
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Name</td>
            <td><?= @$row['first_name'].' '.@$row['middle_name'].' '.@$row['last_name']; ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= @$row['email']; ?></td>
        </tr>
        <tr>
            <td>Created</td>
            <td><?= date('M j, Y', $row['created']); ?></td>
        </tr>
        <tr>
            <td>Last Login</td>
            <td><?= date('M j, Y', $row['last_login']); ?></td>
        </tr>
        <tr>
            <td>Role</td>
            <td><?= $row['role']; ?></td>
        </tr>
        <tr>
            <td>Username</td>
            <td><?= $row['username']; ?></td>
        </tr>
    </tbody>
</table>
<?php endforeach; ?>
<?php endif; ?>