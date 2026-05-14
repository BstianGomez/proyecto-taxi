<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso no autorizado</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|dm-sans:400,500,700" rel="stylesheet" />
    <style>
        :root {
            --brand: #0f6bb6;
            --brand-2: #1b7dc8;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #dbe7f5;
            --card: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            font-family: "DM Sans", "Space Grotesk", ui-sans-serif, system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(900px 400px at 10% 0%, rgba(15, 107, 182, 0.12) 0%, transparent 60%),
                radial-gradient(800px 500px at 90% 100%, rgba(27, 125, 200, 0.10) 0%, transparent 58%),
                #f4f8fd;
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 20px;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }

        .card-top {
            height: 6px;
            background: linear-gradient(90deg, var(--brand), var(--brand-2));
        }

        .content {
            padding: 34px 30px 28px;
            text-align: center;
        }

        .code {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            border-radius: 18px;
            background: #e8f2ff;
            color: var(--brand);
            font-weight: 800;
            font-size: 26px;
            display: grid;
            place-items: center;
            letter-spacing: 1px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 29px;
            line-height: 1.1;
            font-family: "Space Grotesk", "DM Sans", sans-serif;
        }

        p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            line-height: 1.55;
        }

        .detail {
            margin-top: 18px;
            background: #f8fbff;
            border: 1px solid #dbeafe;
            color: #1e3a8a;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .actions {
            margin-top: 24px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 148px;
            height: 42px;
            padding: 0 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: all .18s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            color: #fff;
            box-shadow: 0 8px 18px rgba(15, 107, 182, 0.28);
        }

        .btn-primary:hover { transform: translateY(-1px); }

        .btn-secondary {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
        }

        .btn-secondary:hover {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <main class="card" role="main" aria-label="Error de autorización">
        <div class="card-top"></div>
        <section class="content">
            <div class="code">403</div>
            <h1>Acceso no autorizado</h1>
            <p>No tienes permisos para ingresar a esta sección del sistema.</p>

            @if(!empty($exception?->getMessage()))
                <div class="detail">{{ $exception->getMessage() }}</div>
            @endif

            <div class="actions">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
                <a href="{{ route('oc.index') }}" class="btn btn-primary">Ir al inicio</a>
            </div>
        </section>
    </main>
</body>
</html>
