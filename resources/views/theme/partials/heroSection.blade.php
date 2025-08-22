<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مهارات هب - صفحة المهارات</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --border-radius: 12px;
            --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }

        /* Page Title Styles */
        .page-title {
            margin: 0;
            padding: 0;
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .page-title .heading {
            position: relative;
            text-align: center;
            color: #fff;
            padding: clamp(70px, 10vw, 120px) 0 clamp(50px, 6vw, 80px);
            border-radius: 0 0 24px 24px;
            box-shadow: 0 12px 30px rgba(2, 6, 23, 0.12);
            background: linear-gradient(135deg, #4a90e2 0%, #5fa8ff 30%, #81c4ff 100%);
            overflow: hidden;
        }

        .page-title .heading::before {
            content: "";
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.35) 0%, rgba(0, 0, 0, 0.18) 60%, rgba(0, 0, 0, 0.08) 100%);
        }

        .page-title .heading .container {
            position: relative;
            z-index: 2;
        }

        .page-title h1 {
            font-weight: 800;
            font-size: clamp(2rem, 4vw, 3rem);
            margin-bottom: 16px;
            letter-spacing: 0.5px;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .page-title p {
            color: rgba(255, 255, 255, 0.95);
            font-size: clamp(1.1rem, 1.5vw, 1.3rem);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
            font-weight: 400;
        }

        /* Breadcrumbs */
        .page-title .breadcrumbs {
            background: #fff;
            padding: 16px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .page-title .breadcrumbs ol {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .page-title .breadcrumbs li {
            font-size: 0.95rem;
            font-weight: 500;
            color: #64748b;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .page-title .breadcrumbs li:not(:last-child)::after {
            content: "›";
            margin: 0 8px;
            color: #cbd5e1;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .page-title .breadcrumbs li a {
            color: #334155;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            padding: 4px 8px;
            border-radius: 6px;
        }

        .page-title .breadcrumbs li a:hover {
            color: var(--primary-color);
            background: rgba(67, 97, 238, 0.08);
        }

        .page-title .breadcrumbs li.current {
            color: var(--primary-color);
            font-weight: 700;
            background: rgba(67, 97, 238, 0.12);
            padding: 4px 12px;
            border-radius: 8px;
        }

        /* Content Styles */
        .content-section {
            padding: 60px 0;
        }

        .content-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 30px;
            margin-bottom: 30px;
            transition: var(--transition);
        }

        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .content-card h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 700;
        }

        /* Animation for page title */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title .heading {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .page-title .heading {
                border-radius: 0 0 16px 16px;
            }
            
            .page-title .breadcrumbs ol {
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Page Title -->
    <div class="page-title" data-aos="fade">
        <div class="heading">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1>المهارات المتاحة</h1>
                        <p class="mb-0">اكتشف مجموعة واسعة من المهارات التي نقدمها لمساعدتك في تحقيق أهدافك وتطوير قدراتك</p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="breadcrumbs">
            <div class="container">
                <ol>
                    <li><a href="{{ route('theme.index') }}">الرئيسية</a></li>
                    <li class="current">المهارات</li>
                </ol>
            </div>
        </nav>
    </div>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="content-card">
                        <h3><i class="fas fa-laptop-code me-2"></i> مهارات التكنولوجيا</h3>
                        <p>تعلم أحدث تقنيات البرمجة وتطوير الويب وتصميم تجربة المستخدم من خلال دوراتنا المتخصصة في مجال التكنولوجيا.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-card">
                        <h3><i class="fas fa-chart-line me-2"></i> مهارات الأعمال</h3>
                        <p>طور مهاراتك في إدارة الأعمال، التسويق، والقيادة من خلال برامجنا المصممة خصيصاً للمحترفين.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-card">
                        <h3><i class="fas fa-palette me-2"></i> مهارات إبداعية</h3>
                        <p>اكتشف شغفك الإبداعي من خلال دوراتنا في التصميم الجرافيكي، التصوير الفوتوغرافي، والكتابة الإبداعية.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="content-card">
                        <h3><i class="fas fa-language me-2"></i> مهارات لغوية</h3>
                        <p>تعلم لغات جديدة وحسن مهاراتك اللغوية مع مدرسين مختصين وبرامج تعلم تفاعلية مصممة لتحقيق أفضل النتائج.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Simple animation for page elements
        document.addEventListener('DOMContentLoaded', function() {
            const contentCards = document.querySelectorAll('.content-card');
            
            contentCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 200 + (index * 100));
            });
        });
    </script>
</body>
</html>