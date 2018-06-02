<h1>Регистрация</h1>

<div class="row">
    <div class="col-md-6">
        <form action="/user/signup" method="post">
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control" name="login" id="login" placeholder="Login">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Email">
            </div>
        <button class="btn btn-default" type="submit">Зарегистрировать</button>
        </form>
    </div>
</div>