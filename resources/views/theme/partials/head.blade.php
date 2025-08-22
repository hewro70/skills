<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>مهارات هب</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/skill-logo-design-template-6677debd608907e81c75e20c66e95baf_screen.jpg?ts=1685817469"
        rel="icon">
    <link
        href="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/skill-logo-design-template-6677debd608907e81c75e20c66e95baf_screen.jpg?ts=1685817469"
        rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap-grid.rtl.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap-grid.rtl.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap-utilities.rtl.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap-utilities.rtl.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.rtl.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap/css/bootstrap-reboot.rtl.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/aos/aos.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="{{ asset('assets') }}/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- ============================== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    {{-- ============================== --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">


    <!-- Main CSS File -->
    <link href="{{ asset('assets') }}/css/main.css" rel="stylesheet">

    <style>
        #faqAccordion {
            color: #000 !important;
        }
    </style>

    <style>
        #google-anchor {
            color: #0D6EFD;
            border: 1px solid #0D6EFD;
        }

        #google-anchor:hover {
            background-color: #0D6EFD;
            color: white;
        }
    </style>

    <style>
        @media (max-width: 768px) {
            .dropdown-menu.show {
                position: absolute !important;
                transform: none !important;
                inset: auto !important;
            }
        }
    </style>

    <style>
        /* Dropdown styles */
        .dropdown-menu {
            text-align: right;
            direction: rtl;
        }

        .dropdown-item {
            text-align: right;
            padding: 0.25rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: #0D6EFD;
        }

        .avatar-placeholder {
            font-weight: bold;
        }
    </style>

    <style>
        /* Form validation styles */
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback.d-block {
            display: block !important;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
            text-align: right;
            direction: rtl;
        }

        /* Google button styles */
        .btn-outline-danger {
            border-color: #db4437;
            color: #db4437;
        }

        .btn-outline-danger:hover {
            background-color: #db4437;
            color: white;
        }
    </style>

    <style>
        /* Search Container */
        .search-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .tab-button {
            padding: 10px 20px;
            background: #f0f0f0;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .tab-button.active {
            background: #3498db;
            color: white;
        }

        /* Updated Search Styles */
        .search-wrapper {
            width: 100%;
        }

        .search-bar {
            display: flex;
            position: relative;
        }

        .search-input {
            flex: 1;
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 15px 160px 15px 20px;
            /* Increased right padding */
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: #fff;
            transition: all 0.3s ease;
            text-align: right;
            /* RTL text alignment */
            direction: rtl;
            /* RTL direction */
        }

        .search-input input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .search-button {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translate(7%, -50%);
            background: rgb(13, 110, 253);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
            min-width: 120px;
            /* Ensure consistent button width */
        }

        .search-button:hover {
            background: #2980b9;
        }

        .search-button i {
            font-size: 18px;
        }

        .quick-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            color: #666;
            font-size: 14px;
        }

        .quick-link {
            color: #3498db;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .quick-link:hover {
            background: rgba(52, 152, 219, 0.1);
            text-decoration: underline;
        }

        /* RTL Adjustments */
        .search-button {
            left: auto;
            right: 10px;
            flex-direction: row-reverse;
        }

        .search-input input {
            padding: 15px 160px 15px 20px;
            /* Adjusted for RTL */
        }

        .quick-links {
            justify-content: flex-end;
            direction: rtl;
        }

        .quick-links span {
            margin-right: 0;
            margin-left: 8px;
        }
    </style>


    <style>
        .accordion-button::after {
            transition: transform 0.3s ease;
        }

        .accordion-button.collapsed::after {
            transform: rotate(0deg);
        }

        .accordion-button:not(.collapsed)::after {
            transform: rotate(180deg);
            /* Arrow down to up */
        }

        .accordion-button {
            color: #3498db !important;
            font-size: 1rem !important;
            font-weight: bold !important;
        }

        /* #3498db */
    </style>

    <style>
        /* Add this to your existing styles */
        .form-control,
        .form-select,
        .form-check-input {
            text-align: right;
            direction: rtl;
        }

        .invalid-feedback {
            text-align: right;
        }

        .form-check {
            padding-right: 1.5em;
            padding-left: 0;
        }

        .form-check-input {
            float: right;
            margin-right: -1.5em;
            margin-left: 0;
        }

        /* Adjust floating labels if you use them */
        .form-floating>label {
            right: 0;
            left: auto;
            transform-origin: right top;
        }

        .form-floating>.form-control:focus~label,
        .form-floating>.form-control:not(:placeholder-shown)~label,
        .form-floating>.form-select~label {
            transform: scale(0.85) translateY(-0.5rem) translateX(-1.15rem);
        }
    </style>

    <style>
        /* User dropdown styles */
        .dropdown-menu {
            text-align: right;
            direction: rtl;
        }

        .avatar-placeholder {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Adjust dropdown positioning for RTL */
        .dropdown-menu-end {
            right: auto !important;
            left: 0 !important;
        }

        /* Make sure dropdown items have proper RTL alignment */
        .dropdown-item {
            text-align: right;
            padding-right: 1rem;
            padding-left: 1.5rem;
        }

        /* Adjust caret position for RTL */
        .dropdown-toggle::after {
            margin-right: 0.255em;
            margin-left: 0;
        }
    </style>

    {{-- This is for Why Skills Hub Section --}}
    <style>
        /* Why Skills Hub Section Styles */
        .why-skills-hub {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .feature-box {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            text-align: right;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .feature-list {
            list-style-type: none;
            padding-right: 0;
        }

        .feature-list li {
            position: relative;
            padding-right: 20px;
            margin-bottom: 8px;
        }

        .feature-list li:before {
            content: "•";
            color: #3498db;
            font-weight: bold;
            position: absolute;
            right: 0;
        }

        /* RTL Adjustments */
        .feature-box h3 {
            text-align: right;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .feature-box p {
            text-align: right;
            color: #7f8c8d;
        }
    </style>

    {{-- How It Works Section Styles --}}
    <style>
        .how-it-works {
            background-color: #fff;
            padding: 80px 0;
        }

        .step-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(252, 100, 100, 0.05);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .step-box:hover {
            transform: translateY(-10px);
        }

        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        /* RTL Adjustments */
        .step-box h3 {
            text-align: center;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .step-box p {
            text-align: center;
            color: #7f8c8d;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 18px;
            border-radius: 50px;
        }
    </style>

    <style>
        /* Updated Growing Community Section Styles */
        .community {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        #row-community-id {
            display: flex;
            flex-direction: row-reverse;
        }

        #img-fluid {
            height: 50%
        }

        .community .icon-box {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background-color: transparent;
            color: #3498db;
        }

        .community-features {
            border-left: 3px solid #3498db;
            padding-left: 20px;
        }

        .feature-item {
            text-align: right;
            align-items: flex-start;
        }

        .feature-item .icon-box {
            margin-right: 0;
            margin-left: 1rem;
            margin-top: 2px;
        }

        .feature-text {
            color: #495057;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* RTL Adjustments */
        .community-features {
            border-right: none;
        }

        @media (max-width: 991.98px) {
            .community .row>div {
                order: initial !important;
            }

            .community-features {
                margin-top: 30px;
            }
        }
    </style>

    {{-- Skills styles --}}

    <style>
        /* Base RTL Styles */
        .rtl {
            direction: rtl;
            text-align: right;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-left: 1px solid #e9ecef;
        }

        .sidebar-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .filter-subtitle {
            font-size: 1rem;
            font-weight: 600;
            margin: 15px 0 10px;
            color: #3498db;
        }

        .filter-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .filter-list li {
            margin-bottom: 8px;
        }

        .filter-list li a {
            color: #495057;
            text-decoration: none;
            transition: color 0.2s;
        }

        .filter-list li a:hover {
            color: #3498db;
        }

        /* Talent Card Styles */
        .talent-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 4px 2px 4px rgba(0, 0, 0, 0.09);
        }

        .talent-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .talent-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 15px;
        }

        .talent-info h4 {
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .talent-info p {
            font-size: 0.9rem;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .talent-location {
            font-size: 0.8rem;
            color: #95a5a6;
        }

        .talent-description {
            margin: 15px 0;
            color: #495057;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .talent-jobs {
            font-size: 0.85rem;
            color: #3498db;
            margin-top: 10px;
        }

        .talent-stats {
            display: flex;
            gap: 15px;
            margin: 10px 0;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .talent-stats i {
            color: #f39c12;
            margin-left: 5px;
        }

        .talent-skills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 15px 0;
        }

        .skill-tag {
            background-color: #e8f4fc;
            color: #3498db;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        /* Talent Actions */
        .talent-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-outline-primary {
            border: 1px solid #3498db;
            color: #3498db;
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: #3498db;
            color: white;
        }

        /* Form Check Styles */
        .form-check {
            padding-right: 1.5em;
            padding-left: 0;
        }

        .form-check-input {
            margin-right: -1.5em;
            margin-left: 0;
        }

        /* Main Content Styles */
        .main-content {
            padding: 30px;
        }

        .search-container {
            margin-bottom: 30px;
        }

        .input-group {
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border: none;
            padding: 12px 20px;
        }

        .btn-search {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .sort-options {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-select {
            border-radius: 20px;
            padding: 8px 15px;
            border-color: #ddd;
        }

        .settings-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .settings-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .settings-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .settings-list li {
            margin-bottom: 8px;
            color: #495057;
        }

        .btn-view-profile {
            width: 100%;
            background-color: #f8f9fa;
            color: #3498db;
            border: 1px solid #3498db;
            padding: 8px;
            border-radius: 5px;
            margin-top: 15px;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .sidebar {
                border-left: none;
                border-bottom: 1px solid #e9ecef;
            }

            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }

            .talent-header {
                flex-direction: column;
                text-align: center;
            }

            .talent-avatar {
                margin-left: 0;
                margin-bottom: 15px;
            }

            .talent-stats {
                flex-direction: column;
                gap: 5px;
            }

            .talent-actions {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>

    <style>
        /* Trainer Search Styles */
        .trainer-search-container {
            background: #fff;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
        }

        .trainer-search-group {
            position: relative;
            direction: rtl;
            width: 100%;
        }

        .trainer-search-input {
            padding: 0.75rem 3.5rem 0.75rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 20px !important;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-align: right;
            width: 100%;
            box-sizing: border-box;
        }

        .trainer-search-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        #trainer-search-btn {
            border-radius: 2rem;
            background-color: rgb(13, 110, 253);
        }

        .trainer-search-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(10%);
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            height: calc(100% - 12px);
        }

        .trainer-search-btn:hover {
            background: #2980b9;
        }

        .search-tags {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            direction: rtl;
            width: 100%;
        }

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            flex-grow: 1;
        }

        .tag {
            background: #f8f9fa;
            color: #3498db;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .tag:hover {
            background: #e9f5ff;
            color: #1a6bac;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .trainer-search-input {
                padding-right: 2.5rem;
                font-size: 0.9rem;
                padding-left: 0.75rem;
            }

            .trainer-search-btn {
                padding: 0.5rem;
                width: auto;
            }

            .trainer-search-btn span {
                display: none;
            }

            .search-tags span {
                display: none;
            }

            .tags-container {
                justify-content: center;
                width: 100%;
            }
        }

        @media (max-width: 400px) {
            .tag {
                padding: 0.25rem 0.6rem;
                font-size: 0.75rem;
            }
        }
    </style>


    <style>
        /* Contact Form Styles */
        #contact {
            width: 100%;
            margin-top: 5rem;
        }

        .php-email-form {
            background: #fff;
            box-shadow: 0 0 24px 0 rgba(0, 0, 0, 0.12);
        }

        /* Form Control Styles (existing) */
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem;
        }

        html[dir="rtl"] select.form-control {
            background-position: left 1rem center;
            padding-right: 1rem;
            padding-left: 2.5rem;
        }

        /* Submit Button Styles */
        .php-email-form button[type="submit"] {
            background: #1977cc;
            border: 0;
            padding: 10px 24px;
            color: #fff;
            transition: 0.4s;
            border-radius: 4px;
        }

        .php-email-form button[type="submit"]:hover {
            background: #1c84e3;
        }
    </style>

    {{-- Paginations Skills --}}

    <style>
        /* Custom Pagination Styles */
        .pagination {
            font-size: 0.875rem;
        }

        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            min-width: 2rem;
            text-align: center;
            margin: 0 2px;
            border-radius: 4px;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            min-width: auto;
            padding: 0.25rem 0.5rem;
        }

        /* RTL specific styles */
        .rtl .pagination {
            direction: rtl;
        }

        .rtl .pagination .page-item:first-child .page-link {
            border-radius: 0 4px 4px 0;
        }

        .rtl .pagination .page-item:last-child .page-link {
            border-radius: 4px 0 0 4px;
        }
    </style>


    <style>
        #invitation-count {
            min-width: 16px;
            height: 16px;
            font-size: 0.65rem;
            padding: 0 4px;
            line-height: 16px;
        }
    </style>

</head>
