<li>
    <a class="user_menu" href="?id=<?=$id;?>"><?=$node['title'];?></a>
    <?php if (isset($node['childs'])) : ?>
        <ul>
            <?= $this->getHtmlCode($node['childs']); ?>
        </ul>
    <?php endif; ?>
</li>