<option value="<?=$id?>"><?= $tab . $node['title'];?></option>
<?php if (isset($node['childs'])) : ?>
    <?= $this->getHtmlCode($node['childs'], $tab . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'); ?>
<?php endif; ?>
