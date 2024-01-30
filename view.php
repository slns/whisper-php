<?php 
	include 'backend/init.php';

	$file = null;

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isset($_GET['file'])) {
			$file = $whisperObj->getFileById($_GET['file']);

			if (!$file) {
				header('Location: index.php');
			}
		}
	}	
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['translate'])) {
			$file = $whisperObj->getFileById($_GET['file']);

			if ($file) {
				$lang = $_POST['lang'];

				$allowedLang = [
					'Portuguese',
					'English',
					'French',
					'Spanish',
					'German',
				];

				if (in_array($lang, $allowedLang)) {
					$whisperObj->dataType = 'Translate';
					$whisperObj->content = $file->content;
					$whisperObj->lang = $lang;

					if (!$whisperObj->errors()) {
						$text = $whisperObj->covert();
						$translated = $text['choices'][0]['message']['content'];
						$whisperObj->update($file->id, $translated, $lang);

						header('Location: view.php?file='.$file->id);
					}
				} else {
					$error = 'Invalid language Selected!';
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>
		My Whisper Ai - Convert Video/Audio language into written text
	</title>
	<!-- FONT-AWESOME LINK -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- CSS LINK -->
    <link rel="stylesheet" type="text/css" href="frontend/style/style.css"/>
	<!-- TAILWIND CSS LINK -->
    <script src="https://cdn.tailwindcss.com"></script>
	<!-- GOOGLE FONTS LINK -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <!-- JQUERY LINK -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="">
<div class="Wrapper">
	<div class="inner-wrapper h-screen flex justify-center p-20">
		<div class="h-full border-2 rounded-xl border-gray-700" style="width: 1280px; max-height: 1080px; box-shadow: -20px 20px 0px 0px #74a840,-21px 21px 0px 3px #343333">
			<div class="flex h-full flex-col">
				<!--header-section-->
				<div class="flex w-full justify-between p-6">
					<div class="flex justify-center flex-col">
						<h2 class="text-2xl font-semibold">My Whipser</h2>
						<span><a class="font-semibold text-lime-600" href="https://www.meralesson.com">Meralesson</a></span>
					</div>
					<div class="flex items-center justify-center">
						<a href="index.php" class="border rounded px-4 py-2 cursor-pointer hover:bg-gray-100">Back</a>
					</div>
				</div>
				<!--Body-section-->
				<div class="px-10 overflow-hidden">
					<div class="flex">
						<!--Left-video-section-->
						<div class="w-1/3">
 							<div>
								 <?php if($file->type == 'audio'): ?>
									<!-- Audio Player -->
									<audio controls style="width:100%;border-radius: 20px;">
 										<source src="<?php echo $file->fileUrl; ?>" type="audio/mpeg">
										Your browser does not support the audio element.
									</audio> 
								<?php else: ?>
								 	<!-- Video Player -->
									<video style="height:240px; width:100%;" controls>
										<source src="<?php echo $file->fileUrl; ?>">
 										Your browser does not support the video tag.
									</video> 
								<?php endif; ?>
								 
							</div>
							<!--Tabs-->
							<div class="flex flex-col">
								<div>
									<ul class="flex border-b justify-between">
										<li class="flex-1 flex font-sm items-center justify-center text-2xl text-gray-300 cursor-pointer hover:bg-gray-100">
											<span><i class="fa-solid fa-hands-asl-interpreting"></i></span>
										</li>
										<li class="flex-1 flex items-center justify-center font-sm text-2xl text-gray-300 cursor-pointer hover:bg-gray-100">
											<span><i class="fa-solid fa-database"></i></span>
										</li>
									</ul>
								</div>
								<div style="height: 400px; overflow:scroll;">
									<div class="flex" >
										<ul class="w-full h-full">
											<!-- RECENT GENERATED FILES -->
											<?php $whisperObj->getRecentList() ?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!--Right-Transcript-section-->
						<div class="flex-1">
							<div id="scroll" class="py-10 px-5 overflow-y-scroll w-full" style="height:500px;">
								
								<ul class="w-full">
									<li class="flex flex-col my-5 w-full">
										<h3 class="text-2xl">Text</h3>
										<div>
                                            <?php echo $file->content; ?>
										</div>
									</li>
									<li class="flex flex-col my-5 w-full">
										<!--loader-->
										<div id="loader" class="hidden flex flex-col text-center flex-1  rounded-xl w-full">
											<div class="flex items-center flex-col">
												<img class="w-20 animate-pulse mt-20" src="frontend/images/loader.gif"/>
												<span class="animate-pulse">
													Translating....
												</span>
											</div>
										</div>
									</li>
									<?php if($file->translated): ?>
									 <!-- TRANSLATED CONTENT -->
										<li id="translateContent" class="flex flex-col my-5 w-full">
											<h3 class="text-2xl"><?php echo $file->lang; ?></h3>
											<div>
												<?php echo $file->translated; ?>
											</div>
										</li>
									 <?php endif; ?>
								</ul>

							</div>
							<form id="form" method="POST">
								<div class="flex flex-col justify-center items-center">
 									<div class="">
									 	<label for="lang">Select Language</label>
										<select id="lang"  name="lang"  class="px-4 py-3 mx-2 my-3">
											<option value="Portuguese">Portuguese</option>
											<option value="English">English</option>
											<option value="French">French</option>
											<option value="Spanish">Spanish</option>
											<option value="German">German</option>
										</select>
										
										<button id="translateBtn" name="translate" class="border border-gray-700 rounded px-6 py-2 cursor-pointer hover:bg-gray-100 font-normal">Translate</button>
									</div>
									<?php if(isset($error) || $error = $whisperObj->errors()): ?>
									 	<!-- ERROR HERE -->
										<div class="flex bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative">
											<strong class="font-bold">Error:</strong>
											<span class="block sm:inline"><?php echo $error; ?></span>
										</div>
									<?php endif; ?>
								</div>
							</form>
 						</div>
					</div>
				</div>

			</div>
		</div>	
	</div>
</div>
<!-- JS CODE HERE -->
<script>
	$('#translateBtn').click(function(event) {
		let scrollHeight = $('#scroll')[0].scrollHeight;
		$('#translateContent').addClass('hidden');
		$('#loader').removeClass('hidden');
		$('#scroll').animate({scrollTop: scrollHeight}, "fast");
	});
</script>
</body>
</html>