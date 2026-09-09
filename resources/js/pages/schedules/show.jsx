import Dropdown from '@/components/dropdown';
import SecondaryButton from '@/components/secondary-button';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import {
    AlertTriangle,
    BarChart3,
    CheckCircle2,
    CircleDashed,
    CircleStop,
    Clock3,
    Download,
    FileCog,
    LoaderCircle,
    MessageSquareText,
    Play,
    RefreshCw,
    RotateCcw,
    Save,
    Settings2,
    ShieldCheck,
    TriangleAlert,
    X,
} from 'lucide-react';
import { useEffect, useMemo, useRef, useState } from 'react';

const statusDefinitions = {
    0: { label: 'En attente', icon: CircleDashed, className: 'text-gray-400' },
    1: { label: 'Terminé', icon: CheckCircle2, className: 'text-emerald-600' },
    2: { label: 'Erreur', icon: TriangleAlert, className: 'text-amber-500' },
    3: { label: 'En cours', icon: LoaderCircle, className: 'animate-spin text-indigo-600' },
    4: { label: 'Annulé', icon: CircleStop, className: 'text-red-600' },
    5: { label: 'Réinitialisé', icon: CircleDashed, className: 'text-gray-400' },
    6: { label: 'Analyse', icon: LoaderCircle, className: 'animate-spin text-indigo-600' },
};

const processSteps = [
    { key: 'weekends', label: 'Assigner les fins de semaine', action: 'Compléter weekends + congés' },
    { key: 'lastEvening', label: 'Assigner les derniers soirs de semaine', action: 'Assigner les VS' },
    { key: 'clinical', label: 'Assigner les secteurs cliniques', action: 'Générer' },
];

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

function StatusIcon({ value, large = false }) {
    const definition = statusDefinitions[Number(value)] ?? statusDefinitions[0];
    const Icon = definition.icon;

    return <Icon className={`${large ? 'h-6 w-6' : 'h-5 w-5'} ${definition.className}`} aria-label={definition.label} title={definition.label} />;
}

function StatusCell({ value }) {
    const definition = statusDefinitions[Number(value)] ?? statusDefinitions[0];

    return (
        <div className="flex items-center gap-2">
            <StatusIcon value={value} large />
            <span className={`text-sm font-medium ${definition.className.replace('animate-spin', '')}`}>{definition.label}</span>
        </div>
    );
}

function ActionButton({ children, disabled, onClick, variant = 'primary' }) {
    const styles = {
        primary: 'bg-indigo-600 text-white hover:bg-indigo-700',
        success: 'bg-emerald-600 text-white hover:bg-emerald-700',
        danger: 'bg-red-600 text-white hover:bg-red-700',
        neutral: 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
    };

    return (
        <button
            type="button"
            onClick={onClick}
            disabled={disabled}
            className={`inline-flex items-center justify-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-50 ${styles[variant]}`}
        >
            {children}
        </button>
    );
}

