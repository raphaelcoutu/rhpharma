import { setUserData } from '@/routes/calendar/api';
import axios from 'axios';
import { Search, X } from 'lucide-react';
import { useEffect, useMemo, useState } from 'react';

function formatDate(value) {
    return new Intl.DateTimeFormat('fr-CA', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(`${value}T12:00:00`));
}

export default function CalendarEditor({ cell, shifts, scheduleId, onClose, onSaved }) {
    const [selectedShiftIds, setSelectedShiftIds] = useState([]);
    const [search, setSearch] = useState('');
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState('');

    useEffect(() => {
        setSelectedShiftIds(cell?.shifts?.map((assignedShift) => assignedShift.shift_id) ?? []);
        setSearch('');
        setError('');
    }, [cell]);

    const filteredShifts = useMemo(() => {
        const normalizedSearch = search.trim().toLowerCase();

        return shifts.filter((shift) => {
            if (!normalizedSearch) {
                return true;
            }

            return `${shift.code} ${shift.description ?? ''} ${shift.department?.name ?? ''}`.toLowerCase().includes(normalizedSearch);
        });
    }, [search, shifts]);

    if (!cell) {
        return null;
    }

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
            const response = await axios.post(setUserData.url(), {
                user_id: cell.user.id,
                date: cell.date,
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
            <div className="flex max-h-[92vh] w-full max-w-xl flex-col overflow-hidden rounded-t-2xl bg-white shadow-2xl sm:rounded-2xl" role="dialog" aria-modal="true" aria-labelledby="calendar-editor-title">
                <div className="flex items-start justify-between border-b border-slate-200 px-5 py-4 sm:px-6">
                    <div>
                        <p className="text-xs font-semibold uppercase tracking-wider text-indigo-600">Modification du jour</p>
                        <h2 id="calendar-editor-title" className="mt-1 text-lg font-semibold text-slate-900">
                            {cell.user.firstname} {cell.user.lastname}
                        </h2>
                        <p className="mt-1 text-sm capitalize text-slate-500">{formatDate(cell.date)}</p>
                    </div>
                    <button type="button" onClick={onClose} className="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700" aria-label="Fermer">
                        <X className="h-5 w-5" />
                    </button>
                </div>

                <form onSubmit={save} className="flex min-h-0 flex-1 flex-col">
                    <div className="min-h-0 space-y-5 overflow-y-auto p-5 sm:p-6">
                        <div>
                            <div className="flex items-center justify-between gap-3">
                                <div>
                                    <h3 className="text-sm font-semibold text-slate-900">Shifts assignés</h3>
                                    <p className="mt-1 text-xs text-slate-500">Sélectionnez un ou plusieurs shifts. Une sélection vide efface la journée.</p>
                                </div>
                                <span className="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{selectedShiftIds.length} choisi{selectedShiftIds.length === 1 ? '' : 's'}</span>
                            </div>

                            <label className="relative mt-3 block">
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

                            <div className="mt-3 max-h-64 overflow-y-auto rounded-lg border border-slate-200">
                                {filteredShifts.map((shift) => (
                                    <label key={shift.id} className="flex cursor-pointer items-center gap-3 border-b border-slate-100 px-3 py-2.5 last:border-0 hover:bg-slate-50">
                                        <input
                                            type="checkbox"
                                            checked={selectedShiftIds.includes(shift.id)}
                                            onChange={() => toggleShift(shift.id)}
                                            className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span className="min-w-0 flex-1">
                                            <span className="block text-sm font-semibold text-slate-800">{shift.code}</span>
                                            <span className="block truncate text-xs text-slate-500">{shift.department?.name ?? shift.description}</span>
                                        </span>
                                    </label>
                                ))}
                                {filteredShifts.length === 0 && <p className="p-5 text-center text-sm text-slate-500">Aucun shift trouvé.</p>}
                            </div>
                        </div>

                        {cell.constraints.length > 0 && (
                            <div className="rounded-lg border border-rose-100 bg-rose-50/70 p-4">
                                <h3 className="text-sm font-semibold text-rose-900">Contraintes de la journée</h3>
                                <div className="mt-2 space-y-2 text-sm text-rose-800">
                                    {cell.constraints.map((constraint) => (
                                        <div key={constraint.id}>
                                            <span className="font-semibold">{constraint.code}</span>
                                            {constraint.constraint_type?.name && <span className="ml-2">{constraint.constraint_type.name}</span>}
                                            {constraint.comment && <p className="mt-0.5 text-xs text-rose-700">{constraint.comment}</p>}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                        {error && <p className="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">{error}</p>}
                    </div>

                    <div className="flex flex-col-reverse gap-2 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
                        <button type="button" onClick={onClose} disabled={saving} className="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:opacity-50">
                            Annuler
                        </button>
                        <button type="submit" disabled={saving} className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50">
                            {saving ? 'Enregistrement…' : 'Enregistrer'}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}
