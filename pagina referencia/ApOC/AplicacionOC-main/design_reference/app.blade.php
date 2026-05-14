<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Solicitudes de Viaje')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body, html { background: #f4f8fb; margin: 0; padding: 0; font-family: 'Montserrat', Arial, sans-serif; }
        .main-bg { display: flex; min-height: 100vh; }
        .sidebar {
            width: 80px;
            background: linear-gradient(180deg, #1976d2 0%, #2196f3 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        }
        .sidebar-logo { margin-bottom: 32px; }
        .sidebar-menu { list-style: none; padding: 0; margin: 0; width: 100%; }
        .sidebar-menu li { margin: 24px 0; text-align: center; }
        .sidebar-menu a {
            color: #fff;
            display: inline-block;
            width: 48px;
            height: 48px;
            line-height: 48px;
            border-radius: 16px;
            transition: background 0.2s;
            font-size: 28px;
        }
        .sidebar-menu a.active, .sidebar-menu a:hover {
            background: #fff2;
            color: #fff;
        }
        .main-content {
            flex: 1;
            padding: 0 0 32px 0;
            display: flex;
            flex-direction: column;
        }
        .header {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background: linear-gradient(90deg, #1976d2 60%, #42a5f5 100%);
            color: #fff;
            padding: 24px 40px 24px 40px;
            border-radius: 0 0 24px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .header-title { font-size: 2rem; font-weight: 700; margin-right: 16px; }
        .header-desc { font-size: 1.1rem; opacity: 0.8; }
        .welcome-card {
            background: #fff;
            margin: 32px 40px 16px 40px;
            border-radius: 24px;
            padding: 32px 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            font-size: 1.1rem;
            font-weight: 500;
            max-width: 700px;
        }
        .btn-ver {
            background: #1976d2;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .btn-ver:hover { background: #1565c0; }
    </style>
    @yield('head')
</head>
<body>
    @yield('content')
</body>
</html>
