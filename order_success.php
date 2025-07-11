<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Success | DayrohTech</title>
  <link rel="icon" href="assets/images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4361ee;
      --success: #4cc9f0;
      --light: #f8f9fa;
      --dark: #212529;
      --border-radius: 12px;
      --box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
      min-height: 100vh;
      display: flex;
      padding: 16px;
    }

    .success-card {
      width: 100%;
      max-width: 420px;
      margin: auto;
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      animation: fadeIn 0.6s ease-out;
    }

    .success-header {
      background: linear-gradient(135deg, var(--primary), var(--success));
      color: white;
      padding: 24px;
      text-align: center;
      position: relative;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .success-icon i {
      font-size: 40px;
      color: var(--primary);
      animation: bounce 1s infinite alternate;
    }

    .success-body {
      padding: 24px;
    }

    .order-steps {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin: 24px 0;
    }

    .step {
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .step-icon {
      width: 36px;
      height: 36px;
      background: var(--light);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .step-content {
      flex-grow: 1;
    }

    .step-title {
      font-weight: 600;
      margin-bottom: 4px;
    }

    .step-desc {
      color: #6c757d;
      font-size: 14px;
    }

    .btn-continue {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, var(--primary), var(--success));
      border: none;
      border-radius: var(--border-radius);
      color: white;
      font-weight: 600;
      font-size: 16px;
      margin-top: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s ease;
    }

    .btn-continue:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(67, 97, 238, 0.25);
    }

    .confirmation-note {
      text-align: center;
      font-size: 13px;
      color: #6c757d;
      margin-top: 24px;
      padding-top: 16px;
      border-top: 1px solid #eee;
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes bounce {
      from { transform: translateY(0) scale(1); }
      to { transform: translateY(-8px) scale(1.05); }
    }

    /* Responsive adjustments */
    @media (min-width: 576px) {
      .success-card {
        max-width: 480px;
      }
      
      .success-body {
        padding: 32px;
      }
    }

    @media (max-width: 375px) {
      .success-header {
        padding: 20px;
      }
      
      .success-icon {
        width: 64px;
        height: 64px;
      }
      
      .success-icon i {
        font-size: 32px;
      }
    }
  </style>
</head>
<body>
  <div class="success-card">
    <div class="success-header">
      <div class="success-icon">
        <i class="fas fa-check"></i>
      </div>
      <h2 style="font-weight:700;margin-bottom:8px;">Order Confirmed!</h2>
      <p style="opacity:0.9;font-size:15px;">Thank you for shopping with us</p>
    </div>
    
    <div class="success-body">
      <p style="text-align:center;margin-bottom:0;">We've received your order #<?= rand(10000,99999) ?> and will process it shortly.</p>
      
      <div class="order-steps">
        <div class="step">
          <div class="step-icon" style="color:#28a745;">
            <i class="fas fa-check"></i>
          </div>
          <div class="step-content">
            <div class="step-title">Order Confirmed</div>
            <div class="step-desc">Your payment has been processed</div>
          </div>
        </div>
        
        <div class="step">
          <div class="step-icon" style="color:#fd7e14;">
            <i class="fas fa-box-open"></i>
          </div>
          <div class="step-content">
            <div class="step-title">Preparing Order</div>
            <div class="step-desc">We're getting your items ready</div>
          </div>
        </div>
        
        <div class="step">
          <div class="step-icon" style="color:#4361ee;">
            <i class="fas fa-truck"></i>
          </div>
          <div class="step-content">
            <div class="step-title">On Its Way</div>
            <div class="step-desc">Delivery expected in 2-3 days</div>
          </div>
        </div>
      </div>
      
      <button class="btn-continue">
      <a href="user.php" class="btn btn-primary mt-3">
        <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
      </a>
        
      </button>
      
      <div class="confirmation-note">
        <i class="fas fa-envelope"></i> A confirmation has been sent to your email
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Add micro-interactions
    document.addEventListener('DOMContentLoaded', function() {
      const steps = document.querySelectorAll('.step');
      
      steps.forEach((step, index) => {
        // Animate each step with delay
        step.style.opacity = '0';
        step.style.transform = 'translateX(-10px)';
        step.style.animation = `fadeIn 0.4s ease-out forwards ${index * 0.15 + 0.4}s`;
        
        // Add hover effect
        step.addEventListener('mouseenter', () => {
          step.style.transform = 'translateX(4px)';
        });
        step.addEventListener('mouseleave', () => {
          step.style.transform = 'translateX(0)';
        });
      });
      
      // Make button pulse occasionally
      const btn = document.querySelector('.btn-continue');
      setInterval(() => {
        btn.style.transform = 'translateY(-2px) scale(1.02)';
        setTimeout(() => {
          btn.style.transform = 'translateY(-2px) scale(1)';
        }, 300);
      }, 8000);
    });
  </script>
</body>
</html>