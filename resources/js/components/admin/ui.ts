/** Shared class strings for admin form controls. */
const control =
    'rounded-lg border border-[#d9dadc] bg-white px-3 py-2 text-[15px] text-[#232428] outline-none transition placeholder:text-[#85888f] focus:border-focus focus:ring-3 focus:ring-brand-600/30 disabled:bg-[#f4f4f5]';

export const input = `w-full ${control}`;
/** For selects and inputs that size to their content in a toolbar. */
export const inputInline = `w-auto ${control}`;

export const card = 'rounded-xl border border-[#e3e4e6] bg-white';

export const button = {
    primary: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 text-sm font-medium text-ink-950 transition hover:bg-brand-700 disabled:opacity-50',
    secondary: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg border border-[#d9dadc] bg-white px-4 text-sm font-medium text-[#232428] transition hover:bg-[#f4f4f5] disabled:opacity-50',
    danger: 'inline-flex min-h-10 items-center justify-center gap-2 rounded-lg bg-red-600 px-4 text-sm font-medium text-white transition hover:bg-red-700 disabled:opacity-50',
    ghost: 'inline-flex min-h-9 items-center justify-center gap-1.5 rounded-lg px-2.5 text-sm text-[#5c5f66] transition hover:bg-[#ececed] hover:text-[#232428]',
};

export const statusColours: Record<string, string> = {
    published: 'bg-green-50 text-green-700 ring-green-600/20',
    draft: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    new: 'bg-brand-50 text-brand-900 ring-brand-600/20',
    contacted: 'bg-cyan-50 text-cyan-700 ring-cyan-600/20',
    qualified: 'bg-violet-50 text-violet-700 ring-violet-600/20',
    proposal: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    won: 'bg-green-50 text-green-700 ring-green-600/20',
    lost: 'bg-[#ececed] text-[#5c5f66] ring-[#85888f]/30',
    spam: 'bg-red-50 text-red-700 ring-red-600/20',
    live: 'bg-green-50 text-green-700 ring-green-600/20',
    demo: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    product: 'bg-violet-50 text-violet-700 ring-violet-600/20',
};
