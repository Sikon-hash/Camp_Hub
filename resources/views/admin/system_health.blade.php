<!DOCTYPE html>
<html>
  <head> 
    @include('admin.css') 

    <style>
        .blink {
            animation: blinker 1.5s linear infinite;
        }
        @keyframes blinker {
            50% { opacity: 0; }
        }
        .security-card {
            text-align: center;
            padding: 40px;
            border: 4px solid #ddd;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 800px;
            background-color: #f8f9fa; /* Warna terang agar kontras dengan dark mode */
            color: #333;
        }
        .secure {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .compromised {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .btn-check {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
  </head>
  <body>
    
    @include('admin.header')

    <div class="d-flex align-items-stretch">
      
      @include('admin.sidebar')

      <div class="page-content">
        <div class="page-header">
          <div class="container-fluid">

            <h2 class="h5 no-margin-bottom">🛡️ Blockchain Security Dashboard</h2>
            
            <div class="security-card {{ $healthStatus['status'] === 'SECURE' ? 'secure' : 'compromised' }}">
                
                <h2 style="font-size: 24px; font-weight: bold;">SYSTEM INTEGRITY STATUS</h2>
                
                @if($healthStatus['status'] === 'SECURE')
                    <div style="font-size: 80px;">🛡️</div>
                    <h3 style="font-size: 40px; font-weight: bold; color: green;">SECURE</h3>
                    <p>{{ $healthStatus['message'] }}</p>
                @else
                    <div style="font-size: 80px;">🚨</div>
                    <h3 style="font-size: 40px; font-weight: bold; color: red;" class="blink">COMPROMISED</h3>
                    <p style="font-weight: bold;">{{ $healthStatus['message'] }}</p>
                    
                    <div style="text-align: left; margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.5); border-radius: 5px;">
                        <p><strong>Forensic Report:</strong></p>
                        <ul>
                            <li>Block ID: <strong>#{{ $healthStatus['compromised_block'] }}</strong></li>
                            <li>Detail: {{ $healthStatus['details'] }}</li>
                        </ul>
                    </div>
                @endif

                <a href="{{ url('system_health') }}" class="btn-check">
                    🔄 Re-Scan System
                </a>

            </div>

            </div>
        </div>
        
      </div>
    </div>
    
    <script src="{{ asset('admincss/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admincss/vendor/popper.js/umd/popper.min.js') }}"> </script>
    <script src="{{ asset('admincss/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admincss/vendor/jquery.cookie/jquery.cookie.js') }}"> </script>
    <script src="{{ asset('admincss/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('admincss/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admincss/js/charts-home.js') }}"></script>
    <script src="{{ asset('admincss/js/front.js') }}"></script>
  </body>
</html>