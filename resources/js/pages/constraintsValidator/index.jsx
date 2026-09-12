import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link, useHttp } from '@inertiajs/react';
import { format, parseISO } from 'date-fns';
import { fr } from 'date-fns/locale';
import {
    ArrowLeft,
    BatteryFull,
    BatteryLow,
    CalendarRange,
    Check,
    CheckCircle2,
    ClipboardCheck,
    Clock3,
    History,
    List,
    LoaderCircle,
    ThumbsDown,
    ThumbsUp,
    UserRound,
    X,
    XCircle,
} from 'lucide-react';
import { Fragment, useMemo, useState } from 'react';

const sortOptions = [
    { value: 'dateAsc', label: 'Date — plus ancienne' },
    { value: 'dateDesc', label: 'Date — plus récente' },
    { value: 'nameAsc', label: 'Nom — A à Z' },
    { value: 'nameDesc', label: 'Nom — Z à A' },
    { value: 'weightDesc', label: 'Importance — plus forte' },
];

function getUserName(user) {
    return [user?.firstname, user?.lastname].filter(Boolean).join(' ') || 'Utilisateur inconnu';
}

function formatDateTime(value) {
    if (!value) {
        return 'Date inconnue';
    }

    try {
        return format(parseISO(String(value).replace(' ', 'T')), "dd/MM/yyyy 'à' HH:mm", { locale: fr });
    } catch {
        return value;
    }
}

function formatDate(value) {
    if (!value) {
        return 'Date inconnue';
    }

    try {
        return format(parseISO(value), 'dd/MM/yyyy', { locale: fr });
    } catch {
        return value;
    }
}

function getConstraintTypeName(constraint) {
    return constraint.constraint_type?.name || constraint.constraintType?.name || 'Type inconnu';
}

function compareConstraints(first, second, sort) {
    if (sort === 'nameAsc' || sort === 'nameDesc') {
        const direction = sort === 'nameAsc' ? 1 : -1;

        return getUserName(first.user).localeCompare(getUserName(second.user), 'fr', { sensitivity: 'base' }) * direction;
    }

    if (sort === 'weightDesc') {
        return Number(second.weight) - Number(first.weight);
    }

    const direction = sort === 'dateAsc' ? 1 : -1;

    return String(first.start_datetime || '').localeCompare(String(second.start_datetime || '')) * direction;
}

function ValidationActions({ constraint, validatorId, onValidated }) {
    const [requestError, setRequestError] = useState(null);
    const { put, processing, setData } = useHttp({ status: 0, validated_by: null });

    function validate(status) {
        setRequestError(null);
        setData({ status, validated_by: validatorId });

        void put(`/api/constraintsValidator/${constraint.id}`, {
            onSuccess: () => onValidated(constraint.id),
            onError: () => setRequestError('La validation n’a pas pu être enregistrée.'),
            onHttpException: () => setRequestError('La validation n’a pas pu être enregistrée.'),
            onNetworkError: () => setRequestError('Le serveur est indisponible. Réessayez.'),
        }).catch(() => {});
    }

    return (
        <div className="flex flex-col gap-2">
            <div className="flex items-center gap-2">
                <button
                    type="button"
                    onClick={() => validate(1)}
                    disabled={processing}
                    aria-label={`Approuver la contrainte de ${getUserName(constraint.user)}`}
                    title="Approuver"
                    className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-50"
                >
                    {processing ? <LoaderCircle className="h-4 w-4 animate-spin" /> : <ThumbsUp className="h-4 w-4" />}
                </button>
                <button
                    type="button"
                    onClick={() => validate(2)}
                    disabled={processing}
                    aria-label={`Refuser la contrainte de ${getUserName(constraint.user)}`}
                    title="Refuser"
                    className="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 transition hover:border-rose-300 hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 disabled:cursor-wait disabled:opacity-50"
                >
                    <ThumbsDown className="h-4 w-4" />
                </button>
            </div>
            {requestError && (
                <p className="max-w-48 text-xs text-rose-600" role="alert">
                    {requestError}
                </p>
            )}
        </div>
    );
}

