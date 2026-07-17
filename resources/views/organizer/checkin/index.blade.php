@extends('layouts.organizer')

@section('content')
<div style="margin-bottom: var(--space-8);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-4);">
        <div>
            <h1 class="display" style="margin-bottom: var(--space-1); color: #1e293b;">Check-in Tiket</h1>
            <p class="body" style="color: #6b7280;">Scan QR Code tiket peserta untuk melakukan validasi.</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: var(--space-8); max-width: 600px; margin: 0 auto;">
    <div class="card" style="padding: var(--space-6); text-align: center;">
        <div id="reader-container" style="margin-bottom: var(--space-6); border-radius: var(--radius-lg); overflow: hidden; position: relative; border: 1px solid #e2e8f0; padding: var(--space-4);">
            <div id="reader" style="width: 100%; min-height: 300px;"></div>
        </div>

        <div style="margin-bottom: var(--space-6);">
            <p class="caption" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #6b7280; margin-bottom: var(--space-2);">Atau Masukkan Kode Tiket Manual</p>
            <div style="display: flex; gap: var(--space-3);">
                <input type="text" id="ticket-code-input" class="form-control" placeholder="TIX-..." style="flex: 1;" autocomplete="off">
                <button type="button" id="manual-submit-btn" class="btn btn-primary">Check-in</button>
            </div>
        </div>

        <div id="scan-result" style="display: none; padding: var(--space-4); border-radius: var(--radius-md); margin-top: var(--space-4); font-weight: 500; transition: all 0.3s ease;">
            <!-- Result message injected here -->
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- HTML5 QR Code Scanner Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resultContainer = document.getElementById('scan-result');
    const ticketInput = document.getElementById('ticket-code-input');
    const submitBtn = document.getElementById('manual-submit-btn');
    
    let isProcessing = false;

    // Show result message
    function showResult(success, message) {
        resultContainer.style.display = 'block';
        if (success) {
            resultContainer.style.backgroundColor = 'rgba(22, 163, 74, 0.1)';
            resultContainer.style.color = '#166534';
            resultContainer.style.border = '1px solid rgba(22, 163, 74, 0.2)';
            
            // Auto hide success after 3 seconds
            setTimeout(() => {
                resultContainer.style.display = 'none';
            }, 3000);
        } else {
            resultContainer.style.backgroundColor = 'rgba(220, 38, 38, 0.1)';
            resultContainer.style.color = '#b91c1c';
            resultContainer.style.border = '1px solid rgba(220, 38, 38, 0.2)';
        }
        resultContainer.innerHTML = message;
    }

    // Process Checkin API
    async function processCheckin(ticketCode) {
        if (isProcessing) return;
        isProcessing = true;
        
        // Show loading state
        showResult(true, 'Memproses tiket...');
        resultContainer.style.color = '#ca8a04';
        resultContainer.style.backgroundColor = 'rgba(202, 138, 4, 0.1)';
        resultContainer.style.border = '1px solid rgba(202, 138, 4, 0.2)';

        try {
            const response = await fetch('{{ route('organizer.api.checkin', request()->route('organization')) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ticket_code: ticketCode })
            });

            const data = await response.json();

            if (data.success) {
                showResult(true, `
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <strong>${data.message}</strong>
                        <span style="font-size: 13px; opacity: 0.8;">${data.ticket.event_name}</span>
                    </div>
                `);
                ticketInput.value = ''; // clear input
            } else {
                showResult(false, `
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <strong>Check-in Gagal</strong>
                        <span style="font-size: 13px; opacity: 0.8;">${data.message}</span>
                    </div>
                `);
            }
        } catch (error) {
            showResult(false, 'Terjadi kesalahan jaringan.');
        } finally {
            // Add a small delay before allowing next scan
            setTimeout(() => {
                isProcessing = false;
            }, 1500);
        }
    }

    // Manual checkin submit (Registered first so it always works)
    submitBtn.addEventListener('click', (e) => {
        const code = ticketInput.value.trim();
        if (code) {
            processCheckin(code);
        }
    });

    // Also support pressing Enter in the input field
    ticketInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitBtn.click();
        }
    });

    try {
        // QR Code Scanner config
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Use Html5QrcodeScanner which provides a built-in UI and handles permissions automatically
        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", config, /* verbose= */ false);
            
        html5QrcodeScanner.render(
            (decodedText, decodedResult) => {
                if(!isProcessing) {
                    processCheckin(decodedText);
                }
            },
            (errorMessage) => {
                // parse errors ignored for clean UX
            }
        );
        
        // Remove black background when scanner UI loads successfully (No longer needed, removed from CSS)
    } catch (e) {
        console.error("Scanner failed to initialize", e);
        document.getElementById('reader-container').innerHTML = `
            <div style="padding: 40px; color: #1e293b; display: flex; flex-direction: column; align-items: center; gap: 16px;">
                <p style="margin: 0; color: #ef4444;">Scanner gagal dimuat.</p>
                <p style="font-size: 12px; opacity: 0.7; margin: 0;">Silakan gunakan input manual di bawah.</p>
            </div>
        `;
    }
});
</script>
@endpush
