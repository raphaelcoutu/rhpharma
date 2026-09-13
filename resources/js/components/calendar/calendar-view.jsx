import CalendarBulkEditor from '@/components/calendar/calendar-bulk-editor';
import CalendarCell from '@/components/calendar/calendar-cell';
import CalendarEditor from '@/components/calendar/calendar-editor';
import { getDateKey, useCalendarData } from '@/hooks/use-calendar-data';
import { exportMethod } from '@/routes';
import { show as showSchedule } from '@/routes/schedules';
import { eachDayOfInterval, format, isWeekend, parseISO } from 'date-fns';
import { fr } from 'date-fns/locale';
import { ChevronLeft, ChevronRight, Download, ListFilter, Search, Sparkles, Users, X } from 'lucide-react';
import { useCallback, useEffect, useMemo, useRef, useState } from 'react';

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function formatPeriod(start, end) {
    const startDate = parseISO(start);
    const endDate = parseISO(end);

    if (format(startDate, 'yyyy') === format(endDate, 'yyyy')) {
        return `${format(startDate, 'd MMM', { locale: fr })} – ${format(endDate, 'd MMM yyyy', { locale: fr })}`;
    }

    return `${format(startDate, 'd MMM yyyy', { locale: fr })} – ${format(endDate, 'd MMM yyyy', { locale: fr })}`;
}

