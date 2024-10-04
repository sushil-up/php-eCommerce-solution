<?php require_once("header.php");?>
<div class="container error-container mt-4 mb-5">
	<div class="row d-flex align-items-center justify-content-center mb-5">
		<div class="col-md-12 text-center">
			<h1 class="big-text">Oops!</h1>
			<h2 class="small-text my-4">404 - PAGE NOT FOUND</h2>

		</div>
		<div class="col-md-6  text-center">
			<p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
			<form action="search" class="mt-5">
				<div class="form-group">
					<input type="text" class="border p-3 border-1 border-primary rounded-start border-end-0" placeholder="Search.." name="s"><button class="border border-1 p-3 px-4 bg-primary border-primary rounded-end text-light" type="submit"><i class="fa fa-search"></i></button>
				</div>

			</form>
		</div>
	</div>
</div>
<?php require_once("footer.php");?>