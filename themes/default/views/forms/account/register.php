<br />
<div class="row-fluid">
    <h1>Register</h1>

    <form method="post" action="/account/register">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?= $username; ?>" />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= $password; ?>" />

        <label for="password_confirm">Confirm Password</label>
        <input type="password" name="password_confirm" id="password_confirm" value="<?= $password_confirm; ?>" />

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?= $email; ?>" />

        <br />

        <button type="submit" class="btn">Submit</button>
    </form>
</div>