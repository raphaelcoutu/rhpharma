import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link } from '@inertiajs/react';
import { format, parseISO } from 'date-fns';
import { fr } from 'date-fns/locale';
import { ArrowLeft, CheckCircle2, ClipboardCheck, UserRound, XCircle } from 'lucide-react';

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

function StatusBadge({ status }) {
    const isApproved = Number(status) === 1;

    return (
        <span
            className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ${isApproved ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}`}
        >
            {isApproved ? <CheckCircle2 className="h-3.5 w-3.5" aria-hidden="true" /> : <XCircle className="h-3.5 w-3.5" aria-hidden="true" />}
            {isApproved ? 'Approuvée' : 'Refusée'}
        </span>
    );
}

function HistoryRow({ constraint }) {
    return (
        <>
            <tr className="border-b border-gray-100 last:border-0 hover:bg-gray-50/70">
                <td className="px-5 py-4 align-top">
                    <div className="grid gap-2">
                        <span className="font-medium text-gray-900">{formatDateTime(constraint.updated_at)}</span>
                        <StatusBadge status={constraint.status} />
                        <span className="text-xs text-gray-500">par {getUserName(constraint.validator)}</span>
                    </div>
                </td>
                <td className="px-5 py-4 align-top">
                    <Link
                        href={route('constraintsValidator.history', { user: constraint.user?.id })}
                        className="inline-flex items-center gap-2 font-medium text-indigo-700 hover:underline"
                    >
                        <UserRound className="h-4 w-4" aria-hidden="true" />
                        {getUserName(constraint.user)}
                    </Link>
                </td>
                <td className="whitespace-nowrap px-5 py-4 align-top text-sm text-gray-600">
                    <div>{formatDateTime(constraint.start_datetime)}</div>
                    <div className="mt-1 text-gray-400">au {formatDateTime(constraint.end_datetime)}</div>
                </td>
                <td className="max-w-md px-5 py-4 align-top text-sm leading-6 text-gray-600">{constraint.comment || 'Aucune raison indiquée.'}</td>
            </tr>
        </>
    );
}

export default function History({ constraints = [] }) {
    return (
        <AuthenticatedLayout>
            <Head title="Historique des contraintes" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-indigo-600">
                                <ClipboardCheck className="h-5 w-5" aria-hidden="true" />
                                <span>Traçabilité</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Historique des contraintes</h1>
                            <p className="mt-2 text-sm leading-6 text-gray-500">Les 100 dernières contraintes approuvées ou refusées.</p>
                        </div>
                        <Link
                            href={route('constraintsValidator.index')}
                            className="inline-flex items-center gap-2 self-start rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:self-auto"
                        >
                            <ArrowLeft className="h-4 w-4" aria-hidden="true" />
                            Retour aux contraintes à valider
                        </Link>
                    </div>

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        {constraints.length === 0 ? (
                            <div className="px-6 py-16 text-center text-sm text-gray-500">Aucun historique de validation.</div>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full min-w-[760px] text-left text-sm">
                                    <thead className="bg-gray-50/80 text-xs uppercase tracking-wider text-gray-500">
                                        <tr>
                                            <th scope="col" className="px-5 py-3 font-semibold">
                                                Validation
                                            </th>
                                            <th scope="col" className="px-5 py-3 font-semibold">
                                                Utilisateur
                                            </th>
                                            <th scope="col" className="px-5 py-3 font-semibold">
                                                Période
                                            </th>
                                            <th scope="col" className="px-5 py-3 font-semibold">
                                                Raison
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {constraints.map((constraint) => (
                                            <HistoryRow key={constraint.id} constraint={constraint} />
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}
                    </section>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