function ProcessTable({ schedule, constraintsCount, statuses, onStatusChange }) {
    function processAction(step, nextStatus) {
        onStatusChange(step, nextStatus);
    }

    return (
        <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div className="border-b border-gray-100 px-5 py-5 sm:px-6">
                <div className="flex items-center gap-3">
                    <FileCog className="h-5 w-5 text-indigo-600" />
                    <div>
                        <h2 className="font-semibold text-gray-900">Processus de génération</h2>
                        <p className="mt-1 text-sm text-gray-500">Exécutez les étapes dans l’ordre et surveillez leur état.</p>
                    </div>
                </div>
            </div>

            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-gray-100">
                    <thead className="bg-gray-50/70">
                        <tr>
                            <th className="w-2/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">Étape</th>
                            <th className="w-1/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Statut</th>
                            <th className="w-2/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 sm:px-6">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td className="px-5 py-5 align-top sm:px-6">
                                <p className="font-medium text-gray-900">Validation des contraintes des fériés et fins de semaine</p>
                                <dl className="mt-3 space-y-1 text-sm text-gray-500">
                                    <div className="flex gap-2">
                                        <dt className="font-medium text-gray-700">Limite :</dt>
                                        <dd>{formatDate(schedule.limit_date_weekends)}</dd>
                                    </div>
                                    <div className="flex gap-2">
                                        <dt className="font-medium text-gray-700">À valider :</dt>
                                        <dd>
                                            {constraintsCount} contrainte{constraintsCount === 1 ? '' : 's'}
                                        </dd>
                                    </div>
                                </dl>
                            </td>
                            <td className="px-5 py-5 align-top">
                                <div className="flex items-center gap-2 text-gray-400">
                                    <ShieldCheck className="h-6 w-6" />
                                    <span className="text-sm font-medium">Contrôle</span>
                                </div>
                            </td>
                            <td className="px-5 py-5 align-top sm:px-6">
                                <div className="flex flex-wrap gap-2">
                                    <ActionButton onClick={() => window.location.assign(route('constraintImporter.index'))} variant="primary">
                                        Importer les contraintes Azure
                                    </ActionButton>
                                    <ActionButton
                                        onClick={() => window.location.assign(route('constraintsValidator.index', { schedule: schedule.id }))}
                                        variant="neutral"
                                    >
                                        Valider les contraintes
                                    </ActionButton>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td className="px-5 py-5 font-medium text-gray-900 sm:px-6">Assigner les fériés</td>
                            <td className="px-5 py-5">
                                <StatusCell value={schedule.status_holidays} />
                            </td>
                            <td className="px-5 py-5 text-sm text-gray-500 sm:px-6">Automatique</td>
                        </tr>

                        {processSteps.map((step) => {
                            const status = statuses[step.key];
                            const isRunning = Number(status) === 3;
                            const isClinical = step.key === 'clinical';

                            return (
                                <tr key={step.key}>
                                    <td className="px-5 py-5 font-medium text-gray-900 sm:px-6">{step.label}</td>
                                    <td className="px-5 py-5">
                                        <StatusCell value={status} />
                                    </td>
                                    <td className="px-5 py-5 sm:px-6">
                                        {isRunning ? (
                                            <div className="flex flex-wrap items-center gap-3">
                                                <span className="text-sm text-gray-500">Processus en cours…</span>
                                                {isClinical && (
                                                    <ActionButton onClick={() => processAction(step.key, 4)} variant="danger">
                                                        <X className="h-4 w-4" />
                                                        Annuler
                                                    </ActionButton>
                                                )}
                                            </div>
                                        ) : (
                                            <div className="flex flex-wrap gap-2">
                                                <ActionButton onClick={() => processAction(step.key, 3)} variant="success">
                                                    <Play className="h-4 w-4" />
                                                    {step.action}
                                                </ActionButton>
                                                {isClinical && (
                                                    <>
                                                        <ActionButton onClick={() => processAction(step.key, 6)} variant="primary">
                                                            <RefreshCw className="h-4 w-4" />
                                                            Réanalyser
                                                        </ActionButton>
                                                        <ActionButton onClick={() => processAction(step.key, 5)} variant="danger">
                                                            <RotateCcw className="h-4 w-4" />
                                                            Mise à zéro
                                                        </ActionButton>
                                                    </>
                                                )}
                                            </div>
                                        )}
                                    </td>
                                </tr>
                            );
                        })}

                        <tr>
                            <td className="px-5 py-5 align-top sm:px-6">
                                <p className="font-medium text-gray-900">Validation des contraintes</p>
                                <dl className="mt-3 space-y-1 text-sm text-gray-500">
                                    <div className="flex gap-2">
                                        <dt className="font-medium text-gray-700">Limite :</dt>
                                        <dd>{formatDate(schedule.limit_date)}</dd>
                                    </div>
                                    <div className="flex gap-2">
                                        <dt className="font-medium text-gray-700">À valider :</dt>
                                        <dd>
                                            {constraintsCount} contrainte{constraintsCount === 1 ? '' : 's'}
                                        </dd>
                                    </div>
                                </dl>
                            </td>
                            <td className="px-5 py-5 align-top">
                                <div className="flex items-center gap-2 text-gray-400">
                                    <ShieldCheck className="h-6 w-6" />
                                    <span className="text-sm font-medium">Contrôle</span>
                                </div>
                            </td>
                            <td className="px-5 py-5 align-top sm:px-6">
                                <ActionButton
                                    onClick={() => window.location.assign(route('constraintsValidator.index', { schedule: schedule.id }))}
                                    variant="neutral"
                                >
                                    Valider les contraintes
                                </ActionButton>
                            </td>
                        </tr>

                        <tr>
                            <td className="px-5 py-5 font-medium text-gray-900 sm:px-6">Assigner la distribution</td>
                            <td className="px-5 py-5 text-sm text-gray-400">—</td>
                            <td className="px-5 py-5 text-sm text-gray-400 sm:px-6">Bientôt disponible</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    );
}

