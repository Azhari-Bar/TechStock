@extends('layouts.admin')

@section('title', 'Detail Peminjaman')
@section('heading', 'Detail Peminjaman')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6 mb-6">
        <div class="flex flex-wrap justify-between gap-3 mb-4">
            <div>
                <h2 class="text-xl font-bold">{{ $rental->reference_number }}</h2>
                <p class="text-sm text-slate-500">Diajukan: {{ $rental->created_at?->format('d M Y H:i') }}</p>
            </div>
            <div class="flex gap-2">
                <span class="rounded-full px-3 py-1 text-xs font-medium bg-{{ $rental->status->color() }}-100 text-{{ $rental->status->color() }}-800">{{ $rental->status->label() }}</span>
                <a href="{{ route('admin.rentals.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Kembali</a>
            </div>
        </div>

        <dl class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm mb-6">
            <div><dt class="text-slate-500">Nama</dt><dd>{{ $rental->customer_name }}</dd></div>
            <div><dt class="text-slate-500">Telepon</dt><dd>{{ $rental->customer_phone }}</dd></div>
            <div><dt class="text-slate-500">Email</dt><dd>{{ $rental->customer_email ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Keperluan</dt><dd class="prose prose-sm max-w-none">{{ $rental->purpose ?? '—' }}</dd></div>
            <div><dt class="text-slate-500">Mulai</dt><dd>{{ $rental->start_date?->format('d M Y') }}</dd></div>
            <div><dt class="text-slate-500">Jatuh Tempo</dt><dd>{{ $rental->due_date?->format('d M Y') }}</dd></div>
            <div><dt class="text-slate-500">Perkiraan Biaya</dt><dd class="font-bold text-indigo-600">Rp {{ number_format($rental->estimated_amount, 0, ',', '.') }}</dd></div>
            <div><dt class="text-slate-500">Deposit</dt><dd>Rp {{ number_format($rental->deposit_amount, 0, ',', '.') }}</dd></div>
            <div><dt class="text-slate-500">Unit</dt><dd>{{ $rental->unit?->serial_number ?? 'Belum dialokasikan' }}</dd></div>
        </dl>

        <div class="border-t border-slate-100 pt-4 space-y-3">
            <h3 class="font-semibold text-slate-800">Action (Admin)</h3>
            <div class="flex flex-wrap gap-2">
                @if($rental->status->value === 'menunggu')
                    <form id="approve-form" method="POST" action="{{ route('admin.rentals.approve', $rental) }}" class="flex flex-wrap items-end gap-2">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Unit</label>
                            <select name="product_unit_id" required class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                @forelse($availableUnits as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->serial_number }}</option>
                                @empty
                                    <option value="">Tidak ada unit tersedia</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1">Deposit (Rp)</label>
                            <input type="number" name="deposit_amount" min="0" step="1000" value="{{ old('deposit_amount', 0) }}" required class="w-36 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        </div>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Setujui & Alokasikan</button>
                    </form>
                    <form id="cancel-form" method="POST" action="{{ route('admin.rentals.cancel', $rental) }}" onsubmit="return confirm('Batalkan peminjaman ini?')">@csrf<button type="submit" class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Tolak/Batalkan</button></form>
                @endif
                @if($rental->status->value === 'disetujui')
                    <form id="handover-form" method="POST" action="{{ route('admin.rentals.handover', $rental) }}">@csrf<button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Selesaikan Serah Terima</button></form>
                    <form id="cancel-form" method="POST" action="{{ route('admin.rentals.cancel', $rental) }}" onsubmit="return confirm('Batalkan peminjaman ini?')">@csrf<button type="submit" class="rounded-lg border border-red-300 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Batalkan</button></form>
                @endif
                @if(in_array($rental->status->value, ['dipinjam', 'terlambat']))
                    <form id="return-form" method="POST" action="{{ route('admin.rentals.return', $rental) }}">@csrf<button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Proses Pengembalian</button></form>
                @endif
                @if(! $rental->status->isFinal())
                    <form id="cancel-form" method="POST" action="{{ route('admin.rentals.cancel', $rental) }}" onsubmit="return confirm('Batalkan peminjaman ini?')">@csrf<button type="submit" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">Batal (Tanpa Denda)</button></form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-3">Riwayat Pembayaran</h3>
            @forelse($rental->payments as $payment)
                <div class="border-b border-slate-100 py-3 last:border-0">
                    <div class="flex justify-between">
                        <span>{{ $payment->payment_type->label() }} — Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                        <span class="text-xs {{ match($payment->status->value) { 'pending' => 'text-yellow-700', 'verified' => 'text-emerald-700', default => 'text-red-700' } }}">{{ $payment->status->label() }}</span>
                    </div>
                    <p class="text-xs text-slate-500">{{ $payment->payment_method }} · @if($payment->proof_path)<a href="{{ asset('storage/'.$payment->proof_path) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat bukti</a>@else - @endif</p>
                </div>
            @empty
                <p class="text-sm text-slate-500">Belum ada pembayaran.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-3">Dokumen Identitas</h3>
            @if($rental->identity_document_path)
                <img src="{{ asset('storage/'.$rental->identity_document_path) }}" class="w-full h-48 object-cover rounded-lg">
                <a href="{{ asset('storage/'.$rental->identity_document_path) }}" target="_blank" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">Buka dokumen identitas</a>
            @else
                <p class="text-sm text-slate-500">Tidak ada dokumen identitas.</p>
            @endif
        </div>
    </div>
</div>
@endsection