function WeightIndicator({ weight }) {
    const isImportant = Boolean(Number(weight));
    const Icon = isImportant ? BatteryFull : BatteryLow;

    return (
        <span className={`inline-flex items-center gap-1.5 text-xs font-medium ${isImportant ? 'text-amber-700' : 'text-gray-400'}`}>
            <Icon className="h-4 w-4" aria-hidden="true" />
            {isImportant ? 'Forte' : 'Faible'}
        </span>
    );
}

function ConstraintMeta({ constraint }) {
    return (
        <>
            <div className="flex items-center gap-2 text-sm font-medium text-gray-900">
                <Clock3 className="h-4 w-4 text-gray-400" aria-hidden="true" />
                <span>{formatDateTime(constraint.start_datetime)}</span>
            </div>
            <div className="pl-6 text-sm text-gray-500">au {formatDateTime(constraint.end_datetime)}</div>
        </>
    );
}

function ConstraintRow({ constraint, validatorId, onValidated }) {
    const userName = getUserName(constraint.user);

    return (
        <Fragment>
            <tr className="border-b border-gray-100 last:border-0 hover:bg-gray-50/70">
                <td className="px-5 py-4 align-top">
                    <ValidationActions constraint={constraint} validatorId={validatorId} onValidated={onValidated} />
                </td>
                <td className="px-5 py-4 align-top">
                    <Link
                        href={route('constraintsValidator.history', { user: constraint.user?.id })}
                        className="inline-flex items-center gap-2 font-medium text-indigo-700 hover:text-indigo-900 hover:underline"
                    >
                        <UserRound className="h-4 w-4 shrink-0" aria-hidden="true" />
                        <span>{userName}</span>
                    </Link>
                </td>
                <td className="px-5 py-4 align-top">
                    <span className="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                        {getConstraintTypeName(constraint)}
                    </span>
                </td>
                <td className="whitespace-nowrap px-5 py-4 align-top">
                    <ConstraintMeta constraint={constraint} />
                </td>
                <td className="px-5 py-4 align-top">
                    <WeightIndicator weight={constraint.weight} />
                </td>
                <td className="max-w-md px-5 py-4 align-top text-sm leading-6 text-gray-600">{constraint.comment || 'Aucune raison indiquée.'}</td>
            </tr>
        </Fragment>
    );
}

function EmptyState() {
    return (
        <div className="grid justify-items-center gap-3 px-6 py-16 text-center">
            <div className="flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <Check className="h-7 w-7" aria-hidden="true" />
            </div>
            <div>
                <h2 className="font-semibold text-gray-900">Tout est validé</h2>
                <p className="mt-1 text-sm text-gray-500">Aucune contrainte ne nécessite votre attention.</p>
            </div>
        </div>
    );
}

