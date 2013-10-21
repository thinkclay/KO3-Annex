<p>
    Viewing page <?= $page; ?> of 
    <?php echo (isset($data['pages']) AND count($data['pages'])) ? $data['pages'] : '1'; ?>
</p>

<ul class="pagination">
    <?php for ($i=1; $i<=$data['pages']; $i++) : ?>
    <li><a class="<?= ($i == $page) ? 'selected' : ''; ?>" href="<?= $format.$i; ?>"><?= $i; ?></a></li>
    <?php endfor; ?>
</ul>