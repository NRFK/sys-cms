</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.7/js/bootstrap.bundle.min.js" integrity="sha512-Tc0i+vRogmX4NN7tuLbQfBxa8JkfUSAxSFVzmU31nVdHyiHElPPy2cWfFacmCJKw0VqovrzKhdd2TSTMdAxp2g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	function urlClick(url = "https://nakedkitchens.com", goHome = false) {
		Swal.fire({
			title: "Are you sure?",
			text: "You will be sent to: " + url,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, I am sure."
		}).then((result) => {
			if (result.isConfirmed) {
				goHome ? window.location.href = url : window.location.href = "https://nakedkitchens.com"; // Use href for redirect
			}
		});
	}
</script>
<div class="footer-note">
	<!--<span class="redirect-link" onClick="urlClick(true)">This page was made by the Naked Kitchens IT Team.</span>-->
</div>

<?php if (isset($_SESSION['username'])) { ?>
<div class="user-menu" style="position:absolute;top:10px;right:10px;">
	<!-- User button in navbar -->
	<button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
		<i class="bi bi-person-circle"></i> <?php echo $_SESSION['username']; ?>
	</button>

</div>
<div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="userOffcanvas" aria-labelledby="userOffcanvasLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="userOffcanvasLabel">User Profile</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body">
		<p>Logged in as <?php echo $_SESSION['username']; ?></p>
		<a href="/admin/user/logout.php" class="btn btn-danger w-100">Logout</a>
	</div>
</div>
<?php } ?>

</body>
</html>