export default function Index({ constraints: initialConstraints = [], schedule = null, validatorId }) {
    const [constraints, setConstraints] = useState(initialConstraints);
    const [sort, setSort] = useState('dateAsc');

    const sortedConstraints = useMemo(() => {
        return [...constraints].sort((first, second) => compareConstraints(first, second, sort));
    }, [constraints, sort]);

    function removeValidatedConstraint(constraintId) {
        setConstraints((currentConstraints) => currentConstraints.filter(({ id }) => id !== constraintId));
    }

    return (
        <AuthenticatedLayout>
            <Head title="Validation de contraintes" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                        <div className="max-w-2xl">
                            <div className="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-indigo-600">
                                <ClipboardCheck className="h-5 w-5" aria-hidden="true" />
                                <span>Centre de validation</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Contraintes à valider</h1>
                            <p className="mt-2 text-sm leading-6 text-gray-500">
                                Examinez les demandes de contraintes et approuvez-les ou refusez-les en un clic.
                            </p>
                        </div>

                        <div className="flex flex-wrap gap-2">
                            <Link
                                href={route('constraintsValidator.history')}
                                className="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <History className="h-4 w-4" aria-hidden="true" />
                                Historique
                            </Link>
                            <Link
                                href={route('constraintsValidator.history', { status: 1 })}
                                className="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm font-medium text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                            >
                                <CheckCircle2 className="h-4 w-4" aria-hidden="true" />
                                Approuvées
                            </Link>
                            <Link
                                href={route('constraintsValidator.history', { status: 2 })}
                                className="inline-flex items-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-sm font-medium text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2"
                            >
                                <XCircle className="h-4 w-4" aria-hidden="true" />
                                Refusées
                            </Link>
                        </div>
                    </div>

                    <div className="grid gap-4 sm:grid-cols-3">
                        <div className="rounded-xl border border-indigo-100 bg-indigo-50 p-5">
                            <div className="flex items-center gap-2 text-sm font-medium text-indigo-700">
                                <List className="h-4 w-4" aria-hidden="true" />
                                En attente
                            </div>
                            <p className="mt-2 text-3xl font-semibold text-indigo-950">{constraints.length}</p>
                            <p className="mt-1 text-sm text-indigo-700">contrainte{constraints.length === 1 ? '' : 's'} à traiter</p>
                        </div>
                        {schedule ? (
                            <div className="flex items-start gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:col-span-2">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                    <CalendarRange className="h-5 w-5" aria-hidden="true" />
                                </div>
                                <div className="min-w-0">
                                    <p className="text-sm font-medium text-gray-500">Période filtrée</p>
                                    <p className="mt-1 font-semibold text-gray-900">
                                        Du {formatDate(schedule.start_date)} au {formatDate(schedule.end_date)}
                                    </p>
                                    <div className="mt-2 flex flex-wrap gap-x-4 gap-y-2 text-sm">
                                        <Link
                                            href={route('constraintsValidator.index')}
                                            className="inline-flex items-center gap-1.5 text-indigo-700 hover:underline"
                                        >
                                            <X className="h-4 w-4" aria-hidden="true" />
                                            Retirer le filtre
                                        </Link>
                                        <Link
                                            href={route('schedules.show', schedule.id)}
                                            className="inline-flex items-center gap-1.5 text-gray-600 hover:text-gray-900 hover:underline"
                                        >
                                            <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                                            Retour à l’horaire
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-start gap-3 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:col-span-2">
                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                    <UserRound className="h-5 w-5" aria-hidden="true" />
                                </div>
                                <div>
                                    <p className="font-semibold text-gray-900">Toutes les périodes</p>
                                    <p className="mt-1 text-sm text-gray-500">La liste inclut toutes les contraintes en attente de validation.</p>
                                </div>
                            </div>
                        )}
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex flex-col gap-4 border-b border-gray-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                            <div>
                                <h2 className="font-semibold text-gray-900">Demandes en attente</h2>
                                <p className="mt-1 text-sm text-gray-500">
                                    {constraints.length} résultat{constraints.length === 1 ? '' : 's'}
                                </p>
                            </div>
                            <label className="flex items-center gap-3 text-sm text-gray-600">
                                <span className="font-medium">Trier par</span>
                                <select
                                    value={sort}
                                    onChange={(event) => setSort(event.target.value)}
                                    className="rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-9 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                >
                                    {sortOptions.map((option) => (
                                        <option key={option.value} value={option.value}>
                                            {option.label}
                                        </option>
                                    ))}
                                </select>
                            </label>
                        </div>

                        {sortedConstraints.length === 0 ? (
                            <EmptyState />
                        ) : (
                            <>
                                <div className="overflow-x-auto">
                                    <table className="w-full min-w-[920px] text-left text-sm">
                                        <thead className="bg-gray-50/80 text-xs uppercase tracking-wider text-gray-500">
                                            <tr>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Validation
                                                </th>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Utilisateur
                                                </th>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Type
                                                </th>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Période
                                                </th>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Importance
                                                </th>
                                                <th scope="col" className="px-5 py-3 font-semibold">
                                                    Raison
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {sortedConstraints.map((constraint) => (
                                                <ConstraintRow
                                                    key={constraint.id}
                                                    constraint={constraint}
                                                    validatorId={validatorId}
                                                    onValidated={removeValidatedConstraint}
                                                />
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </>
                        )}
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
