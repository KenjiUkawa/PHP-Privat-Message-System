		<header>
			<div id="header_inner">
				<div id="header_logo"><a href="">LOGO</a></div>
			</div><!-- div id="header_inner" -->
		</header>

		<h2>LOGIN FORM</h2>
		<form action="" method="post">
			<input type="email" name="user_email" placeholder="Email" value="<?php if(isset($_POST['user_email'])) { echo htmlentities($_POST['user_email']); } ?>" required>
			<input type="password" name="user_password" placeholder="Password" required>
			<button type="submit" value="Login" name="login">Login</button>
			<a href="index.php?page=signup">Signup</a>
		</form>