function LogPanel({ scheduleId }) {
    const [messages, setMessages] = useState([]);
    const messageBox = useRef(null);

    useEffect(() => {
        const channel = window.Echo?.channel('build-message');
        const listener = (event) => {
            if (Number(event.schedule?.id) !== Number(scheduleId)) {
                return;
            }

            setMessages((currentMessages) => [...currentMessages, `${event.timestamp ?? ''}\t ${event.message ?? ''}`]);
        };

        channel?.listen('BuildMessageGenerated', listener);

        return () => window.Echo?.leave('build-message');
    }, [scheduleId]);

    useEffect(() => {
        if (messageBox.current) {
            messageBox.current.scrollTop = messageBox.current.scrollHeight;
        }
    }, [messages]);

    return (
        <div>
            <div ref={messageBox} className="h-56 overflow-auto rounded-lg border border-gray-200 bg-gray-950 p-4 font-mono text-xs text-gray-200">
                {messages.length > 0 ? (
                    messages.map((message, index) => <div key={`${message}-${index}`}>{message}</div>)
                ) : (
                    <span>Aucun message reçu.</span>
                )}
            </div>
            <p className="mt-2 text-xs text-gray-500">Les nouveaux messages de génération apparaîtront ici automatiquement.</p>
        </div>
    );
}

