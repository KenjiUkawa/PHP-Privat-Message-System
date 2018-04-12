<?php
include("{$core_path}/function/header_reqmts.php");
?>

		<header>
			<div id="header_inner">
				<div id="header_logo"><a href="">LOGO</a></div>
				
				<div id="header_inner_right">
					<ul id="header_user_info">
						<li><a href="index.php?page=user_page"><?php echo $user_icon ?><p>&nbsp;<?php echo $user_name ?></p></a></li>
						<li>　|　</li>
						<li><a href="index.php?page=home"><i class="fa fa-home" aria-hidden="true"></i></a></li>
					</ul>
					
					<ul id="header_function">
						<li><a href="index.php?page=friend_offer"><i class="fas fa-comments" aria-hidden="true"></i></a></li>
						<li><a href="index.php?page=inbox"><i class="fa fa-envelope" alt="Private Message"></i></a></li>
						<li><a href=""><i class="fa fa-info" aria-hidden="true"></i></a></li>
					</ul>
					
					<ul id="other_options">
						<li><a href="index.php?page=preference"><i class="fa fa-sliders-h" aria-hidden="true"></i></a></li>
						<li><a href="core/pages/logout.php?logout"><i class="fas fa-sign-out-alt" aria-hidden="true"></i></a></li>
					</ul>
				</div>
				
			</div><!-- div id="header_inner" -->
		</header>