	<?php
	//load header
	include($include_header);
	?>

	<section id="home">
		<div class="inner_container">
			<div class="inner_left">
				<ul>
					<li id="home_user_icon"><?php echo $user_icon; ?></li>
					<li id="home_user_name"><?php echo $user_name; ?></li>
				</ul>
			</div>
			<div class="inner_center">
				<!------ post form ------>
				<form id="new_post" action="" method="post">
					<ul>
						<li class="post_icon">
							<label>
								<i class="fas fa-pencil-alt"></i>
								ポスト
								<input type="submit" name="new_post" class="input_hide">
							<label>
						</li>
						<li class="post_icon">
							<label>
								<i class="fas fa-camera"></i>
								フォト
								<input type="file" name="post_file" accept="image/*" id="post_photo" class="input_hide">
							</label>
						</li>
						<li class="post_icon">
							<label>
								<i class="fas fa-video"></i>
								ムービー
								<input type="file" name="post_file" accept="video/*"id="post_movie" class="input_hide">
							</label>	
						</li>
					</ul>
					<!-- div id="upload_preview">
					*make the reset button shows when a photo is prevewed by js
						<div id="preview_file"></div>
						<input type="reset" value="キャンセル" onclick="resetPreview();">
					</div -->
					<textarea name="new_post_body" value="<?php if(isset($_POST['new_post_body'])) { echo htmlentities($_POST['new_post_body']); } ?>" placeholder="ニュースを書いて、ポストボタンでポストしよう"></textarea>
				</form>
				<!-- a list of old posts -->
				<div id="post_list">
					<?php $post; ?>
				</div>
			
			</div>
			<div class="inner_right">
				<div id="home_post">
					<?php $post; ?>
				</div>
			</div>
				
		</div>
	</section>
