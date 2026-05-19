<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        @page { margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Lato', sans-serif;
            width: 297mm;
            height: 210mm;
            background: #fff;
        }

        .page {
            width: 297mm;
            height: 210mm;
            position: relative;
            background: #fdfaf4;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .frame-outer {
            position: absolute;
            inset: 6mm;
            border: 3px solid #b8952a;
        }
        .frame-inner {
            position: absolute;
            inset: 8.5mm;
            border: 1px solid #d4af5a;
        }
        .corner {
            position: absolute;
            width: 18mm;
            height: 18mm;
        }
        .corner svg { width: 100%; height: 100%; }
        .corner.tl { top: 4mm; left: 4mm; }
        .corner.tr { top: 4mm; right: 4mm; transform: scaleX(-1); }
        .corner.bl { bottom: 4mm; left: 4mm; transform: scaleY(-1); }
        .corner.br { bottom: 4mm; right: 4mm; transform: scale(-1); }

        .side-bar-left {
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 18mm;
            background: linear-gradient(180deg, #1a3a6e 0%, #2251cc 50%, #1a3a6e 100%);
        }
        .side-bar-right {
            position: absolute;
            right: 0; top: 0; bottom: 0;
            width: 18mm;
            background: linear-gradient(180deg, #1a3a6e 0%, #2251cc 50%, #1a3a6e 100%);
        }
        .side-bar-left::after,
        .side-bar-right::after {
            content: '';
            position: absolute;
            top: 0; bottom: 0;
            width: 3px;
            background: #b8952a;
        }
        .side-bar-left::after { right: 0; }
        .side-bar-right::after { left: 0; }

        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            width: 100%;
            padding: 0 30mm;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4mm;
            margin-bottom: 5mm;
        }
        .brand-icon {
            width: 11mm; height: 11mm;
            background: #1a3a6e;
            border-radius: 1.5mm;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-icon svg { width: 6mm; height: 6mm; fill: #d4af5a; }
        .brand-name {
            font-family: 'Cinzel', serif;
            font-size: 16pt;
            color: #1a3a6e;
            letter-spacing: 3px;
        }
        .divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3mm;
            margin: 4mm 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #b8952a, transparent);
            max-width: 60mm;
        }
        .divider-diamond {
            width: 3mm; height: 3mm;
            background: #b8952a;
            transform: rotate(45deg);
        }

        .cert-label {
            font-family: 'Cinzel', serif;
            font-size: 9pt;
            letter-spacing: 5px;
            color: #b8952a;
            text-transform: uppercase;
            margin-bottom: 5mm;
        }

        .awarded-text {
            font-size: 9pt;
            color: #6b7280;
            letter-spacing: 1px;
            font-weight: 300;
            margin-bottom: 2mm;
        }

        .student-name {
            font-family: 'Cinzel', serif;
            font-size: 28pt;
            font-weight: 700;
            color: #1a3a6e;
            line-height: 1.1;
            margin: 2mm 0 4mm;
        }

        .completing-text {
            font-size: 9pt;
            color: #6b7280;
            letter-spacing: 1px;
            font-weight: 300;
            margin-bottom: 2mm;
        }

        .course-title {
            font-family: 'Cinzel', serif;
            font-size: 15pt;
            font-weight: 700;
            color: #2251cc;
            margin-bottom: 2mm;
        }

        .professor-text {
            font-size: 8.5pt;
            color: #9ca3af;
            font-weight: 300;
        }
        .professor-text strong {
            color: #4b5563;
            font-weight: 700;
        }

        .footer {
            position: absolute;
            bottom: 13mm;
            left: 30mm;
            right: 30mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .sig-block { text-align: center; }
        .sig-name {
            font-size: 8.5pt;
            font-weight: 700;
            color: #1a3a6e;
            letter-spacing: 0.5px;
        }
        .sig-line {
            width: 38mm;
            height: 1px;
            background: #b8952a;
            margin: 4px auto 3px;
        }
        .sig-role {
            font-size: 7.5pt;
            color: #9ca3af;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .seal {
            width: 20mm; height: 20mm;
            border-radius: 50%;
            border: 2px solid #b8952a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1px;
        }
        .seal svg { width: 8mm; height: 8mm; fill: #b8952a; }
        .seal-text {
            font-size: 5.5pt;
            color: #b8952a;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .date-block { text-align: center; }
        .date-value {
            font-family: 'Cinzel', serif;
            font-size: 10pt;
            font-weight: 700;
            color: #1a3a6e;
        }
        .date-label {
            font-size: 7.5pt;
            color: #9ca3af;
            font-weight: 300;
            letter-spacing: 1px;
        }
        .score-value {
            font-size: 8pt;
            color: #2251cc;
            font-weight: 700;
            margin-top: 2px;
        }

        .cert-number {
            position: absolute;
            bottom: 7mm;
            left: 50%;
            transform: translateX(-50%);
            font-size: 7pt;
            color: #d1d5db;
            letter-spacing: 1px;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div class="page">

    <div class="side-bar-left"></div>
    <div class="side-bar-right"></div>

    <div class="frame-outer"></div>
    <div class="frame-inner"></div>
    <div class="corner tl">
        <svg viewBox="0 0 60 60">
            <path d="M2 2 L30 2 L30 6 L6 6 L6 30 L2 30 Z" fill="#b8952a"/>
            <path d="M2 2 L14 2 L2 14 Z" fill="#d4af5a"/>
            <circle cx="18" cy="18" r="3" fill="none" stroke="#b8952a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner tr">
        <svg viewBox="0 0 60 60">
            <path d="M2 2 L30 2 L30 6 L6 6 L6 30 L2 30 Z" fill="#b8952a"/>
            <path d="M2 2 L14 2 L2 14 Z" fill="#d4af5a"/>
            <circle cx="18" cy="18" r="3" fill="none" stroke="#b8952a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner bl">
        <svg viewBox="0 0 60 60">
            <path d="M2 2 L30 2 L30 6 L6 6 L6 30 L2 30 Z" fill="#b8952a"/>
            <path d="M2 2 L14 2 L2 14 Z" fill="#d4af5a"/>
            <circle cx="18" cy="18" r="3" fill="none" stroke="#b8952a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner br">
        <svg viewBox="0 0 60 60">
            <path d="M2 2 L30 2 L30 6 L6 6 L6 30 L2 30 Z" fill="#b8952a"/>
            <path d="M2 2 L14 2 L2 14 Z" fill="#d4af5a"/>
            <circle cx="18" cy="18" r="3" fill="none" stroke="#b8952a" stroke-width="1"/>
        </svg>
    </div>

    <div class="content">

        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
            </div>
            <span class="brand-name">EduLearn</span>
        </div>

        <div class="cert-label">Certificate of Completion</div>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-diamond"></div>
            <div class="divider-line"></div>
        </div>

        <div class="awarded-text">This certificate is proudly awarded to</div>

        <div class="student-name">
            {{ $certificate->student->name ?? 'Student Name' }}
        </div>

        <div class="completing-text">in recognition of successfully completing</div>

        <div class="course-title">
            {{ $certificate->course->title ?? 'Course Title' }}
        </div>

        <div class="professor-text">
            Instructed by <strong>{{ $certificate->course->professor->name ?? 'Professor' }}</strong>
            &nbsp;·&nbsp; Online Learning Platform
        </div>

    </div>

    <div class="footer">
        <div class="sig-block">
            <div class="sig-name">EduLearn Director</div>
            <div class="sig-line"></div>
            <div class="sig-role">Authorized Signature</div>
        </div>

        <div class="seal">
            <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
            <div class="seal-text">VERIFIED</div>
        </div>

        <div class="date-block">
            <div class="date-value">
                {{ optional($certificate->issued_at)->format('d M Y') }}
            </div>
            <div class="date-label">Date of Issue</div>
            <div class="score-value">Score: {{ $certificate->score ?? 0 }}%</div>
        </div>
    </div>

    <div class="cert-number">
        Certificate No: {{ $certificate->certificate_number ?? '000000' }}
    </div>

</div>
</body>
</html>