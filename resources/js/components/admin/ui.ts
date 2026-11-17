/** Shared class strings for admin form controls. */
const control =
    'rounded-lg border border-[#d5dbe7] bg-white px-3 py-2 text-[15px] text-[#0a0f24] outline-none transition placeholder:text-[#9aa6c1] focus:border-brand-500 focus:ring-3 focus:ring-brand-500/15 disabled:bg-[#f6f7fb]';

export const input = `w-full ${control}`;
/** For selects and inputs that size to their content in a toolbar. */
export const inputInline = `w-auto ${control}`;

export const card = 'rounded-xl border border-[#e4e8f0] bg-white';

export const button = {
    primary: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 text-sm font-medium text-white transition hover:bg-brand-700 disabled:opacity-50',
    secondary: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg border border-[#d5dbe7] bg-white px-4 text-sm font-medium text-[#0a0f24] transition hover:bg-[#f6f7fb] disabled:opacity-50',
    danger: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-red-600 px-4 text-sm font-medium text-white transition hover:bg-red-700 disabled:opacity-50',
    ghost: 'inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg px-2.5 text-sm text-[#55607a] transition hover:bg-[#f0f2f7] hover:text-[#0a0f24]',
};

export const statusColours: Record<string, string> = {
    published: 'bg-green-50 text-green-700 ring-green-600/20',
    draft: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    new: 'bg-brand-50 text-brand-700 ring-brand-600/20',
    contacted: 'bg-cyan-50 text-cyan-700 ring-cyan-600/20',
    qualified: 'bg-violet-50 text-violet-700 ring-violet-600/20',
    proposal: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    won: 'bg-green-50 text-green-700 ring-green-600/20',
    lost: 'bg-[#f0f2f7] text-[#55607a] ring-[#9aa6c1]/30',
    spam: 'bg-red-50 text-red-700 ring-red-600/20',
    live: 'bg-green-50 text-green-700 ring-green-600/20',
    demo: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    product: 'bg-violet-50 text-violet-700 ring-violet-600/20',
};
