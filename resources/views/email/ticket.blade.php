<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - AmikomEventHub</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            background-color: #f3f4f6; 
            margin: 0; 
            padding: 40px 20px; 
            color: #0f172a; 
        }
        .container { 
            max-width: 500px; 
            margin: 0 auto; 
            width: 100%;
        }
        .header-text { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        .header-text h1 { 
            font-size: 32px; 
            font-weight: 900; 
            margin: 0 0 10px 0; 
            text-transform: uppercase;
            letter-spacing: -1px;
            color: #000000;
        }
        .header-text p { 
            font-weight: 700;
            color: #000000;
            margin: 0; 
            font-size: 16px;
        }
        .ticket-card { 
            background-color: #ffffff; 
            color: #000000;
            border: 4px solid #000000;
            box-shadow: 8px 8px 0 #000000;
            overflow: hidden; 
        }
        .ticket-top { 
            background-color: #f59e0b; 
            padding: 30px;
            text-align: center; 
            border-bottom: 4px solid #000000; 
        }
        .ticket-top p { 
            color: #000000; 
            font-size: 14px; 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin: 0 0 10px 0; 
            background-color: #ffffff;
            display: inline-block;
            padding: 4px 12px;
            border: 2px solid #000000;
        }
        .ticket-top h2 { 
            font-size: 28px; 
            font-weight: 900; 
            margin: 10px 0 0 0; 
            text-transform: uppercase;
        }
        .ticket-body { 
            padding: 30px; 
        }
        .grid { 
            display: block; 
            width: 100%; 
            margin-bottom: 20px; 
        }
        .grid-item { 
            display: inline-block; 
            width: 45%; 
            vertical-align: top; 
            margin-bottom: 20px; 
        }
        .label { 
            color: #000000; 
            font-size: 12px; 
            font-weight: 900;
            text-transform: uppercase; 
            margin: 0 0 5px 0; 
            background-color: #14b8a6;
            display: inline-block;
            padding: 2px 6px;
        }
        .value { 
            font-weight: 700; 
            font-size: 16px; 
            margin: 0; 
            text-transform: uppercase;
        }
        .qr-section { 
            background-color: #ffffff; 
            padding: 25px;
            border: 4px solid #000000; 
            text-align: center; 
            margin-top: 10px; 
            box-shadow: 4px 4px 0 #000000;
        }
        .qr-container { 
            background-color: white; 
            padding: 10px;
            border: 2px solid #000000; 
            display: inline-block; 
            margin-bottom: 15px; 
        }
        .footer { 
            text-align: center; 
            padding: 0 30px 30px 30px;
            color: #000000; 
            font-size: 14px; 
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Success Banner -->
        <div class="header-text">
            <h1>Pembayaran Berhasil!</h1>
            <p>Tiket Anda telah terbit dan siap digunakan.</p>
        </div>
        <!-- Ticket Card -->
        <div class="ticket-card">
            <!-- Ticket Header -->
            <div class="ticket-top">
                <p>E-TICKET RESMI</p>
                <h2>{{ $transaction->event->title }}</h2>
            </div>
            <!-- Ticket Body -->
            <div class="ticket-body">
                <div class="grid">
                    <div class="grid-item">
                        <p class="label">NAMA PEMBELI</p>
                        <p class="value">{{ $transaction->customer_name }}</p>
                    </div>
                    <div class="grid-item">
                        <p class="label">TANGGAL & WAKTU</p>
                        <p class="value">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="grid-item">
                        <p class="label">ORDER ID</p>
                        <p class="value">{{ $transaction->order_id }}</p>
                    </div>
                    <div class="grid-item">
                        <p class="label">LOKASI</p>
                        <p class="value">{{ $transaction->event->location }}</p>
                    </div>
                </div>
                <div class="qr-section">
                    <p class="label" style="margin-bottom: 15px; background-color: #f43f5e; color: #fff;">SCAN QR UNTUK CHECK-IN</p><br>
                    <div class="qr-container">
                        <img src="https://api.qrserver.com/v1/create-qrcode/?size=150x150&data={{ urlencode($transaction->order_id) }}" alt="QR Code" width="150" height="150" style="display: block;">
                    </div>
                    <p style="margin: 0; font-family: monospace; font-size: 18px; font-weight: 900; color: #000000; text-transform: uppercase;">{{ $transaction->order_id }}</p>
                </div>
            </div>
            <div class="footer">
                <p>MOHON TUNJUKKAN E-TICKET INI SAAT MEMASUKI AREA ACARA.</p>
                <p style="margin-top: 10px;">&copy; {{ date('Y') }} AMIKOMEVENTHUB.</p>
            </div>
        </div>
    </div>
</body>
</html>