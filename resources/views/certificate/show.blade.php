<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: 'Georgia', serif;
        }
        .certificate-container {
            width: 800px;
            height: 550px;
            background-color: white;
            padding: 40px;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
            border: 15px solid #004d40;
            box-sizing: border-box;
        }
        .inner-border {
            border: 2px solid #004d40;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
        }
        .header {
            font-size: 36px;
            font-weight: bold;
            color: #004d40;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }
        .sub-header {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }
        .student-name {
            font-size: 42px;
            font-weight: bold;
            font-style: italic;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            display: inline-block;
            padding: 0 40px 10px;
        }
        .course-title {
            font-size: 24px;
            font-weight: bold;
            color: #004d40;
            margin: 20px 0 40px;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            font-size: 14px;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            padding-top: 5px;
            text-align: center;
        }
        .cert-number {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #777;
            font-family: monospace;
        }
        @media print {
            body { background: none; }
            .certificate-container { box-shadow: none; }
            @page { margin: 0; size: landscape; }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="inner-border">
            <div class="header">Sertifikat Kelulusan</div>
            <div class="sub-header">Diberikan dengan bangga kepada:</div>
            
            <div class="student-name">{{ $certificate->user->name }}</div>
            
            <div class="sub-header">Atas penyelesaian yang luar biasa pada kursus:</div>
            <div class="course-title">{{ $certificate->course->title }}</div>
            
            <div class="footer">
                <div style="text-align: left;">
                    <p>Diterbitkan tanggal:<br>
                    <strong>{{ $certificate->issued_at->format('d F Y') }}</strong></p>
                </div>
                <div>
                    <div class="signature-line">
                        <strong>{{ $certificate->course->instructor->name ?? 'Instruktur' }}</strong><br>
                        Instruktur Kursus
                    </div>
                </div>
            </div>
            
            <div class="cert-number">
                No. Sertifikat: {{ $certificate->certificate_number }}
            </div>
        </div>
    </div>
    
    <script>
        // Opsional: otomatis buka dialog print saat halaman dimuat
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
