<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - BUTOLOG' : 'BUTOLOG - Zdalna naprawa butów przez paczkomat'; ?></title>
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Profesjonalna naprawa obuwia na odległość. Wyślij buty przez paczkomat InPost i odbierz je naprawione w 24h. Darmowa wysyłka w obie strony.'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'naprawa butów, szewc, paczkomat, InPost, naprawa obuwia, zdalna naprawa'; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - BUTOLOG' : 'BUTOLOG - Zdalna naprawa butów przez paczkomat'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Profesjonalna naprawa obuwia na odległość. Wyślij buty przez paczkomat InPost i odbierz je naprawione w 24h.'; ?>">
    <meta property="og:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/images/butolog-logo-small.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - BUTOLOG' : 'BUTOLOG - Zdalna naprawa butów przez paczkomat'; ?>">
    <meta property="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Profesjonalna naprawa obuwia na odległość. Wyślij buty przez paczkomat InPost i odbierz je naprawione w 24h.'; ?>">
    <meta property="twitter:image" content="<?php echo 'https://' . $_SERVER['HTTP_HOST']; ?>/assets/images/butolog-logo-small.png">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/" class="logo-link">
                        <img src="/assets/images/butolog-logo-small.png" alt="BUTOLOG" class="logo-image">
                    </a>
                </div>

                <nav class="nav desktop-nav">
                    <a href="/#o-serwisie" class="nav-link">O serwisie</a>
                    <a href="/#proces" class="nav-link">Proces</a>
                    <a href="/#oferta" class="nav-link">Oferta</a>
                    <a href="/#wycena" class="nav-link">Wycena</a>
                    <a href="/#faq" class="nav-link">FAQ</a>
                    <a href="/#kontakt" class="nav-link">Kontakt</a>
                    <a href="/#wycena" class="btn btn-primary">Wyceń swoją naprawę</a>
                </nav>

                <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <nav class="mobile-nav" id="mobileNav">
                <a href="/#o-serwisie" class="nav-link" onclick="closeMobileMenu()">O serwisie</a>
                <a href="/#proces" class="nav-link" onclick="closeMobileMenu()">Proces</a>
                <a href="/#oferta" class="nav-link" onclick="closeMobileMenu()">Oferta</a>
                <a href="/#wycena" class="nav-link" onclick="closeMobileMenu()">Wycena</a>
                <a href="/#faq" class="nav-link" onclick="closeMobileMenu()">FAQ</a>
                <a href="/#kontakt" class="nav-link" onclick="closeMobileMenu()">Kontakt</a>
                <a href="/#wycena" class="btn btn-primary" onclick="closeMobileMenu()">Wyceń swoją naprawę</a>
            </nav>
        </div>
    </header>
