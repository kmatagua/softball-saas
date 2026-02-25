<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Softball SaaS</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #111827; /* gris oscuro elegante */
            color: #f3f4f6;
        }

        .navbar {
            background-color: #0f172a !important;
        }

        .card {
            background-color: #1f2937;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .card,
        .card h1,
        .card h2,
        .card h3,
        .card h4,
        .card h5,
        .card h6,
        .card p,
        .card small,
        .card span {
            color: #f3f4f6 !important;
        }

        .list-group-item {
            background-color: #1f2937;
            color: #f3f4f6;
            border-color: #374151;
        }

        .text-muted {
            color: #9ca3af !important;
        }

        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .btn-success {
            background-color: #16a34a;
            border-color: #16a34a;
        }

        .btn-success:hover {
            background-color: #15803d;
            border-color: #15803d;
        }

        hr {
            border-color: #374151;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                Softball SaaS
            </a>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>