import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { create, edit, show } from '@/routes/schedules';
import { show as showCalendar } from '@/routes/calendar';
import { CheckCircle2, CircleDashed, Clock3, FileWarning, LoaderCircle, Pencil, Plus, Search, TriangleAlert } from 'lucide-react';
import { useMemo, useState } from 'react';

const statusDefinitions = {
    0: { label: 'En attente', icon: CircleDashed, className: 'text-gray-400' },
    1: { label: 'Terminé', icon: CheckCircle2, className: 'text-emerald-600' },
    2: { label: 'Erreur', icon: TriangleAlert, className: 'text-amber-500' },
    3: { label: 'En cours', icon: LoaderCircle, className: 'animate-spin text-indigo-600' },
    4: { label: 'Annulé', icon: TriangleAlert, className: 'text-red-600' },
    5: { label: 'Réinitialisé', icon: CircleDashed, className: 'text-gray-400' },
    6: { label: 'Analyse', icon: LoaderCircle, className: 'animate-spin text-indigo-600' },
};

function normalize(value) {
    return String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();
}

function formatDate(value) {
    const date = String(value ?? '').slice(0, 10);

    if (!date) {
        return '—';
    }

    return new Intl.DateTimeFormat('fr-CA', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(new Date(`${date}T12:00:00`));
}

function StatusIcon({ value }) {
    const definition = statusDefinitions[Number(value)] ?? statusDefinitions[0];
    const Icon = definition.icon;

    return <Icon className={`h-5 w-5 ${definition.className}`} aria-label={definition.label} title={definition.label} />;
}

function ScheduleStatuses({ schedule }) {
    const statuses = [schedule.status_holidays, schedule.status_weekends, schedule.status_last_evening, schedule.status_clinical_departments];

    return (
        <div className="flex items-center gap-2" aria-label="Progression de l'horaire">
            {statuses.map((status, index) => (
                <StatusIcon key={`${schedule.id}-status-${index}`} value={status} />
            ))}
        </div>
    );
}

function Pagination({ paginator }) {
    if (!paginator?.links?.length || paginator.links.length <= 3) {
        return null;
    }

    return (
        <nav className="flex flex-wrap items-center justify-center gap-2 border-t border-gray-100 px-5 py-4" aria-label="Pagination des horaires">
            {paginator.links.map((link) => (
                <Link
                    key={`${link.label}-${link.url}`}
                    href={link.url ?? '#'}
                    preserveScroll
                    className={`rounded-md px-3 py-1.5 text-sm transition ${
                        link.active
                            ? 'bg-indigo-600 font-medium text-white'
                            : link.url
                              ? 'text-gray-600 hover:bg-gray-100'
                              : 'cursor-not-allowed text-gray-300'
                    }`}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                    aria-disabled={!link.url}
                />
            ))}
        </nav>
    );
}

export default function Index({ constraintsInSchedule = {}, pageTitle = 'Horaires', schedules }) {
    const [search, setSearch] = useState('');
    const scheduleRows = Array.isArray(schedules) ? schedules : (schedules?.data ?? []);

    const filteredSchedules = useMemo(() => {
        const query = normalize(search.trim());

        if (!query) {
            return scheduleRows;
        }

        return scheduleRows.filter((schedule) =>
            [schedule.name, schedule.start_date, schedule.end_date].some((value) => normalize(value).includes(query)),
        );
    }, [scheduleRows, search]);

    const schedulesWithWarnings = scheduleRows.filter((schedule) => Number(constraintsInSchedule[schedule.id] ?? 0) > 0).length;

    return (
        <AuthenticatedLayout>
            <Head title={pageTitle} />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Clock3 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Planification</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Horaires</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Préparez les périodes de planification, suivez leur progression et accédez à leur calendrier.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={create()} className="gap-2">
                            <Plus className="h-4 w-4" />
                            Ajouter un horaire
                        </SecondaryButton>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Horaires enregistrés</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{scheduleRows.length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <p className="text-sm font-medium text-gray-500">Résultats affichés</p>
                            <p className="mt-2 text-3xl font-semibold text-gray-900">{filteredSchedules.length}</p>
                        </div>
                        <div className="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                            <div className="flex items-start justify-between gap-4">
                                <div>
                                    <p className="text-sm font-medium text-gray-500">À vérifier</p>
                                    <p className="mt-2 text-3xl font-semibold text-gray-900">{schedulesWithWarnings}</p>
                                </div>
                                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                                    <FileWarning className="h-5 w-5" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Tous les horaires</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {filteredSchedules.length} résultat{filteredSchedules.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <div className="relative w-full sm:max-w-xs">
                                <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                                <Input
                                    type="search"
                                    value={search}
                                    onChange={(event) => setSearch(event.target.value)}
                                    placeholder="Rechercher un horaire..."
                                    aria-label="Rechercher un horaire"
                                    className="pl-9"
                                />
                            </div>
                        </div>

                        <div className="overflow-x-auto">
                            <Table>
                                <TableHeader>
                                    <TableRow className="bg-gray-50/70 hover:bg-gray-50/70">
                                        <TableHead className="pl-6">Description</TableHead>
                                        <TableHead>Début</TableHead>
                                        <TableHead>Fin</TableHead>
                                        <TableHead>Progression</TableHead>
                                        <TableHead className="pr-6 text-right">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {filteredSchedules.map((schedule) => {
                                        const pendingConstraints = Number(constraintsInSchedule[schedule.id] ?? 0);

                                        return (
                                            <TableRow key={schedule.id}>
                                                <TableCell className="max-w-72 pl-6">
                                                    <div className="flex items-center gap-3">
                                                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                                            <Clock3 className="h-5 w-5" />
                                                        </div>
                                                        <div className="min-w-0">
                                                            <p className="truncate font-medium text-gray-900">{schedule.name}</p>
                                                            <Link
                                                                href={edit(schedule.id)}
                                                                className="mt-1 inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800"
                                                            >
                                                                <Pencil className="h-3.5 w-3.5" />
                                                                Modifier
                                                            </Link>
                                                        </div>
                                                    </div>
                                                </TableCell>
                                                <TableCell className="whitespace-nowrap text-sm text-gray-600">
                                                    {formatDate(schedule.start_date)}
                                                </TableCell>
                                                <TableCell className="whitespace-nowrap text-sm text-gray-600">
                                                    {formatDate(schedule.end_date)}
                                                </TableCell>
                                                <TableCell>
                                                    <div className="flex flex-col gap-2">
                                                        <ScheduleStatuses schedule={schedule} />
                                                        {pendingConstraints > 0 ? (
                                                            <span className="inline-flex items-center gap-1 text-xs font-medium text-amber-700">
                                                                <TriangleAlert className="h-3.5 w-3.5" />
                                                                {pendingConstraints} contrainte{pendingConstraints === 1 ? '' : 's'} à valider
                                                            </span>
                                                        ) : (
                                                            <span className="text-xs text-emerald-700">Contraintes validées</span>
                                                        )}
                                                    </div>
                                                </TableCell>
                                                <TableCell className="pr-6 text-right">
                                                    <div className="flex items-center justify-end gap-3">
                                                        <a
                                                                href={showCalendar(schedule.id).url}
                                                            className="text-sm font-medium text-gray-600 transition hover:text-gray-900"
                                                        >
                                                            Calendrier
                                                        </a>
                                                        <Link
                                                                href={show(schedule.id).url}
                                                            className="rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700"
                                                        >
                                                            Générer
                                                        </Link>
                                                    </div>
                                                </TableCell>
                                            </TableRow>
                                        );
                                    })}
                                    {filteredSchedules.length === 0 && (
                                        <TableRow>
                                            <TableCell colSpan={5} className="px-6 py-12 text-center">
                                                <div className="mx-auto flex max-w-sm flex-col items-center">
                                                    <div className="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                        <Search className="h-5 w-5" />
                                                    </div>
                                                    <p className="mt-4 font-medium text-gray-900">
                                                        {search ? 'Aucun horaire trouvé' : 'Aucun horaire enregistré'}
                                                    </p>
                                                    <p className="mt-1 text-sm text-gray-500">
                                                        {search
                                                            ? 'Essayez avec un autre terme de recherche.'
                                                            : 'Commencez par ajouter votre premier horaire.'}
                                                    </p>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                        <Pagination paginator={schedules} />
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
