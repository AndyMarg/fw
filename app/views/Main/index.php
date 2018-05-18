<!--View Main->index begin -->
<div class="container">
    <button class="btn btn-default" id="send">Кнопка</button>
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
                    console.log(res);
                },
                error: function () {
                    alert('ERROR!!!!');
                }
            });
        });
    });
</script>
