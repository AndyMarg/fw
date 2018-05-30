<!--View Main->index begin -->
<div class="container">

    <button class="btn btn-default" id="send">Кнопка</button>
    <br><br>

    <?php
        $menu = new \fw\widgets\menu\Menu();
        $menu->configure([
           'template' =>  $_SERVER['DOCUMENT_ROOT'] . '/app/widgets/menu/tpl/select_menu.tpl.php',
           'html_container' => 'select',
           'container_class' => 'select_menu',
            'cache_key' => 'select_menu',
            'cache_time' => 2400
        ]);
        $menu->run();
    ?>
    <br><br>

    <?php
        $menu = new \fw\widgets\menu\Menu();
        $menu->run();
    ?>

    <div id="answer"></div>
    <?php if(!empty($posts)): ?>
        <?php foreach ($posts as $post): ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?= $post['title'] ?></div>
                    <div class="panel-body"><?= $post['text'] ?></div>
                </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<!--View Main->index end -->

<script>
    $(function() {
        $('#send').click(function () {
            $.ajax({
                url: '/main/test',
                type: 'post',
                data: {'id': 2},
                success: function (res) {
                    //console.log(res);
                    $('#answer').html(res);
                },
                error: function () {
                    alert('ERROR!!!!');
                }
            });
        });
    });
</script>
