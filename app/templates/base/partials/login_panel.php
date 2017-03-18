<div class="panel panel-default">
    <div class="panel-heading">Logowanie</div>
    <div class="panel-body">
        <form action="/login" method="POST">

            <input name="login" type="text" class="form-control" placeholder="Login / Email">
            <input name="password" type="password" class="form-control" placeholder="Hasło">

            <button type="submit" class="btn btn-primary button-login">Zaloguj się</button>
            <input title="Remember me" name="remember_me" type="checkbox" style="margin-top: 10px;"> Zapamiętaj mnie
            <br>
            <a href="/help">Nie mogę się zalogować</a>

        </form>
        <br>

        <a href="<?= Facebook::getLoginURL() ?>" class="btn btn-facebook">
            <i class="fa fa-facebook"></i> | Zaloguj przez facebooka
        </a>

        <br/><br/>

        <div id="status">
        </div>
    </div>
</div>