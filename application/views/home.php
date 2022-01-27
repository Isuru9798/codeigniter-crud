<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php $this->load->view('layouts/headers.php'); ?>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12 mt-5">
				<h1>Student Management</h1>
				<hr>
				<?php include 'students/add-student.php' ?>
			</div>
		</div>
		<?php $this->load->view('students/students.php'); ?>
	</div>


</body>
<?php $this->load->view('layouts/footers.php'); ?>

</html>