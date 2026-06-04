@extends('layout.owner.app')

@section('content')
    <div class="bg-linear-to-r from-primary-700 to-primary-900 rounded-2xl shadow-xl p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Laporan Pendapatan</h1>
                <p class="text-primary-100">Ringkasan pendapatan mingguan, bulanan, dan tahunan.</p>
            </div>
            <form method="GET" class="bg-white/10 rounded-xl p-4 flex flex-col md:flex-row md:items-end gap-3">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-primary-100 mb-1">Minggu Terakhir</label>
                    <input type="number" name="weeks" min="4" max="52" value="{{ $filters['weeks'] }}" required
                        class="w-full md:w-32 px-3 py-2 rounded-lg border border-white/20 bg-white/20 text-white placeholder-primary-100 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all" />
                    <p class="text-xs text-primary-200 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Menampilkan pendapatan N minggu terakhir (4-52 minggu)
                    </p>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-primary-100 mb-1">Tahun Bulanan</label>
                    <input type="number" name="year" min="2000" max="{{ now()->year + 1 }}" value="{{ $filters['year'] }}" required
                        class="w-full md:w-32 px-3 py-2 rounded-lg border border-white/20 bg-white/20 text-white placeholder-primary-100 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all" />
                    <p class="text-xs text-primary-200 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Menampilkan pendapatan bulanan pada tahun yang dipilih
                    </p>
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-primary-100 mb-1">Rentang Tahun</label>
                    <input type="number" name="years" min="3" max="10" value="{{ $filters['years'] }}" required
                        class="w-full md:w-32 px-3 py-2 rounded-lg border border-white/20 bg-white/20 text-white placeholder-primary-100 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all" />
                    <p class="text-xs text-primary-200 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Menampilkan pendapatan tahunan selama N tahun terakhir (3-10 tahun)
                    </p>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-white text-primary-700 rounded-lg font-semibold hover:bg-primary-50 transition-all shadow-lg hover:shadow-xl">
                    Terapkan
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Minggu Ini</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($summary['week'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($summary['month'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-md p-6">
            <p class="text-gray-500 text-sm font-medium mb-1">Total Tahun Ini</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($summary['year'], 0, ',', '.') }}</p>
        </div>
    </div>

    <section class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Pendapatan Mingguan</h2>
                <p class="text-sm text-gray-500">Rentang: {{ $weekly['range'] }}</p>
            </div>
            <!-- <div class="flex flex-wrap gap-2">
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'weekly', 'format' => 'csv', 'weeks' => $filters['weeks']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-gray-100 rounded-lg hover:bg-gray-200">
                    Export CSV
                </a>
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'weekly', 'format' => 'pdf', 'weeks' => $filters['weeks']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Export PDF
                </a>
            </div> -->
        </div>
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <canvas id="weeklyChart" height="240"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Periode</th>
                            <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($weekly['rows'] as $row)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $row['label'] }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Pendapatan Bulanan</h2>
                <p class="text-sm text-gray-500">Tahun: {{ $monthly['year'] }}</p>
            </div>
            <!-- <div class="flex flex-wrap gap-2">
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'monthly', 'format' => 'csv', 'year' => $filters['year']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-gray-100 rounded-lg hover:bg-gray-200">
                    Export CSV
                </a>
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'monthly', 'format' => 'pdf', 'year' => $filters['year']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Export PDF
                </a>
            </div> -->
        </div>
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <canvas id="monthlyChart" height="240"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Periode</th>
                            <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($monthly['rows'] as $row)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $row['label'] }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="mb-4">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Pendapatan Tahunan</h2>
                <p class="text-sm text-gray-500">Rentang: {{ $yearly['range'] }}</p>
            </div>
            <!-- <div class="flex flex-wrap gap-2">
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'yearly', 'format' => 'csv', 'years' => $filters['years']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-gray-100 rounded-lg hover:bg-gray-200">
                    Export CSV
                </a>
                <a href="{{ route('owner.laporan-pendapatan.export', ['type' => 'yearly', 'format' => 'pdf', 'years' => $filters['years']]) }}"
                    class="px-3 py-2 text-sm font-medium bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Export PDF
                </a>
            </div> -->
        </div>
        <div class="grid lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <canvas id="yearlyChart" height="240"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-600">Periode</th>
                            <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($yearly['rows'] as $row)
                            <tr>
                                <td class="px-4 py-3 text-gray-700">{{ $row['label'] }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const weeklyLabels = @json($weekly['labels']);
            const weeklyTotals = @json($weekly['totals']);
            const monthlyLabels = @json($monthly['labels']);
            const monthlyTotals = @json($monthly['totals']);
            const yearlyLabels = @json($yearly['labels']);
            const yearlyTotals = @json($yearly['totals']);

            const weeklyCtx = document.getElementById('weeklyChart');
            const monthlyCtx = document.getElementById('monthlyChart');
            const yearlyCtx = document.getElementById('yearlyChart');

            if (weeklyCtx) {
                new Chart(weeklyCtx, {
                    type: 'bar',
                    data: {
                        labels: weeklyLabels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: weeklyTotals,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            if (monthlyCtx) {
                new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: monthlyTotals,
                            backgroundColor: 'rgba(16, 185, 129, 0.3)',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 2,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            if (yearlyCtx) {
                new Chart(yearlyCtx, {
                    type: 'bar',
                    data: {
                        labels: yearlyLabels,
                        datasets: [{
                            label: 'Pendapatan (Rp)',
                            data: yearlyTotals,
                            backgroundColor: 'rgba(234, 88, 12, 0.4)',
                            borderColor: 'rgb(234, 88, 12)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
