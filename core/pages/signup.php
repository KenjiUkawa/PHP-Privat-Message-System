	<h2>SIGNUP FORM</h2>
	<form action="" method="post">
		<input type="text" name="user_name" placeholder="Name" value="<?php if(isset($_POST['user_name'])) { echo htmlentities($_POST['user_name']); } ?>" required>
		<input type="email" name="user_email" placeholder="Email" value="<?php if(isset($_POST['user_email'])) { echo htmlentities($_POST['user_email']); } ?>" required>
		<input type="password" name="user_password" placeholder="Password" required>
		<button type="submit" value="Signup" name="signup">Signup</button>
		<a href="index.php?page=login">Login</a>
	</form>