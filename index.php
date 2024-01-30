<?php 

include 'backend/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_FILES['file'])) {
		if (!empty($_FILES['file']['name'][0])) {
			$fileType = $_FILES['file']['type'];

		$file = $whisperObj->upload($_FILES['file']);
		

		if ($file) {
			$whisperObj->dataType = 'ASR';
			$whisperObj->file = $file;
			$text = $whisperObj->covert();

			if (strpos($fileType, 'audio/') === 0) {
				$type = 'audio';
			} else if (strpos($fileType, 'video/') === 0) {
				$type = 'video';
			}

			$fileId = $whisperObj->save($file, $text['text'], $type);

			header("location: view.php?file={$fileId}");
		}
			
		}
	} else {
		$error = "Please select a file to convert into a text";
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
			<div class="flex h-full flex-col items-center">

				<!--loader-->
				<div id="loader" class="hidden flex flex-col text-center flex-1 " style="width: 600px;">
					<div class="flex items-center flex-col">
						<img class="w-40 animate-pulse mt-60" src="frontend/images/loader.gif"/>
						<span class="animate-pulse">
							Uploading....
						</span>
					</div>
				</div>

				<div id="upload" class="flex flex-col text-center mt-40" style="width: 600px;">
					<img class="mx-auto my-4" src="frontend/images/banner-img.png" style="width: 300px;">
					<h1 class="text-4xl py-2">Welecome to My Whisper</h1>
					<p class="text-xl">
                      Whisper is an AI tool that allows you to convert video/audio files into text and also translate the text into your desired language.					</p>
					<label style="width: 200px;box-shadow: -7px 6px 0px 0px;
  border: 1px solid;" class="cursor-pointer select-none mx-auto my-4 rounded-full text-xl text-gray-600 px-10 py-5 border bg-lime-400" for="file-upload">
						Upload File
					</label>
					<form id="form"  method="post" enctype="multipart/form-data">
                        <input id="file-upload" class="hidden "type="file" name="file">
                    </form>
                    <!-- ERROR DIV -->
					<?php if(isset($error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Error:</strong>
                            <span class="block sm:inline"><?php echo $error; ?></span>
                        </div>
                   <?php endif; ?>
                    <p>Recently Generated Files : Click <a href="view.php?file=1" class="text-green font-bold text-sm">Here to View</p>
				</div>
			</div>
		</div>	
	</div>
</div>
<script>
	$(document).ready(function() {
		$('#file-upload').change(function() {
			if (this.files.length > 0) {
				$('#loader').removeClass('hidden');
				$('#upload').addClass('hidden');
				$('#form').submit();
			}
		});
	});
</script>
</body>
</html>
