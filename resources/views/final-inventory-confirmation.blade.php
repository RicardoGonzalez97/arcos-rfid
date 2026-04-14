@extends('layouts.dashboard')

@section('content')
@vite('resources/css/final-inventory.css')

<div class="-m-5 min-w-0 flex-1 overflow-y-auto bg-white px-5 py-5 xl:pr-6">
    <div class="mb-7 flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="max-w-4xl">
            <div class="mb-2 flex items-center gap-3 text-[11px] font-semibold tracking-[-0.02em] text-slate-500">
                <span>Receiving Sessions</span>
                <span class="text-xl leading-none text-slate-400">›</span>
                <span class="text-slate-800">Session #SES-2024-0082</span>
            </div>

            <h1 class="mb-2 text-[27px] font-bold leading-[1.05] tracking-[-0.045em] text-slate-900 xl:text-[29px]">
                Final Inventory Confirmation
            </h1>
            <p class="max-w-4xl text-[13px] leading-[1.45] tracking-[-0.02em] text-slate-500">
                Review aggregated results from Portal Gate 04 before committing to the central warehouse ledger.
            </p>
        </div>

        <div class="flex flex-wrap gap-3 pt-0 xl:pt-2">
            <button type="button" class="inline-flex h-12 min-w-[170px] items-center justify-center gap-3 rounded-2xl border border-slate-300 bg-white px-5 text-[11px] font-semibold tracking-[-0.02em] text-slate-800 shadow-sm">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 shrink-0">
                    <path d="M6 9V3h12v6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M6 18H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M7 14h10v7H7z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
                <span>Print Report</span>
            </button>

            <button type="button" class="inline-flex h-12 min-w-[170px] items-center justify-center gap-3 rounded-2xl border border-slate-300 bg-white px-5 text-[11px] font-semibold tracking-[-0.02em] text-slate-800 shadow-sm">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 shrink-0">
                    <path d="M8 3h6l5 5v12a1 1 0 0 1-1 1H8a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M14 3v5h5" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M9 13h6M9 17h6M9 9h2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                </svg>
                <span>Export CSV</span>
            </button>
        </div>
    </div>

    <div class="mb-8 grid max-w-[838px] grid-cols-1 gap-8 xl:grid-cols-3">
        <article class="relative h-[132px] w-full rounded-[12px] bg-[#F0F7FE] px-7 py-7 shadow-[0_2px_4px_rgba(46,49,56,0.08)] xl:w-[258px]">
            <div class="absolute right-7 top-7 text-[#2f8cf4]">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
            </div>
            <p class="mb-3 text-[11px] font-bold tracking-[0.08em] text-slate-500">TOTAL SCANNED</p>
            <div class="mb-2 text-[32px] font-bold leading-none tracking-[-0.05em] text-[#2f8cf4]">450</div>
            <p class="text-[11px] leading-5 tracking-[-0.02em] text-slate-500">Unique RFID signals detected</p>
        </article>

        <article class="relative h-[132px] w-full rounded-[12px] bg-[#F1FBF6] px-7 py-7 shadow-[0_2px_4px_rgba(46,49,56,0.08)] xl:w-[258px]">
            <div class="absolute right-7 top-7 text-white/95">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                    <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/>
                    <path d="m8.5 12.3 2.3 2.4 4.8-5.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
            </div>
            <p class="mb-3 text-[11px] font-bold tracking-[0.08em] text-slate-500">VERIFIED VALID</p>
            <div class="mb-2 text-[32px] font-bold leading-none tracking-[-0.05em] text-white">442</div>
            <p class="text-[11px] leading-5 tracking-[-0.02em] text-slate-500">Matched existing SKU records</p>
        </article>

        <article class="relative h-[132px] w-full rounded-[12px] bg-[#FFF4F4] px-7 py-7 shadow-[0_2px_4px_rgba(46,49,56,0.08)] xl:w-[258px]">
            <div class="absolute right-7 top-7 text-[#ff4d4d]">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                    <circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M12 7.8v5.4M12 16.4h.01" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                </svg>
            </div>
            <p class="mb-3 text-[11px] font-bold tracking-[0.08em] text-slate-500">PENDING ERRORS</p>
            <div class="mb-2 text-[32px] font-bold leading-none tracking-[-0.05em] text-[#ef4e4e]">2</div>
            <p class="text-[11px] leading-5 tracking-[-0.02em] text-slate-500">Requires manual override</p>
        </article>
    </div>

    <section class="overflow-hidden rounded-[22px] border border-slate-200 bg-white shadow-[0_10px_30px_rgba(17,24,39,0.05)]">
        <div class="flex flex-col p-4 gap-4 border-b border-slate-200  lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="mb-1 text-[15px] font-bold leading-tight tracking-[-0.03em] text-slate-900">Session Product Breakdown</h2>
                <p class="text-[11px] leading-7 tracking-[-0.02em] text-slate-500">
                    Consolidated list of all items processed in this session.
                </p>
            </div>

            <span class="inline-flex min-h-[34px] items-center justify-center rounded-full border border-slate-300 bg-white px-4 text-[11px] font-semibold text-slate-800">
                5 Item Groups Total
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[980px] w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-2 text-left text-[11px] font-bold text-slate-500">SKU</th>
                        <th class="px-6 py-2 text-left text-[11px] font-bold text-slate-500">Product Name</th>
                        <th class="px-5 py-2 text-center text-[11px] font-bold text-slate-500">Expected</th>
                        <th class="px-5 py-2 text-center text-[11px] font-bold text-slate-500">Scanned</th>
                        <th class="px-5 py-2 text-center text-[11px] font-bold text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-t border-slate-100 px-6 py-4 text-[11px] text-slate-500">RFID-1029</td>
                        <td class="border-t border-slate-100 px-6 py-4 text-[12px] font-semibold tracking-[-0.02em] text-slate-900">Precision Industrial Drill v2</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] text-slate-900">120</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] font-bold text-slate-900">120</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center">
                            <span class="inline-flex min-h-[28px] min-w-[96px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Complete</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-t border-slate-100 px-6 py-4 text-[11px] text-slate-500">RFID-4452</td>
                        <td class="border-t border-slate-100 px-6 py-4 text-[12px] font-semibold tracking-[-0.02em] text-slate-900">Hydraulic Seals (Pack of 50)</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] text-slate-900">200</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] font-bold text-slate-900">198</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center">
                            <span class="inline-flex min-h-[28px] min-w-[96px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Partial</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-t border-slate-100 px-6 py-4 text-[11px] text-slate-500">RFID-8812</td>
                        <td class="border-t border-slate-100 px-6 py-4 text-[12px] font-semibold tracking-[-0.02em] text-slate-900">Lithium-Ion Battery Module</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] text-slate-900">80</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] font-bold text-slate-900">82</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center">
                            <span class="inline-flex min-h-[28px] min-w-[96px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Over Scan</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-t border-slate-100 px-6 py-4 text-[11px] text-slate-500">RFID-0092</td>
                        <td class="border-t border-slate-100 px-6 py-4 text-[12px] font-semibold tracking-[-0.02em] text-slate-900">Safety Goggles - Polarized</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] text-slate-900">50</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] font-bold text-slate-900">50</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center">
                            <span class="inline-flex min-h-[28px] min-w-[96px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Complete</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-t border-slate-100 px-6 py-4 text-[11px] text-slate-500">RFID-7721</td>
                        <td class="border-t border-slate-100 px-6 py-4 text-[12px] font-semibold tracking-[-0.02em] text-slate-900">Unknown RFID Tag (Auto-Flagged)</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] text-slate-900">0</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center text-[12px] font-bold text-slate-900">2</td>
                        <td class="border-t border-slate-100 px-5 py-4 text-center">
                            <span class="inline-flex min-h-[28px] min-w-[82px] items-center justify-center rounded-full border border-[#ef4e4e] bg-[#ef4e4e] px-3 text-[11px] font-semibold text-white">Error</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 bg-gradient-to-b from-white to-slate-50 px-7 py-10">
            <div class="mb-5 flex flex-wrap items-center justify-center gap-6">
                <button type="button" class="border-0 bg-transparent px-0 py-2 text-[11px] font-semibold tracking-[-0.02em] text-slate-500">Discard Session</button>
                <button type="button" class="inline-flex h-14 w-full max-w-[380px] items-center justify-center gap-4 rounded-2xl border-0 bg-[#2f8cf4] px-8 text-[13px] font-bold tracking-[-0.03em] text-white shadow-[0_12px_22px_rgba(47,140,244,0.24)]">
                    <span>Commit to Inventory</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5">
                        <path d="M5 12h14M13 5l7 7-7 7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </button>
            </div>

            <p class="m-0 text-center text-[11px] leading-7 tracking-[-0.02em] text-slate-500">
                By clicking commit, you are authorizing the addition of 450 units to the ERP inventory module.
            </p>
        </div>
    </section>
</div>
@endsection
