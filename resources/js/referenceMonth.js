// Helpers for the "YYYY-MM" reference month (the month a transaction counts in)

function toDate(month) {
    const [year, m] = month.split('-').map(Number);
    return new Date(year, m - 1, 1);
}

// "2026-10" -> "out/26"
export function shortMonth(month) {
    if (!month) return '';
    const date = toDate(month);
    const name = date.toLocaleDateString('pt-BR', { month: 'short' }).replace('.', '');
    return `${name}/${String(date.getFullYear()).slice(2)}`;
}

// "2026-10" -> "outubro de 2026"
export function longMonth(month) {
    if (!month) return '';
    return toDate(month).toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
}

// Is the transaction counted in a different month than its date? (e.g. card purchase billed next month)
export function isShifted(transaction) {
    return !!transaction.reference_month
        && transaction.reference_month !== String(transaction.date).slice(0, 7);
}

// "Nubank_2026-10-14.csv" -> "2026-10"
export function monthFromFilename(filename) {
    const match = filename?.match(/(\d{4})-(\d{2})-\d{2}/);
    return match && Number(match[2]) >= 1 && Number(match[2]) <= 12 ? `${match[1]}-${match[2]}` : null;
}
