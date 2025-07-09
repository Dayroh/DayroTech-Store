<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Internet Plans | DayrohTech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="whatsapp.css">
<script src="whatsapp.js" defer></script>
</head>
<body>
  <!-- Background Video -->
<video autoplay muted loop playsinline id="indexVideo">
  <source src="assets/videos/index.mp4" type="video/mp4" />
  Your browser does not support HTML5 video.
</video>
  <div class="container mt-5">
  <div class="alert alert-info text-center shadow">
    <h1 class="text-success mb-3">🚀 Get Connected with DayrohTech Internet!</h1>
    <p class="lead mb-1">
      Enjoy <strong>free installation</strong> when you join us with a one-time setup fee of <strong>Ksh 4,500</strong> —
      covering a router, quality cabling, and your first 5 Mbps monthly plan!
    </p>
    <p class="mb-0 text-primary">
      No hassle. No hidden fees. Just blazing internet delivered and installed by our team. 
      Get started today and stay connected effortlessly!
    </p>
  </div>
</div>

<div class="container my-5">
  <h2 class="text-center">🌐 Our Internet Plans</h2>
  <p class="text-center text-muted mb-4">Choose a plan that fits your home or business.</p>

  <div class="row">
    <!-- Basic 5 Mbps -->
    <div class="col-md-4 mb-4">
      <div class="card border-primary">
        <div class="card-body text-center">
          <h5 class="card-title">Basic Plan</h5>
          <p class="card-text">5 Mbps / 30 Days</p>
          <h4 class="text-primary">Ksh 1,299</h4>

          <form action="request_plan.php" method="POST">
            <input type="hidden" name="package" value="Basic Plan - 5 Mbps / Ksh 1,299">
            <div class="mb-2">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-2">
    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
  </div>
            <div class="mb-2">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>

            <button type="submit" class="btn btn-outline-primary btn-sm">Request Plan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Basic 10 Mbps -->
    <div class="col-md-4 mb-4">
      <div class="card border-primary">
        <div class="card-body text-center">
          <h5 class="card-title">Basic Plan</h5>
          <p class="card-text">10 Mbps / 30 Days</p>
          <h4 class="text-primary">Ksh 1,999</h4>

          <form action="request_plan.php" method="POST">
            <input type="hidden" name="package" value="Basic Plan - 10 Mbps / Ksh 1,999">
            <div class="mb-2">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-2">
    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
  </div>
            <div class="mb-2">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <button type="submit" class="btn btn-outline-primary btn-sm">Request Plan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Standard -->
    <div class="col-md-4 mb-4">
      <div class="card border-success">
        <div class="card-body text-center">
          <h5 class="card-title">Standard Plan</h5>
          <p class="card-text">15 Mbps / 30 Days</p>
          <h4 class="text-success">Ksh 2,999</h4>

          <form action="request_plan.php" method="POST">
            <input type="hidden" name="package" value="Standard Plan - 15 Mbps / Ksh 2,999">
            <div class="mb-2">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-2">
    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
  </div>
            <div class="mb-2">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <button type="submit" class="btn btn-outline-success btn-sm">Request Plan</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Pro -->
    <div class="col-md-4 mb-4">
      <div class="card border-warning">
        <div class="card-body text-center">
          <h5 class="card-title">Pro Plan</h5>
          <p class="card-text">30 Mbps / 30 Days</p>
          <h4 class="text-warning">Ksh 4,999</h4>

          <form action="request_plan.php" method="POST">
            <input type="hidden" name="package" value="Pro Plan - 30 Mbps / Ksh 4,999">
            <div class="mb-2">
              <input type="text" name="name" class="form-control" placeholder="Your Name" required>
            </div>
            <div class="mb-2">
    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
  </div>
            <div class="mb-2">
              <input type="email" name="email" class="form-control" placeholder="Your Email" required>
            </div>
            <button type="submit" class="btn btn-outline-warning btn-sm">Request Plan</button>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>
</body>
</html>