function ConflictsPanel({ scheduleId, conflicts, onRefresh }) {
    const sortedConflicts = useMemo(
        () => [...conflicts].sort((first, second) => Number(second.severity ?? 0) - Number(first.severity ?? 0)),
        [conflicts],
    );

    return (
        <div>
            <div className="mb-4 flex items-center justify-between gap-4">
                <p className="text-sm text-gray-500">Les conflits sont classés par sévérité.</p>
                <ActionButton onClick={onRefresh} variant="neutral">
                    <RefreshCw className="h-4 w-4" />
                    Actualiser
                </ActionButton>
            </div>
            <div className="overflow-x-auto rounded-lg border border-gray-200">
                <table className="min-w-full divide-y divide-gray-200 text-sm">
                    <thead className="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th className="px-4 py-3">ID</th>
                            <th className="px-4 py-3">Sévérité</th>
                            <th className="px-4 py-3">Secteur</th>
                            <th className="px-4 py-3">Date</th>
                            <th className="px-4 py-3">Message</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-100 bg-white">
                        {sortedConflicts.map((conflict) => (
                            <tr key={conflict.id}>
                                <td className="whitespace-nowrap px-4 py-3 font-mono text-gray-600">{conflict.id}</td>
                                <td className="px-4 py-3 font-medium text-amber-700">{conflict.severity ?? '—'}</td>
                                <td className="px-4 py-3">
                                    {conflict.department ? (
                                        <a
                                            href={route('calendar.showByDepartment', { schedule: scheduleId, department: conflict.department.id })}
                                            className="font-medium text-indigo-600 hover:text-indigo-800"
                                        >
                                            {conflict.department.name} ({conflict.department.id})
                                        </a>
                                    ) : (
                                        '—'
                                    )}
                                </td>
                                <td className="whitespace-nowrap px-4 py-3 text-gray-600">
                                    {formatDate(conflict.start_date)}
                                    {conflict.end_date && ` – ${formatDate(conflict.end_date)}`}
                                </td>
                                <td className="min-w-64 px-4 py-3 text-gray-700">{conflict.message || '—'}</td>
                            </tr>
                        ))}
                        {sortedConflicts.length === 0 && (
                            <tr>
                                <td colSpan={5} className="px-4 py-10 text-center text-gray-500">
                                    Aucun conflit pour l&apos;instant.
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

function NotesPanel({ scheduleId, initialNotes }) {
    const [notes, setNotes] = useState(initialNotes ?? '');
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    async function saveNotes(event) {
        event.preventDefault();
        setSaving(true);
        setMessage('');
        setError('');

        try {
            await axios.put(`/api/schedules/${scheduleId}/updateNotes`, { notes });
            setMessage('Sauvegarde effectuée avec succès.');
        } catch {
            setError('Les notes n’ont pas pu être sauvegardées.');
        } finally {
            setSaving(false);
        }
    }

    return (
        <form onSubmit={saveNotes}>
            <label htmlFor="schedule-notes" className="text-sm font-medium text-gray-900">
                Notes de l&apos;horaire
            </label>
            <textarea
                id="schedule-notes"
                rows={9}
                value={notes}
                onChange={(event) => setNotes(event.target.value)}
                className="mt-2 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            />
            <div className="mt-4 flex flex-col-reverse items-start justify-between gap-3 sm:flex-row sm:items-center">
                <div>
                    {message && (
                        <p className="text-sm text-emerald-700" role="status">
                            {message}
                        </p>
                    )}
                    {error && (
                        <p className="text-sm text-red-600" role="alert">
                            {error}
                        </p>
                    )}
                </div>
                <ActionButton disabled={saving} variant="primary">
                    <Save className="h-4 w-4" />
                    {saving ? 'Sauvegarde...' : 'Sauvegarder'}
                </ActionButton>
            </div>
        </form>
    );
}

function StatisticsPanel({ scheduleId }) {
    const [statistics, setStatistics] = useState([]);
    const [status, setStatus] = useState('idle');

    async function loadStatistics() {
        const response = await axios.get(`/api/scheduleStatDepartment/${scheduleId}`);
        const content = response.data?.content;

        setStatistics(content ? JSON.parse(content) : []);
        setStatus('success');
    }

    useEffect(() => {
        const channel = window.Echo?.channel(`schedule.${scheduleId}`);
        const listener = () => {
            loadStatistics().catch(() => setStatus('error'));
        };

        channel?.listen('StatsByDepartmentsGenerated', listener);

        return () => window.Echo?.leave(`schedule.${scheduleId}`);
    }, [scheduleId]);

    async function generateStatistics() {
        setStatus('loading');

        try {
            await axios.get(`/api/scheduleStatDepartment/${scheduleId}/create`);
        } catch {
            setStatus('error');
        }
    }

    return (
        <div>
            <div className="flex flex-wrap items-center justify-between gap-3">
                <p className="text-sm text-gray-500">Consultez la répartition des heures par secteur et par utilisateur.</p>
                <ActionButton onClick={generateStatistics} disabled={status === 'loading'} variant="primary">
                    <BarChart3 className="h-4 w-4" />
                    {status === 'success' ? 'Régénérer' : 'Générer les statistiques'}
                </ActionButton>
            </div>

            {status === 'loading' && (
                <div className="mt-6 flex items-center gap-2 text-sm text-gray-500">
                    <LoaderCircle className="h-4 w-4 animate-spin text-indigo-600" />
                    Génération en cours…
                </div>
            )}
            {status === 'error' && <p className="mt-6 text-sm text-red-600">Les statistiques n’ont pas pu être générées.</p>}
            {status === 'success' && statistics.length > 0 && (
                <div className="mt-6 grid gap-4 lg:grid-cols-2">
                    {statistics.map((departmentStatistics) => (
                        <div key={departmentStatistics.department.id} className="rounded-lg border border-gray-200">
                            <div className="border-b border-gray-100 bg-gray-50 px-4 py-3 font-medium text-gray-900">
                                {departmentStatistics.department.name}
                            </div>
                            <div className="divide-y divide-gray-100">
                                {departmentStatistics.users.map((userStatistics) => (
                                    <div key={userStatistics.user.id} className="flex items-center justify-between gap-4 px-4 py-3 text-sm">
                                        <span className="text-gray-700">
                                            {userStatistics.user.firstname} {userStatistics.user.lastname}
                                        </span>
                                        <span className="font-mono font-medium text-gray-900">{userStatistics.hours} h</span>
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            )}
            {status === 'success' && statistics.length === 0 && <p className="mt-6 text-sm text-gray-500">Aucune statistique disponible.</p>}
        </div>
    );
}

function OutputPanel({ schedule, conflicts, onRefreshConflicts }) {
    const tabDefinitions = [
        { key: 'log', label: 'Log', icon: MessageSquareText },
        { key: 'conflicts', label: `Conflits (${conflicts.length})`, icon: AlertTriangle },
        { key: 'notes', label: 'Notes', icon: FileCog },
        { key: 'stats', label: 'Stats-départements', icon: BarChart3 },
    ];
    const [activeTab, setActiveTab] = useState(() => {
        const hash = window.location.hash.slice(1);
        return tabDefinitions.some((tab) => tab.key === hash) ? hash : 'log';
    });

    function changeTab(tab) {
        setActiveTab(tab);
        window.history.replaceState(null, '', `#${tab}`);
    }

    return (
        <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div className="flex flex-wrap gap-2 border-b border-gray-100 bg-gray-50/70 p-3">
                {tabDefinitions.map((tab) => {
                    const Icon = tab.icon;
                    const isActive = activeTab === tab.key;

                    return (
                        <button
                            key={tab.key}
                            type="button"
                            onClick={() => changeTab(tab.key)}
                            className={`inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition ${
                                isActive
                                    ? 'bg-white text-indigo-700 shadow-sm ring-1 ring-gray-200'
                                    : 'text-gray-500 hover:bg-white hover:text-gray-900'
                            }`}
                            aria-pressed={isActive}
                        >
                            <Icon className="h-4 w-4" />
                            {tab.label}
                        </button>
                    );
                })}
            </div>
            <div className="p-5 sm:p-6">
                {activeTab === 'log' && <LogPanel scheduleId={schedule.id} />}
                {activeTab === 'conflicts' && <ConflictsPanel scheduleId={schedule.id} conflicts={conflicts} onRefresh={onRefreshConflicts} />}
                {activeTab === 'notes' && <NotesPanel scheduleId={schedule.id} initialNotes={schedule.notes} />}
                {activeTab === 'stats' && <StatisticsPanel scheduleId={schedule.id} />}
            </div>
        </section>
    );
}

export default function Show({ conflicts: initialConflicts = [], constraintsCount = 0, departments = [], durationInWeeks = 0, schedule }) {
    const [statuses, setStatuses] = useState({
        weekends: Number(schedule.status_weekends),
        lastEvening: Number(schedule.status_last_evening),
        clinical: Number(schedule.status_clinical_departments),
    });
    const [conflicts, setConflicts] = useState(initialConflicts);

    useEffect(() => {
        const channel = window.Echo?.channel('build-status');
        const listener = (event) => {
            if (Number(event.scheduleId) !== Number(schedule.id)) {
                return;
            }

            const keyByBuildStep = { weekends: 'weekends', last_evening: 'lastEvening', clinical: 'clinical' };
            const statusKey = keyByBuildStep[event.buildStep];

            if (statusKey) {
                setStatuses((currentStatuses) => ({ ...currentStatuses, [statusKey]: Number(event.status) }));
            }
        };

        channel?.listen('UpdateBuildStatus', listener);

        return () => window.Echo?.leave('build-status');
    }, [schedule.id]);

    async function refreshConflicts() {
        const response = await axios.get(`/api/conflicts/${schedule.id}`);
        setConflicts(response.data);
    }

    async function changeStatus(buildStep, status) {
        const keyByBuildStep = { weekends: 'weekends', last_evening: 'lastEvening', clinical: 'clinical' };
        const statusKey = keyByBuildStep[buildStep];
        const previousStatus = statuses[statusKey];

        setStatuses((currentStatuses) => ({ ...currentStatuses, [statusKey]: status }));

        try {
            await axios.post('/api/schedules/updateStatus', {
                scheduleId: schedule.id,
                buildStep,
                status,
            });
        } catch {
            setStatuses((currentStatuses) => ({ ...currentStatuses, [statusKey]: previousStatus }));
        }
    }

    return (
        <AuthenticatedLayout>
            <Head title={`Générer ${schedule.name}`} />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <Clock3 className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Planification</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Générer l&apos;horaire</h1>
                            <p className="mt-2 text-lg font-medium text-gray-700">{schedule.name}</p>
                            <p className="mt-2 text-sm text-gray-500">
                                Du {formatDate(schedule.start_date)} au {formatDate(schedule.end_date)} · {durationInWeeks} semaine
                                {durationInWeeks === 1 ? '' : 's'}
                            </p>
                        </div>

                        <div className="flex flex-wrap items-center gap-2">
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <SecondaryButton className="gap-2">
                                        <Settings2 className="h-4 w-4" />
                                        Configuration
                                    </SecondaryButton>
                                </Dropdown.Trigger>
                                <Dropdown.Content align="right">
                                    <a
                                        href={route('settings.index')}
                                        className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                    >
                                        Paramètres généraux
                                    </a>
                                    <a
                                        href={route('settings.departments')}
                                        className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                    >
                                        Secteurs
                                    </a>
                                    <a
                                        href={route('settings.constraintTypes')}
                                        className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                    >
                                        Types de contraintes
                                    </a>
                                </Dropdown.Content>
                            </Dropdown>
                            <Dropdown>
                                <Dropdown.Trigger>
                                    <SecondaryButton className="gap-2">Calendrier</SecondaryButton>
                                </Dropdown.Trigger>
                                <Dropdown.Content align="right">
                                    <a
                                        href={route('calendar.show', schedule.id)}
                                        className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                    >
                                        Vue complète
                                    </a>
                                    {departments.map((department) => (
                                        <a
                                            key={department.id}
                                            href={route('calendar.showByDepartment', { schedule: schedule.id, department: department.id })}
                                            className="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none"
                                        >
                                            {department.name}
                                        </a>
                                    ))}
                                </Dropdown.Content>
                            </Dropdown>
                            <a
                                href={route('export', schedule.id)}
                                className="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-700"
                            >
                                <Download className="h-4 w-4" />
                                Exporter Excel
                            </a>
                        </div>
                    </div>

                    <ProcessTable schedule={schedule} constraintsCount={Number(constraintsCount)} statuses={statuses} onStatusChange={changeStatus} />

                    <OutputPanel schedule={schedule} conflicts={conflicts} onRefreshConflicts={refreshConflicts} />

                    <SecondaryButton as={Link} href={route('schedules.index')}>
                        Retour à la liste des horaires
                    </SecondaryButton>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
