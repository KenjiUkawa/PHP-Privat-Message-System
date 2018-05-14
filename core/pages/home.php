<?php
//load header
include($include_header);

/*---------------------------------------
	validation to new post form
---------------------------------------*/
if(isset($_POST['new_post'])){
	$errors=array();

	if(empty($_POST['post_text'])){
		$errors[]="ポストを入力してください。";
	}

	//post if no errors
	if(empty($errors)){
		create_post($_SESSION['user_id'],$_POST['post_text'], $_POST['post_img'],$_POST['post_movie'], $mysqli);
		$success_message= 'ポストしました。';
	}
}
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

				<?php
					if(isset($errors)){
						foreach($errors as $error){
							echo '<div class="errors"><i class="fas fa-exclamation-circle fa-2x"></i><p>'.$error.'</p></div><br>';
						}
					}elseif(isset($success_message)){
						//Show success message
						echo '<div class="success_message"><i class="fas fa-check fa-2x"></i>'.$success_message.'</div>';
					}
				?>

				<!------ post form ------>
				<form id="new_post" action="" method="post" enctype="multipart/form-data">
					<ul>
						<li class="post_icon">
							<label>
								<i class="fas fa-pencil-alt"></i>
								ポスト
								<input type="submit" name="new_post" class="input_hide">
							</label>
						</li>
						<li class="post_icon">
							<label>
								<i class="fas fa-camera"></i>
								フォト
								<input type="file" name="post_img" accept="image/*" id="post_photo" class="input_hide">
							</label>
						</li>
						<li class="post_icon">
							<label>
								<i class="fas fa-video"></i>
								ムービー
								<input type="file" name="post_movie" accept="video/*"id="post_movie" class="input_hide">
							</label>
						</li>
					</ul>
					<!-- div id="upload_preview">
					*make the reset button shows when a photo is prevewed by js
						<div id="preview_file"></div>
						<input type="reset" value="キャンセル" onclick="resetPreview();">
					</div -->
					<textarea name="post_text" value="<?php if(isset($_POST['post_text'])) { echo htmlentities($_POST['post_text']); } ?>" placeholder="ニュースを書いて、ポストボタンでポストしよう"></textarea>
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
