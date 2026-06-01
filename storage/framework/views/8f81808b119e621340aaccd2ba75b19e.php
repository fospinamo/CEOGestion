<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1A4B8E">
    <title>Ingresar - CEOGestion</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='80' font-size='80'>📊</text></svg>">
    <style>
        * { margin: 0 !important; padding: 0 !important; box-sizing: border-box !important; }
        html, body { width: 100% !important; height: 100% !important; }
        body { 
            font-family: 'Segoe UI', sans-serif !important;
            background: linear-gradient(135deg, #1A4B8E 0%, #0D2A54 100%) !important;
            min-height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            overflow-x: hidden !important;
        }
        
        .container-login {
            width: 100% !important;
            max-width: 448px !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .card-login {
            width: 100% !important;
            background-color: white !important;
            border-radius: 12px !important;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15) !important;
            padding: 2rem !important;
            overflow: hidden !important;
        }
        
        .header-logos {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 1.5rem !important;
            margin-bottom: 1.5rem !important;
            flex-wrap: wrap !important;
            width: 100% !important;
        }
        
        .logo-container {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }
        
        .logo-empresa {
            max-width: 80px !important;
            max-height: 80px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        }
        
        .logo-ceo {
            max-width: 60px !important;
            max-height: 60px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
        }
        
        .header-title {
            text-align: center !important;
            margin-bottom: 2rem !important;
        }
        
        .header-title h1 {
            font-size: 1.875rem !important;
            font-weight: 700 !important;
            color: #0D2A54 !important;
            margin: 0 !important;
        }
        
        .header-title p {
            color: #666 !important;
            font-size: 0.875rem !important;
            margin: 0 !important;
        }
        
        .form-group {
            margin-bottom: 1rem !important;
            width: 100% !important;
        }
        
        .form-group label {
            display: block !important;
            color: #333 !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            margin-bottom: 0.5rem !important;
        }
        
        .form-group input {
            width: 100% !important;
            padding: 0.5rem 1rem !important;
            border: 2px solid #d1d5db !important;
            border-radius: 8px !important;
            font-size: 1rem !important;
            font-family: 'Segoe UI', sans-serif !important;
        }
        
        .form-group input:focus {
            border-color: #1A4B8E !important;
            outline: none !important;
            box-shadow: 0 0 5px rgba(26, 75, 142, 0.3) !important;
        }
        
        .submit-btn {
            width: 100% !important;
            background: linear-gradient(90deg, #1A4B8E 0%, #0D2A54 100%) !important;
            color: white !important;
            font-weight: 700 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 8px !important;
            border: none !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            font-size: 1rem !important;
            margin-bottom: 1.5rem !important;
        }
        
        .submit-btn:hover {
            background: linear-gradient(90deg, #1540A3 0%, #08193A 100%) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
        }
        
        .divider {
            display: flex !important;
            align-items: center !important;
            margin: 1.5rem 0 !important;
        }
        
        .divider-line {
            flex: 1 !important;
            border-top: 2px solid #d1d5db !important;
        }
        
        .divider-text {
            padding: 0 0.75rem !important;
            color: #999 !important;
            font-size: 0.875rem !important;
        }
        
        .credentials-box {
            background-color: #f0f6ff !important;
            border-left: 4px solid #2E7DFF !important;
            padding: 1rem !important;
            border-radius: 4px !important;
            margin-bottom: 1rem !important;
        }
        
        .credentials-box p {
            margin: 0.5rem 0 !important;
            font-size: 0.875rem !important;
            color: #333 !important;
        }
        
        .credentials-box p strong {
            font-weight: 700 !important;
        }
        
        .register-link {
            text-align: center !important;
            margin-top: 1.5rem !important;
        }
        
        .register-link p {
            color: #666 !important;
            font-size: 0.875rem !important;
        }
        
        .register-link a {
            color: #1A4B8E !important;
            font-weight: 700 !important;
            text-decoration: none !important;
        }
        
        .register-link a:hover {
            text-decoration: underline !important;
        }
        
        .footer {
            text-align: center !important;
            margin-top: 1.5rem !important;
            color: white !important;
            font-size: 0.875rem !important;
            width: 100% !important;
            max-width: 448px !important;
            padding: 0 1rem !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .footer p {
            margin: 0 !important;
            word-break: break-word !important;
        }
        
        .error-box {
            background-color: #fee !important;
            border-left: 4px solid #dc2626 !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
            border-radius: 4px !important;
        }
        
        .error-box p {
            color: #dc2626 !important;
            font-size: 0.875rem !important;
            margin: 0.25rem 0 !important;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #1A4B8E 0%, #0D2A54 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow-x: hidden; padding: 2rem 1rem;">
    <div class="container-login">
        <!-- Card Login -->
        <div class="card-login">
            <!-- Header con Logos y Nombres -->
            <div class="header-title">
                <div class="header-logos">
                    <!-- Logo de la Empresa -->
                    <?php if($empresa && $empresa->logo): ?>
                        <div class="logo-container">
                            <img src="<?php echo e(asset($empresa->logo)); ?>" alt="<?php echo e($empresa->nombre); ?>" class="logo-empresa">
                            <span style="font-size: 0.75rem; color: #666;"><?php echo e($empresa->nombre); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="logo-container">
                            <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 2rem; color: #ccc;">🏢</span>
                            </div>
                            <span style="font-size: 0.75rem; color: #666;">
                                <?php if($empresa): ?>
                                    <?php echo e($empresa->nombre); ?>

                                <?php else: ?>
                                    Empresa
                                <?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Logo de CEO Gestion -->
                    <div class="logo-container">
                        <img src="<?php echo e(asset('images/playstore.png')); ?>" alt="Logo CEOGestion" class="logo-ceo" style="width: 60px; height: 60px;">
                        <span style="font-size: 0.75rem; color: #666;">CEOGestion</span>
                    </div>
                </div>
                
                <h1 style="font-size: 1.875rem; font-weight: 700; color: #0D2A54; margin: 1rem 0 0 0;">CEOGestion</h1>
                <p style="color: #666; font-size: 0.875rem; margin: 0.25rem 0 0 0;">Sistema de Gestión Empresarial</p>
            </div>

            <!-- Errores -->
            <?php if($errors->any()): ?>
                <div class="error-box">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form method="POST" action="<?php echo e(route('login.store')); ?>" style="width: 100%;">
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        value="<?php echo e(old('email')); ?>"
                        placeholder="tu@email.com"
                    >
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label>Contraseña</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        placeholder="••••••••"
                    >
                </div>

                <!-- Submit -->
                <button type="submit" class="submit-btn">
                    Ingresar
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">O</span>
                <div class="divider-line"></div>
            </div>

            <!-- Demo Credentials -->
            <div class="credentials-box">
                <p><strong>Credenciales de Demostración:</strong></p>
                <p>📧 Email: admin@ceogestion.com</p>
                <p>🔐 Contraseña: password123</p>
            </div>

            <!-- Register Link -->
            <div class="register-link">
                <p>
                    ¿No tienes cuenta? 
                    <a href="<?php echo e(route('register', [], false)); ?>">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>CEO Soluciones © 2026</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CEOGestion\resources\views/auth/login.blade.php ENDPATH**/ ?>