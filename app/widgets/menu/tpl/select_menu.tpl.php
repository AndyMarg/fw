<option value="<?=$id?>"><?=$node['title'];?></option>
<?php if (isset($node['childs'])) : ?>
    <?= $this->getHtmlCode($node['childs']); ?>
<?php endif; ?>
