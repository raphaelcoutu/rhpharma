import clsx from 'clsx';
import { CalendarDays } from 'lucide-react';

export default function CalendarCell({ date, shifts = [], constraints = [], isSelected, isWeekend, onClick, canEdit }) {
    const hasContent = shifts.length > 0 || constraints.length > 0;

    return (
        <button
            type="button"
            className={clsx(
                'group relative flex min-h-16 min-w-24 flex-col items-center justify-center gap-1 border-b border-r border-slate-200 px-1.5 py-2 text-center align-top transition focus:z-10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:border-slate-700',
                isWeekend && !isSelected ? 'bg-slate-50/80 dark:bg-slate-900/40' : 'bg-white dark:bg-slate-950',
                isSelected ? 'bg-indigo-50 ring-2 ring-inset ring-indigo-500 dark:bg-indigo-950/60' : 'hover:bg-indigo-50/70 dark:hover:bg-slate-900',
                canEdit ? 'cursor-pointer' : 'cursor-default',
            )}
            onClick={canEdit ? onClick : undefined}
            aria-label={`${date}${hasContent ? ` : ${[...shifts.map((shift) => shift.shift?.code), ...constraints.map((constraint) => constraint.code)].filter(Boolean).join(', ')}` : ''}`}
        >
            {hasContent ? (
                <div className="flex max-w-full flex-wrap justify-center gap-1">
                    {shifts.map((assignedShift) => (
                        <span
                            key={assignedShift.id}
                            className={clsx(
                                'rounded-md px-1.5 py-0.5 text-xs font-semibold leading-4',
                                assignedShift.is_generated
                                    ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/70 dark:text-indigo-200'
                                    : 'bg-amber-100 text-amber-800 dark:bg-amber-900/70 dark:text-amber-200',
                            )}
                        >
                            {assignedShift.shift?.code ?? '—'}
                        </span>
                    ))}
                </div>
            ) : (
                <span className="text-sm text-slate-300 transition group-hover:text-indigo-300 dark:text-slate-700">—</span>
            )}

            {constraints.length > 0 && (
                <div className="flex max-w-full items-center gap-1 truncate text-[11px] font-medium text-rose-600 dark:text-rose-300">
                    <CalendarDays className="h-3 w-3 shrink-0" aria-hidden="true" />
                    <span className="truncate">{constraints.map((constraint) => constraint.code).filter(Boolean).join(' · ')}</span>
                </div>
            )}

            {isSelected && <span className="absolute right-1 top-1 h-1.5 w-1.5 rounded-full bg-indigo-600" aria-hidden="true" />}
        </button>
    );
}
