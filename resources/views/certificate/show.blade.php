<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .certificate-wrapper {
            width: 900px;
            height: 620px;
            background: #ffffff;
            background-image: url('https://www.transparenttextures.com/patterns/cream-paper.png');
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            box-sizing: border-box;
        }

        .border-outer {
            border: 4px solid #991b1b;
            height: 100%;
            padding: 10px;
            box-sizing: border-box;
        }

        .border-inner {
            border: 1px solid #d4af37;
            height: 100%;
            padding: 40px 30px 20px 30px;
            box-sizing: border-box;
            position: relative;
            text-align: center;
        }

        /* Decorative corners */
        .corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 3px solid #d4af37;
        }
        .corner-tl { top: -2px; left: -2px; border-right: none; border-bottom: none; }
        .corner-tr { top: -2px; right: -2px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: -2px; left: -2px; border-right: none; border-top: none; }
        .corner-br { bottom: -2px; right: -2px; border-left: none; border-top: none; }

        .logo-container {
            margin-bottom: 25px;
        }
        
        .logo-container img {
            height: 60px;
            object-fit: contain;
        }

        .header-title {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 700;
            color: #991b1b;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 0 0 15px 0;
        }

        .subtitle {
            font-size: 16px;
            color: #4b5563;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .recipient-name {
            font-family: 'Playfair Display', serif;
            font-size: 46px;
            font-weight: 700;
            font-style: italic;
            color: #111827;
            margin: 0 auto 20px auto;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding: 0 40px 10px 40px;
        }

        .completion-text {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 10px;
        }

        .course-name {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: #991b1b;
            margin: 0 0 35px 0;
        }

        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding: 0 30px;
        }

        .footer-block {
            width: 200px;
            text-align: center;
        }

        .date-text {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
            border-bottom: 1px solid #9ca3af;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .footer-label {
            font-size: 13px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .qr-container {
            text-align: center;
        }

        .qr-container img {
            border: 2px solid #d4af37;
            padding: 4px;
            background: white;
            border-radius: 5px;
            width: 80px;
            height: 80px;
            transform: translateY(-15px);
        }

        .cert-number {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #9ca3af;
            font-family: monospace;
            letter-spacing: 1px;
        }

        @media print {
            body { background: none; }
            .certificate-wrapper { box-shadow: none; border: none; }
            @page { margin: 0; size: landscape; }
        }
    </style>
</head>
<body>

    <div class="certificate-wrapper">
        <div class="border-outer">
            <div class="border-inner">
                <!-- Golden Corners -->
                <div class="corner corner-tl"></div>
                <div class="corner corner-tr"></div>
                <div class="corner corner-bl"></div>
                <div class="corner corner-br"></div>

                @php $setting = \App\Models\Setting::first(); @endphp
                
                <div class="logo-container">
                    @if ($setting && $setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo">
                    @else
                        <h2 style="margin:0; color:#991b1b; font-family:'Playfair Display', serif;">{{ $setting->app_name ?? 'Nihongo Gakuen' }}</h2>
                    @endif
                </div>

                <h1 class="header-title">Sertifikat Kelulusan</h1>
                <div class="subtitle">DIBERIKAN DENGAN BANGGA KEPADA</div>
                
                <div class="recipient-name">{{ $certificate->user->name }}</div>
                
                <div class="completion-text">Atas dedikasi dan keberhasilannya dalam menyelesaikan program kursus:</div>
                <h2 class="course-name">{{ $certificate->course->title }}</h2>

                <div class="footer-section">
                    <div class="footer-block">
                        <div class="date-text">{{ $certificate->issued_at->format('d F Y') }}</div>
                        <div class="footer-label">Tanggal Diterbitkan</div>
                    </div>
                    
                    <div class="qr-container">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(route('certificate.show', $certificate)) }}" alt="QR Code Signature">
                    </div>

                    <div class="footer-block">
                        <div class="date-text">{{ $certificate->course->instructor->name ?? 'Instruktur Utama' }}</div>
                        <div class="footer-label">Instruktur Kursus</div>
                    </div>
                </div>

                <div class="cert-number">
                    ID: {{ $certificate->certificate_number }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>
