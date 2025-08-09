<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftCart | Page Not Found</title>
       <?php include __DIR__ . '/../../Componenets/Other/Header.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/../../Componenets/Home/Navbar.php' ?>
    <section class="flex items-center h-full p-16 ">
	<div class="container flex flex-col items-center justify-center px-5 mx-auto my-8">
		<div class="max-w-md text-center">
			<h2 class="mb-8 font-extrabold text-9xl ">
				<span class="sr-only">Error</span>404
			</h2>
			<p class="text-2xl font-semibold md:text-3xl">Sorry, we couldn't find this page.</p>
			<p class="mt-4 mb-8 ">But dont worry, you can find plenty of other things on our homepage.</p>
			<a rel="noopener noreferrer" href="/SwiftCart" class="px-8 py-3 bg-[#d09523] hover:bg-[#f4b942] text-white font-semibold rounded ">Back to homepage</a>
		</div>
	</div>
</section>
<?php include __DIR__ . '/../../Componenets/Home/Footer.php'; ?>
</body>
</html>