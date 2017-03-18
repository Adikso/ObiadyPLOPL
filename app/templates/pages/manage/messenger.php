<?php
$this->layout('base/main', ['title' => 'Wiadomości']);
?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.tagsinput/0.4.2/bootstrap-tagsinput.css"/>
<link href="/css/pages/manage/messenger.css">
<?php $this->end() ?>

<?php $this->push('scripts') ?>
<script src="/js/vendor/bootstrap-tagsinput.min.js"></script>
<?php $this->end() ?>


<div class="order-day">

        <div class="panel panel-default">
            <div class="panel-heading">Lista wiadomości</div>
            <div class="panel-body table-responsive">
                <form method="post" action="#">
                    <?= $csrf = csrfField(); ?>
                    <table class="table">
                        <tr>
                            <th></th>
                            <th>Odbiorca</th>
                            <th>Treść</th>
                            <th>Adres</th>
                        </tr>
                        <?php
                        foreach ($messages as $message) { ?>

                            <tr>
                                <td style="width: 5px;">
                                    <input type="checkbox" class="pull-right" name="remove[]"
                                           value="<?= $message->id ?>">
                                </td>
                                <td>
                                    <a href="<?= route('profile', ['id' => $message->target]) ?>"><?= $message->target ?></a>
                                </td>
                                <td>
                                    <?= $message->description ?>
                                </td>
                                <td>
                                    <?= (!empty($message->url) ? '<a href="'.$message->url.'">'.$message->url.'</a>' : '') ?>
                                </td>
                            </tr>

                            <?php
                        }

                        if (sizeof($messages) == 0) {
                            ?>
                            <tr>
                                <td></td>
                                <td>Brak wiadomości</td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php }

                        ?>
                    </table>
                    <button class="btn btn-default" type="submit" value="delete">Usuń zaznaczone</button>

                </form>
            </div>
        </div>


        <form method="POST" action="">


            <div class="panel panel-default">
                <div class="panel-heading">Wysyłanie wiadomości</div>
                <div class="panel-body">
                    <?= $csrf; ?>
                    <div class="form-group">
                        <label class="control-label">Odbiorcy</label><br>
                        <input data-role="tagsinput" type="text" name="to" class="form-control" value="<?= $to ?>"/>
                    </div>
                    <input type="text" class="form-control" name="url" id="url" placeholder="Link (adres)">

                    <textarea style="height: 190px;" name="message" class="form-control"></textarea>
                    <button class="btn btn-default form-control" type="submit" name="sendmsg">Wyślij
                    </button>
                </div>
            </div>

        </form>

</div>