export default function CalendarView({ schedule, users = [], departments = [], shifts = [], selectedDepartmentIds = [], canEdit = false }) {
    const allDates = useMemo(
        () => eachDayOfInterval({ start: parseISO(getDateKey(schedule.start_date)), end: parseISO(getDateKey(schedule.end_date)) }).map((date) => format(date, 'yyyy-MM-dd')),
        [schedule.end_date, schedule.start_date],
    );
    const totalWeeks = Math.max(1, Math.ceil(allDates.length / 7));
    const [weekCount, setWeekCount] = useState(Math.min(2, totalWeeks));
    const [firstDay, setFirstDay] = useState(0);
    const [departmentFilter, setDepartmentFilter] = useState(selectedDepartmentIds.length === 1 ? String(selectedDepartmentIds[0]) : 'all');
    const [search, setSearch] = useState('');
    const [selectionMode, setSelectionMode] = useState(false);
    const [selectedCells, setSelectedCells] = useState({});
    const [editingCell, setEditingCell] = useState(null);
    const [bulkEditorOpen, setBulkEditorOpen] = useState(false);
    const [assignedShifts, setAssignedShifts] = useState(() => users.flatMap((user) => user.assigned_shifts ?? []));
    const tableScrollRef = useRef(null);
    const headerDatesRef = useRef(null);

    const { shiftsByUserDate, constraintsByUserDate } = useCalendarData(users, assignedShifts);
    const maxFirstDay = Math.max(0, allDates.length - weekCount * 7);
    const visibleDates = allDates.slice(firstDay, firstDay + weekCount * 7);
    const visibleStartWeek = Math.floor(firstDay / 7) + 1;
    const visibleEndWeek = Math.min(totalWeeks, Math.ceil((firstDay + visibleDates.length) / 7));

    const filteredUsers = useMemo(() => {
        const normalizedSearch = normalize(search.trim());

        return users.filter((user) => {
            const matchesDepartment = departmentFilter === 'all' || (user.departments ?? []).some((department) => String(department.id) === departmentFilter);
            const matchesSearch = !normalizedSearch || normalize(`${user.firstname} ${user.lastname}`).includes(normalizedSearch);

            return matchesDepartment && matchesSearch;
        });
    }, [departmentFilter, search, users]);

    const selectedCellList = useMemo(
        () => Object.values(selectedCells).filter(Boolean),
        [selectedCells],
    );

    useEffect(() => {
        function handleKeyDown(event) {
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(event.target.tagName)) {
                return;
            }

            if (event.key === 'ArrowLeft') {
                setFirstDay((current) => Math.max(0, current - 7));
            }

            if (event.key === 'ArrowRight') {
                setFirstDay((current) => Math.min(maxFirstDay, current + 7));
            }

            if (event.key === 'Escape') {
                setEditingCell(null);
                setBulkEditorOpen(false);
            }
        }

        window.addEventListener('keydown', handleKeyDown);

        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [maxFirstDay]);

    useEffect(() => {
        setWeekCount((current) => Math.min(current, totalWeeks));
    }, [totalWeeks]);

    useEffect(() => {
        setFirstDay((current) => Math.min(current, maxFirstDay));
    }, [maxFirstDay]);

    useEffect(() => {
        const tableScroll = tableScrollRef.current;
        const headerDates = headerDatesRef.current;

        if (!tableScroll || !headerDates) {
            return;
        }

        function syncHeaderPosition() {
            headerDates.style.transform = `translate3d(-${tableScroll.scrollLeft}px, 0, 0)`;
        }

        syncHeaderPosition();
        tableScroll.addEventListener('scroll', syncHeaderPosition, { passive: true });

        return () => tableScroll.removeEventListener('scroll', syncHeaderPosition);
    }, [firstDay, visibleDates.length]);

    const updateAssignedShifts = useCallback((updatedShifts) => {
        const updatedKeys = new Set(updatedShifts.map((shift) => `${shift.user_id}::${getDateKey(shift.date)}`));

        setAssignedShifts((currentShifts) => [
            ...currentShifts.filter((shift) => !updatedKeys.has(`${shift.user_id}::${getDateKey(shift.date)}`)),
            ...updatedShifts,
        ]);
    }, []);

    function toggleCell(user, date) {
        const key = `${user.id}::${date}`;

        setSelectedCells((current) => {
            const next = { ...current };

            if (next[key]) {
                delete next[key];
            } else {
                next[key] = { user, date, shifts: shiftsByUserDate[user.id]?.[date] ?? [], constraints: constraintsByUserDate[user.id]?.[date] ?? [] };
            }

            return next;
        });
    }

    function handleCellClick(event, user, date) {
        if (event.shiftKey || event.metaKey || event.ctrlKey || selectionMode) {
            toggleCell(user, date);
            return;
        }

        setEditingCell({ user, date, shifts: shiftsByUserDate[user.id]?.[date] ?? [], constraints: constraintsByUserDate[user.id]?.[date] ?? [] });
    }

    function clearSelection() {
        setSelectedCells({});
        setSelectionMode(false);
    }

    function handleBulkSaved(updatedShifts) {
        const selectedKeys = new Set(selectedCellList.map((cell) => `${cell.user.id}::${cell.date}`));

        setAssignedShifts((currentShifts) => [
            ...currentShifts.filter((shift) => !selectedKeys.has(`${shift.user_id}::${getDateKey(shift.date)}`)),
            ...updatedShifts,
        ]);
        clearSelection();
    }

    return (
        <div className="min-h-screen bg-slate-50 pb-12">
            <div className="mx-auto max-w-[1800px] space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                <header className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <div className="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-indigo-600">
                            <Sparkles className="h-4 w-4" />
                            Planification
                        </div>
                        <div className="mt-2 flex flex-wrap items-center gap-3">
                            <h1 className="text-3xl font-semibold tracking-tight text-slate-900">Calendrier</h1>
                            <span className="rounded-full bg-white px-3 py-1 text-sm font-medium text-slate-500 shadow-sm ring-1 ring-slate-200">{schedule.name}</span>
                        </div>
                        <p className="mt-2 text-sm text-slate-500">{formatPeriod(getDateKey(schedule.start_date), getDateKey(schedule.end_date))} · {users.length} utilisateur{users.length === 1 ? '' : 's'}</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a href={exportMethod(schedule.id).url} className="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
                            <Download className="h-4 w-4" />
                            Exporter
                        </a>
                        <a href={showSchedule(schedule.id).url} className="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800">
                            Retour au processus
                        </a>
                    </div>
                </header>

                <section className="rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div className="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
                            <label className="relative block sm:w-64">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" aria-hidden="true" />
                                <input type="search" value={search} onChange={(event) => setSearch(event.target.value)} placeholder="Rechercher un utilisateur" className="w-full rounded-lg border-slate-300 py-2 pl-9 pr-3 text-sm focus:border-indigo-500 focus:ring-indigo-500" aria-label="Rechercher un utilisateur" />
                            </label>
                            <label className="flex items-center gap-2 text-sm text-slate-600">
                                <ListFilter className="h-4 w-4 text-slate-400" aria-hidden="true" />
                                <span className="sr-only">Filtrer par secteur</span>
                                <select value={departmentFilter} onChange={(event) => { setDepartmentFilter(event.target.value); clearSelection(); }} className="rounded-lg border-slate-300 py-2 pl-3 pr-8 text-sm focus:border-indigo-500 focus:ring-indigo-500" aria-label="Filtrer par secteur">
                                    <option value="all">Tous les secteurs</option>
                                    {departments.map((department) => <option key={department.id} value={department.id}>{department.name}</option>)}
                                </select>
                            </label>
                            <span className="inline-flex items-center gap-1.5 text-sm text-slate-500"><Users className="h-4 w-4" />{filteredUsers.length} affiché{filteredUsers.length === 1 ? '' : 's'}</span>
                        </div>

                        <div className="flex flex-wrap items-center gap-2">
                            <div className="flex items-center rounded-lg border border-slate-200 bg-slate-50 p-1" aria-label="Nombre de semaines affichées">
                                {[1, 2, 4].filter((count) => count <= totalWeeks).map((count) => <button key={count} type="button" onClick={() => setWeekCount(count)} className={`rounded-md px-3 py-1.5 text-xs font-semibold ${weekCount === count ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-500 hover:text-slate-800'}`}>{count} sem.</button>)}
                            </div>
                            {canEdit && <button type="button" onClick={() => setSelectionMode((current) => !current)} className={`rounded-lg border px-3 py-2 text-sm font-semibold ${selectionMode ? 'border-indigo-200 bg-indigo-50 text-indigo-700' : 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50'}`}>{selectionMode ? 'Sélection active' : 'Sélectionner'}</button>}
                        </div>
                    </div>

                    {selectedCellList.length > 0 && <div className="mt-4 flex flex-col gap-3 rounded-lg border border-indigo-100 bg-indigo-50/70 p-3 text-sm sm:flex-row sm:items-center sm:justify-between"><div className="font-medium text-indigo-900">{selectedCellList.length} case{selectedCellList.length === 1 ? '' : 's'} sélectionnée{selectedCellList.length === 1 ? '' : 's'}</div><div className="flex flex-wrap gap-2"><button type="button" onClick={() => setBulkEditorOpen(true)} className="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Modifier les shifts</button><button type="button" onClick={clearSelection} className="inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-50"><X className="h-3.5 w-3.5" />Effacer</button></div></div>}
                    {!canEdit && <p className="mt-4 rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-500">Cet horaire est en lecture seule avec vos permissions actuelles.</p>}
                </section>

                <section className="overflow-visible rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div className="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-3 sm:px-5">
                        <div><p className="text-sm font-semibold text-slate-900">Semaine {visibleStartWeek} à {visibleEndWeek}</p><p className="text-xs text-slate-500">Cliquez sur une case pour modifier une journée · Maj/Ctrl-clic pour sélectionner plusieurs cases</p></div>
                        <div className="flex items-center gap-1"><button type="button" onClick={() => setFirstDay((current) => Math.max(0, current - 7))} disabled={firstDay === 0} className="rounded-lg p-2 text-slate-500 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30" aria-label="Période précédente"><ChevronLeft className="h-5 w-5" /></button><button type="button" onClick={() => setFirstDay((current) => Math.min(maxFirstDay, current + 7))} disabled={firstDay >= maxFirstDay} className="rounded-lg p-2 text-slate-500 hover:bg-slate-100 disabled:cursor-not-allowed disabled:opacity-30" aria-label="Période suivante"><ChevronRight className="h-5 w-5" /></button></div>
                    </div>

                    <div className="sticky top-0 z-40 overflow-hidden bg-white">
                        <div className="flex w-max min-w-full">
                            <div className="flex w-[260px] shrink-0 items-end border-b border-r border-slate-200 bg-slate-50 px-4 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Utilisateur</div>
                            <div ref={headerDatesRef} className="grid shrink-0" style={{ gridTemplateColumns: `repeat(${visibleDates.length}, 104px)` }}>
                                {visibleDates.map((date) => <div key={date} className={`flex min-h-16 flex-col items-center justify-center border-b border-r border-slate-200 px-1 text-center ${isWeekend(parseISO(date)) ? 'bg-slate-100 text-slate-500' : 'bg-slate-50 text-slate-700'}`}><span className="text-xs font-semibold uppercase">{format(parseISO(date), 'EEE', { locale: fr })}</span><span className="mt-1 text-lg font-semibold">{format(parseISO(date), 'd')}</span><span className="text-[11px] text-slate-400">{format(parseISO(date), 'MMM', { locale: fr })}</span></div>)}
                            </div>
                        </div>
                    </div>

                    <div ref={tableScrollRef} className="overflow-x-auto">
                        <div className="grid min-w-max" style={{ gridTemplateColumns: `260px repeat(${visibleDates.length}, 104px)`, width: 'max-content' }}>
                            {filteredUsers.length > 0 ? filteredUsers.map((user) => <div key={user.id} className="contents"><div className="sticky left-0 z-10 flex min-h-16 flex-col justify-center border-b border-r border-slate-200 bg-white px-4 py-2"><span className="text-sm font-semibold text-slate-800">{user.lastname}, {user.firstname}</span><span className="mt-0.5 text-xs text-slate-400">{user.workdays_per_week} jours · {(user.departments ?? []).map((department) => department.name).join(', ') || 'Aucun secteur'}</span></div>{visibleDates.map((date) => <CalendarCell key={`${user.id}-${date}`} date={date} shifts={shiftsByUserDate[user.id]?.[date] ?? []} constraints={constraintsByUserDate[user.id]?.[date] ?? []} isSelected={Boolean(selectedCells[`${user.id}::${date}`])} isWeekend={isWeekend(parseISO(date))} onClick={(event) => handleCellClick(event, user, date)} canEdit={canEdit} />)}</div>) : <div className="col-span-full p-12 text-center text-sm text-slate-500">Aucun utilisateur ne correspond aux filtres actuels.</div>}
                        </div>
                    </div>
                </section>
            </div>

            {editingCell && <CalendarEditor cell={editingCell} shifts={shifts} scheduleId={schedule.id} onClose={() => setEditingCell(null)} onSaved={(updatedShifts) => { updateAssignedShifts(updatedShifts); setEditingCell(null); }} />}
            {bulkEditorOpen && <CalendarBulkEditor cells={selectedCellList} shifts={shifts} scheduleId={schedule.id} onClose={() => setBulkEditorOpen(false)} onSaved={handleBulkSaved} />}
        </div>
    );
}
