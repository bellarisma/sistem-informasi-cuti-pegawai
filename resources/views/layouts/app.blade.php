<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            background:#f4f7fc;
            overflow-x: hidden;
        }

        .wrapper{
            display:flex;
            min-height:100vh;
            position: relative;
        }

        /* SIDEBAR */
        .sidebar{
            width:240px;
            background:linear-gradient(180deg, #2563eb, #1d4ed8);
            color:white;
            position:fixed;
            height:100vh;
            padding:25px;
            transition: all 0.3s ease;
            z-index: 999;
            left: 0;
        }

        .sidebar.closed {
            left: -240px;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .logo{
            font-size:20px;
            font-weight:700;
            line-height: 1.2;
        }

        /* TOMBOL TOGGLE DI DALAM SIDEBAR */
        .toggle-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            color: white;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* TOMBOL FLOATING (Saat sidebar closed) */
        .floating-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #2563eb;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            box-shadow: 0 3px 10px rgba(37, 99, 235, 0.3);
            display: none;
            z-index: 998;
            transition: 0.3s;
        }

        .floating-toggle:hover {
            background: #1d4ed8;
        }

        .menu{
            list-style:none;
        }

        .menu li{
            margin-bottom:10px;
        }

        .menu a{
            display:flex; 
            align-items:center;
            gap:12px; 
            color:white;
            text-decoration:none;
            padding:12px 15px;
            border-radius:10px;
            transition:.3s;
        }

        .menu a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .menu a:hover{
            background:rgba(255,255,255,.15);
        }

        /* CONTENT */
        .content{
            flex:1;
            margin-left:240px;
            padding:30px;
            transition: all 0.3s ease;
            width: calc(100% - 240px);
        }

        .content.full-width {
            margin-left: 0;
            width: 100%;
        }

        /* STYLE FORM */
        .form-line {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .form-line label {
            width: 120px;
            font-weight: 500;
            color: #475569;
        }

        .form-line .input-field {
            flex: 1;
            max-width: 400px;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            outline: none;
            font-size: 14px;
        }

        .form-line .input-field:focus {
            border-color: #2563eb;
        }

        .btn-custom {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: #94a3b8; color: white; }
        .btn-secondary:hover { background: #64748b; }

        /* NEW: TOMBOL AKSI HITAM PUTIH MINIMALIS & LEBIH KECIL */
        .btn-action {
            background: #ffffff;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 5px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-action-danger:hover {
            background: #fef2f2;
            color: #ef4444;
            border-color: #fca5a5;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .topbar, .table-box {
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 3px 10px rgba(0,0,0,.05);
            margin-bottom:25px;
        }

        .topbar h2{ color:#1e293b; }
        .topbar p{ color:#64748b; margin-top:5px; }

        /* CARDS STYLING */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }

        .card h3 {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .card h1 {
            font-size: 32px;
            color: #1e293b;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="wrapper">

    <button class="floating-toggle" id="floatingToggle" title="Buka Sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">EMS System</div>
            <button class="toggle-btn" id="closeSidebar" title="Tutup Sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <ul class="menu">
    <li><a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
    
    {{-- KHUSUS ADMIN: Menu Pegawai dikunci di sini --}}
    @if(auth()->user()->role == 'admin')
    <li><a href="{{ route('pegawai.index') }}"><i class="fa-solid fa-users"></i> Pegawai</a></li>
    @endif
    
    {{-- Bisa diakses Admin maupun Pegawai --}}
    <li><a href="{{ route('cuti.index') }}"><i class="fa-solid fa-calendar-check"></i> Cuti</a></li>
    
    {{-- KHUSUS ADMIN: Menu Laporan --}}
    @if(auth()->user()->role == 'admin')
    <li><a href="{{ route('laporan.index') }}"><i class="fa-solid fa-file-invoice"></i> Laporan</a></li>
    @endif
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside>

    <div class="content" id="content">
        @yield('content')
    </div>

</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const closeSidebar = document.getElementById('closeSidebar');
    const floatingToggle = document.getElementById('floatingToggle');

    closeSidebar.addEventListener('click', () => {
        sidebar.classList.add('closed');
        content.classList.add('full-width');
        floatingToggle.style.display = 'block';
    });

    floatingToggle.addEventListener('click', () => {
        sidebar.classList.remove('closed');
        content.classList.remove('full-width');
        floatingToggle.style.display = 'none';
    });
</script>

@yield('scripts')

</body>
</html>