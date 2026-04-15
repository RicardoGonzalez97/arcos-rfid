@extends('layouts.dashboard')

@section('content')
@vite('resources/css/final-inventory.css')

<div class="-m-5 min-w-0 flex-1 overflow-y-auto bg-white px-5 py-5 xl:pr-6">
    <div class="mb-8 flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
        <div class="max-w-4xl">
            <div class="mb-2 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.18em] text-[#2f8cf4]">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                    <path d="M12 8V3m0 0-3 3m3-3 3 3M4 12a8 8 0 1 0 8-8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
                <span>Audit Trail</span>
            </div>

            <h1 class="mb-2 text-[31px] font-bold leading-[1.05] tracking-[-0.045em] text-slate-900">
                Session History
            </h1>
            <p class="max-w-4xl text-[13px] leading-[1.45] tracking-[-0.02em] text-slate-500">
                Comprehensive log of all RFID portal activities and intake events.
            </p>
        </div>

        <div class="flex flex-wrap gap-3 pt-0 xl:pt-1">
            <button type="button" class="inline-flex h-12 min-w-[160px] items-center justify-center gap-3 rounded-2xl border border-slate-300 bg-white px-5 text-[11px] font-semibold tracking-[-0.02em] text-slate-800 shadow-sm">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 shrink-0">
                    <path d="M7 3v3M17 3v3M4 9h16M6 5h12a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
                <span>Last 30 Days</span>
            </button>

            <button type="button" class="inline-flex h-12 min-w-[158px] items-center justify-center gap-3 rounded-2xl border border-[#2f8cf4] bg-[#2f8cf4] px-5 text-[11px] font-semibold tracking-[-0.02em] text-white shadow-sm">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 shrink-0">
                    <path d="M12 4v10m0 0 4-4m-4 4-4-4M5 18v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                </svg>
                <span>Export CSV</span>
            </button>
        </div>
    </div>

    <div class="mb-8 grid grid-cols-1 gap-5 xl:grid-cols-4">
        <article class="rounded-[18px] border border-slate-100 bg-white px-7 py-6 shadow-[0_4px_18px_rgba(15,23,42,0.07)]">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-[14px] bg-[#e8f1ff] text-[#2f8cf4]">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                        <path d="M12 8V3m0 0-3 3m3-3 3 3M4 12a8 8 0 1 0 8-8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-500">Total Sessions</p>
                    <div class="text-[32px] font-bold leading-none tracking-[-0.05em] text-slate-900">1,284</div>
                </div>
            </div>
        </article>

        <article class="rounded-[18px] border border-slate-100 bg-white px-7 py-6 shadow-[0_4px_18px_rgba(15,23,42,0.07)]">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center text-slate-900">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                        <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <path d="m9.4 12.2 1.8 1.9 3.7-4.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-500">Success Rate</p>
                    <div class="text-[32px] font-bold leading-none tracking-[-0.05em] text-slate-900">98.2%</div>
                </div>
            </div>
        </article>

        <article class="rounded-[18px] border border-slate-100 bg-white px-7 py-6 shadow-[0_4px_18px_rgba(15,23,42,0.07)]">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-[14px] bg-[#fff0f0] text-[#ef4e4e]">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                        <path d="M12 4.8 4.8 18.2a1 1 0 0 0 .88 1.48h12.64a1 1 0 0 0 .88-1.48L12 4.8Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                        <path d="M12 9.2v4.2M12 16.5h.01" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-500">Alerts Flagged</p>
                    <div class="text-[32px] font-bold leading-none tracking-[-0.05em] text-slate-900">24</div>
                </div>
            </div>
        </article>

        <article class="rounded-[18px] border border-slate-100 bg-white px-7 py-6 shadow-[0_4px_18px_rgba(15,23,42,0.07)]">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center text-slate-900">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-7 w-7">
                        <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </div>
                <div>
                    <p class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-slate-500">Avg. Intake Time</p>
                    <div class="text-[32px] font-bold leading-none tracking-[-0.05em] text-slate-900">18m 45s</div>
                </div>
            </div>
        </article>
    </div>

    <section class="mb-8 rounded-[22px] border border-slate-100 bg-white p-5 shadow-[0_4px_18px_rgba(15,23,42,0.05)]">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center">
            <div class="relative flex-1">
                <svg viewBox="0 0 24 24" aria-hidden="true" class="pointer-events-none absolute left-5 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-slate-400">
                    <circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                    <path d="m16 16 4 4" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.8"/>
                </svg>
                <input type="text" value="Filter by Session ID or Operator Name..." class="h-14 w-full rounded-2xl border border-slate-300 bg-white pl-[52px] pr-5 text-[11px] font-medium text-slate-500 outline-none" readonly>
            </div>

            <div class="flex flex-wrap items-center gap-4 xl:flex-nowrap">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-14 items-center whitespace-nowrap text-[11px] font-bold uppercase tracking-[0.04em] text-slate-600">Warehouse:</span>
                    <div class="relative flex items-center">
                        <select class="h-14 min-w-[205px] appearance-none rounded-2xl border border-slate-300 bg-white px-5 pr-11 text-[11px] font-medium text-slate-800 outline-none">
                            <option>All Locations</option>
                        </select>
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="pointer-events-none absolute right-4 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-slate-600">
                            <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex h-14 items-center whitespace-nowrap text-[11px] font-bold uppercase tracking-[0.04em] text-slate-600">Status:</span>
                    <div class="relative flex items-center">
                        <select class="h-14 min-w-[170px] appearance-none rounded-2xl border border-slate-300 bg-white px-5 pr-11 text-[11px] font-medium text-slate-800 outline-none">
                            <option>Any Status</option>
                        </select>
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="pointer-events-none absolute right-4 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-slate-600">
                            <path d="m6 9 6 6 6-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                        </svg>
                    </div>
                </div>

                <button type="button" class="inline-flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-300 bg-white text-slate-800 shadow-sm">
                    <svg viewBox="0 0 24 24" aria-hidden="true" class="h-[18px] w-[18px]">
                        <path d="M4 5h16l-6 7v6l-4-2v-4L4 5Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[22px] border border-slate-100 bg-white shadow-[0_6px_22px_rgba(15,23,42,0.06)]">
        <div class="overflow-x-auto">
            <table class="min-w-[1120px] w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Session ID</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Timestamp &amp; Duration</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Location / Gate</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Operator</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Load Size</th>
                        <th class="px-6 py-4 text-left text-[11px] font-bold uppercase tracking-[0.05em] text-slate-500">Status</th>
                        <th class="w-[60px] px-4 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-t border-slate-200 px-6 py-4"><span class="text-[12px] font-bold text-[#2f8cf4]">SES-9402</span></td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">2024-05-24 09:15 AM</div>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span>14m 22s</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">North Logistics Hub</div>
                            <div class="mt-1 text-[11px] font-bold text-[#5da7ff]">GATE-04</div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pravatar.cc/40?img=12" alt="Marcus Chen" class="h-10 w-10 rounded-full">
                                <span class="text-[12px] font-semibold text-slate-900">Marcus Chen</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-2 text-[12px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 text-slate-500">
                                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900">412</strong> tags</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <span class="inline-flex min-h-[30px] min-w-[94px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Complete</span>
                        </td>
                        <td class="border-t border-slate-200 px-4 py-4 text-center">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="mx-auto h-5 w-5 text-slate-900">
                                <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </td>
                    </tr>

                    <tr>
                        <td class="border-t border-slate-200 px-6 py-4"><span class="text-[12px] font-bold text-[#2f8cf4]">SES-9398</span></td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">2024-05-24 08:45 AM</div>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span>08m 10s</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">West Distribution Center</div>
                            <div class="mt-1 text-[11px] font-bold text-[#5da7ff]">GATE-01</div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pravatar.cc/40?img=32" alt="Sarah Jenkins" class="h-10 w-10 rounded-full">
                                <span class="text-[12px] font-semibold text-slate-900">Sarah Jenkins</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-2 text-[12px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 text-slate-500">
                                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900">128</strong> tags</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <span class="inline-flex min-h-[30px] min-w-[108px] items-center justify-center rounded-full border border-[#ffb7b7] bg-[#fff8f8] px-3 text-[11px] font-semibold text-[#ef4e4e]">With Errors</span>
                        </td>
                        <td class="border-t border-slate-200 px-4 py-4 text-center">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="mx-auto h-5 w-5 text-slate-900">
                                <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </td>
                    </tr>

                    <tr>
                        <td class="border-t border-slate-200 px-6 py-4"><span class="text-[12px] font-bold text-[#2f8cf4]">SES-9385</span></td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">2024-05-23 04:20 PM</div>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span>42m 05s</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">North Logistics Hub</div>
                            <div class="mt-1 text-[11px] font-bold text-[#5da7ff]">GATE-02</div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pravatar.cc/40?img=15" alt="David Miller" class="h-10 w-10 rounded-full">
                                <span class="text-[12px] font-semibold text-slate-900">David Miller</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-2 text-[12px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 text-slate-500">
                                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900">856</strong> tags</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <span class="inline-flex min-h-[30px] min-w-[94px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Complete</span>
                        </td>
                        <td class="border-t border-slate-200 px-4 py-4 text-center">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="mx-auto h-5 w-5 text-slate-900">
                                <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </td>
                    </tr>

                    <tr>
                        <td class="border-t border-slate-200 px-6 py-4"><span class="text-[12px] font-bold text-[#2f8cf4]">SES-9372</span></td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">2024-05-23 02:10 PM</div>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span>15m 30s</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">Central Yard A</div>
                            <div class="mt-1 text-[11px] font-bold text-[#5da7ff]">GATE-07</div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pravatar.cc/40?img=49" alt="Elena Rodriguez" class="h-10 w-10 rounded-full">
                                <span class="text-[12px] font-semibold text-slate-900">Elena Rodriguez</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-2 text-[12px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 text-slate-500">
                                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900">245</strong> tags</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <span class="inline-flex min-h-[30px] min-w-[104px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Incomplete</span>
                        </td>
                        <td class="border-t border-slate-200 px-4 py-4 text-center">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="mx-auto h-5 w-5 text-slate-900">
                                <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </td>
                    </tr>

                    <tr>
                        <td class="border-t border-slate-200 px-6 py-4"><span class="text-[12px] font-bold text-[#2f8cf4]">SES-9366</span></td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">2024-05-23 11:35 AM</div>
                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-4 w-4">
                                    <circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/>
                                    <path d="M12 7.5v5l3 1.8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span>12m 45s</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="text-[12px] font-semibold text-slate-900">North Logistics Hub</div>
                            <div class="mt-1 text-[11px] font-bold text-[#5da7ff]">GATE-04</div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://i.pravatar.cc/40?img=12" alt="Marcus Chen" class="h-10 w-10 rounded-full">
                                <span class="text-[12px] font-semibold text-slate-900">Marcus Chen</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <div class="flex items-center gap-2 text-[12px] text-slate-500">
                                <svg viewBox="0 0 24 24" aria-hidden="true" class="h-5 w-5 text-slate-500">
                                    <path d="M12 2 4.5 6v12L12 22l7.5-4V6L12 2Z" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                    <path d="M4.5 6 12 10l7.5-4M12 10v12" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="1.8"/>
                                </svg>
                                <span><strong class="font-bold text-slate-900">310</strong> tags</span>
                            </div>
                        </td>
                        <td class="border-t border-slate-200 px-6 py-4">
                            <span class="inline-flex min-h-[30px] min-w-[94px] items-center justify-center rounded-full border border-slate-300 bg-white px-3 text-[11px] font-semibold text-slate-800">Complete</span>
                        </td>
                        <td class="border-t border-slate-200 px-4 py-4 text-center">
                            <svg viewBox="0 0 24 24" aria-hidden="true" class="mx-auto h-5 w-5 text-slate-900">
                                <path d="m9 6 6 6-6 6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"/>
                            </svg>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-4 border-t border-slate-200 px-6 py-5 xl:flex-row xl:items-center xl:justify-between">
            <p class="m-0 text-[12px] text-slate-500">
                Showing <strong class="font-semibold text-slate-900">1-5</strong> of <strong class="font-semibold text-slate-900">1,284</strong> results
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 text-[11px] font-medium text-slate-400">Previous</button>
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-[#2f8cf4] bg-[#2f8cf4] text-[11px] font-semibold text-white">1</button>
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 bg-white text-[11px] font-semibold text-slate-900">2</button>
                <button type="button" class="inline-flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-300 bg-white text-[11px] font-semibold text-slate-900">3</button>
                <button type="button" class="inline-flex h-12 items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 text-[11px] font-medium text-slate-900">Next</button>
            </div>
        </div>
    </section>
</div>
@endsection
