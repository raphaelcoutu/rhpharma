import { setSelectedData } from '@/routes/calendar/api';
import axios from 'axios';
import { Search, X } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

export default function CalendarBulkEditor({ cells, shifts, scheduleId, onClose, onSaved }) {
    const [selectedShiftIds, setSelectedShiftIds] = useState([]);
    const [search, setSearch] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    useEffect(() => {
        setSelectedShiftIds([]);
        setSearch('');
        setError('');
    }, [cells]);

    const filteredShifts = useMemo(() => {
        const normalizedSearch = search.trim().toLowerCase();

        return shifts.filter((shift) => `${shift.code} ${shift.description ?? ''} ${shift.department?.name ?? ''}`.toLowerCase().includes(normalizedSearch));
    }, [search, shifts]);

    function toggleShift(shiftId) {
        setSelectedShiftIds((currentIds) =>
            currentIds.includes(shiftId) ? currentIds.filter((id) => id !== shiftId) : [...currentIds, shiftId],
        );
    }

    async function save(event) {
        event.preventDefault();
        setSaving(true);
        setError('');

        try {
            const response = await axios.post(setSelectedData.url(), {
                selected: cells.map((cell) => ({ user_id: cell.user.id, date: cell.date })),
                shifts: selectedShiftIds,
                schedule_id: scheduleId,
            });

            onSaved(response.data);
            onClose();
        } catch (requestError) {
            setError(requestError.response?.data?.message ?? 'La sauvegarde a échoué. Vérifiez les données puis réessayez.');
        } finally {
            setSaving(false);
        }
    }

    return (
        <div className="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/50 p-0 sm:items-center sm:p-6" role="presentation">
            <div className="flex max-h-[92vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl" role="dialog" aria-modal="true" aria-labelledby="calendar-bulk-editor-title">
                <div className="flex items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p className="text-xs font-semibold uppercase tracking-wider text-indigo-600">Modification en lot</p>
                        <h2 id="calendar-bulk-editor-title" className="mt-1 text-lg font-semibold text-slate-900">{cells.length} case{cells.length === 1 ? '' : 's'} sélectionnée{cells.length === 1 ? '' : 's'}</h2>
                        <p className="mt-1 text-sm text-slate-500">La sélection remplacera les shifts présents dans chaque case.</p>
                    </div>
                    <button type="button" onClick={onClose} className="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Fermer">
                        <X className="h-5 w-5" />
                    </button>
                </div>

                <form onSubmit={save} className="flex min-h-0 flex-1 flex-col">
                    <div className="min-h-0 overflow-y-auto p-5 sm:p-6">
                        <label className="relative block">
                            <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" aria-hidden="true" />
                            <input
                                type="search"
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                                placeholder="Rechercher un shift ou un secteur"
                                className="w-full rounded-lg border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                aria-label="Rechercher un shift"
                            />
                        </label>
                        <p className="mt-3 text-xs text-slate-500">Une sélection vide efface les shifts des cases choisies.</p>
                        <div className="mt-3 rounded-lg border border-slate-200">
                            {filteredShifts.map((shift) => (
                                <label key={shift.id} className="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-3 py-2.5 last:border-0 hover:bg-slate-50">
                                    <input type="checkbox" checked={selectedShiftIds.includes(shift.id)} onChange={() => toggleShift(shift.id)} className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                                    <span className="min-w-0 flex-1">
                                        <span className="block text-sm font-semibold text-slate-800">{shift.code}</span>
                                        <span className="block truncate text-xs text-slate-500">{shift.department?.name ?? shift.description}</span>
                                    </span>
                                </label>
                            ))}
                            {filteredShifts.length === 0 && <p className="p-5 text-center text-sm text-slate-500">Aucun shift trouvé.</p>}
                        </div>
                        {error && <p className="mt-4 rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">{error}</p>}
                    </div>

                    <div className="flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" onClick={onClose} disabled={saving} className="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50">Annuler</button>
                        <button type="submit" disabled={saving} className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">{saving ? 'Enregistrement…' : 'Appliquer la sélection'}</button>
                    </div>
                </form>
            </div>
        </div>
    );
}
