import PrimaryButton from '@/components/primary-button';
import SecondaryButton from '@/components/secondary-button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthenticatedLayout from '@/layouts/authenticated-layout';
import { Head, Link, useForm } from '@inertiajs/react';
import { AlertTriangle, CalendarRange, CheckCircle2, CloudDownload, LoaderCircle, Settings2, UserRound } from 'lucide-react';

function getToday() {
    const date = new Date();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${date.getFullYear()}-${month}-${day}`;
}

function ImportedUsers({ users }) {
    if (!users.length) {
        return null;
    }

    return (
        <div className="rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm text-indigo-950">
            <div className="flex items-center gap-2 font-semibold text-indigo-800">
                <UserRound className="h-4 w-4" />
                Nouveaux utilisateurs ajoutés
            </div>
            <ul className="mt-3 grid gap-2 sm:grid-cols-2">
                {users.map((user) => (
                    <li key={user.Id} className="rounded-md bg-white/70 px-3 py-2">
                        <span className="font-medium">
                            {user.LastName}, {user.FirstName}
                        </span>
                        <span className="mt-0.5 block text-xs text-indigo-700">Azure Id: {user.Id}</span>
                    </li>
                ))}
            </ul>
        </div>
    );
}

function ImportedConstraintTypes({ constraintTypes }) {
    if (!constraintTypes.length) {
        return null;
    }

    return (
        <div className="rounded-lg border border-indigo-100 bg-indigo-50 p-4 text-sm text-indigo-950">
            <div className="flex items-center gap-2 font-semibold text-indigo-800">
                <Settings2 className="h-4 w-4" />
                Nouveaux types de contraintes ajoutés
            </div>
            <ul className="mt-3 grid gap-2">
                {constraintTypes.map((constraintType) => (
                    <li key={constraintType.Id} className="rounded-md bg-white/70 px-3 py-2">
                        <span className="font-medium">{constraintType.Name}</span>
                        <span className="mt-0.5 block text-xs text-indigo-700">Azure Id: {constraintType.Id}</span>
                        {constraintType.Description && <span className="mt-1 block text-xs text-gray-600">{constraintType.Description}</span>}
                    </li>
                ))}
            </ul>
        </div>
    );
}

function MissingUsers({ users }) {
    if (!users.length) {
        return null;
    }

    return (
        <div className="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-950">
            <div className="flex items-center gap-2 font-semibold text-amber-800">
                <AlertTriangle className="h-4 w-4" />
                Utilisateurs à vérifier
            </div>
            <p className="mt-1 text-amber-800">Ces utilisateurs n&apos;ont pas pu être ajoutés depuis Azure.</p>
            <ul className="mt-3 grid gap-2 sm:grid-cols-2">
                {users.map((user) => (
                    <li key={user.Id} className="rounded-md bg-white/70 px-3 py-2">
                        <span className="font-medium">
                            {user.LastName}, {user.FirstName}
                        </span>
                        <span className="mt-0.5 block text-xs text-amber-700">Azure Id: {user.Id}</span>
                    </li>
                ))}
            </ul>
        </div>
    );
}

export default function Index({ status, newUsers = [], newConstraintTypes = [], missingUsers = [] }) {
    const { data, setData, get, processing } = useForm({
        start: getToday(),
        end: getToday(),
    });

    const formIsValid = Boolean(data.start && data.end && data.start <= data.end);

    function submit(event) {
        event.preventDefault();

        if (!formIsValid) {
            return;
        }

        get(route('constraintImporter.import'), {
            preserveScroll: true,
        });
    }

    return (
        <AuthenticatedLayout>
            <Head title="Importer des contraintes" />

            <div className="py-10 sm:py-12">
                <div className="mx-auto max-w-5xl space-y-8 px-4 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <div className="flex items-center gap-3 text-indigo-600">
                                <CloudDownload className="h-5 w-5" />
                                <span className="text-sm font-semibold uppercase tracking-wider">Synchronisation</span>
                            </div>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight text-gray-900">Importer des contraintes</h1>
                            <p className="mt-2 max-w-2xl text-sm text-gray-500">
                                Récupérez les contraintes enregistrées dans Azure pour une période donnée.
                            </p>
                        </div>
                        <SecondaryButton as={Link} href={route('schedules.index')}>
                            Retour aux horaires
                        </SecondaryButton>
                    </div>

                    {status && (
                        <div
                            className="flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800"
                            role="status"
                        >
                            <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0" />
                            <span>{status}</span>
                        </div>
                    )}

                    <section className="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div className="flex items-start gap-4 border-b border-gray-100 px-5 py-5 sm:px-6">
                            <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <CalendarRange className="h-5 w-5" />
                            </div>
                            <div>
                                <h2 className="font-semibold text-gray-900">Choisir la période</h2>
                                <p className="mt-1 text-sm text-gray-500">Les dates incluses seront demandées à Azure.</p>
                            </div>
                        </div>

                        <form onSubmit={submit} className="space-y-6 p-5 sm:p-6">
                            <div className="grid gap-5 sm:grid-cols-2">
                                <div>
                                    <Label htmlFor="start">Début</Label>
                                    <Input
                                        id="start"
                                        type="date"
                                        value={data.start}
                                        className="mt-2"
                                        onChange={(event) => setData('start', event.target.value)}
                                        required
                                    />
                                </div>
                                <div>
                                    <Label htmlFor="end">Fin</Label>
                                    <Input
                                        id="end"
                                        type="date"
                                        value={data.end}
                                        className="mt-2"
                                        onChange={(event) => setData('end', event.target.value)}
                                        required
                                    />
                                </div>
                            </div>

                            {!formIsValid && data.start && data.end && (
                                <p className="text-sm text-red-600" role="alert">
                                    La date de début doit être antérieure ou égale à la date de fin.
                                </p>
                            )}

                            <div className="flex flex-col gap-4 border-t border-gray-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <p className="max-w-xl text-sm text-gray-500">
                                    L&apos;import remplace les contraintes actuellement enregistrées dans l&apos;application.
                                </p>
                                <PrimaryButton type="submit" disabled={!formIsValid || processing} className="justify-center gap-2 sm:shrink-0">
                                    {processing ? <LoaderCircle className="h-4 w-4 animate-spin" /> : <CloudDownload className="h-4 w-4" />}
                                    {processing ? 'Importation...' : 'Importer'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </section>

                    {(newUsers.length > 0 || newConstraintTypes.length > 0 || missingUsers.length > 0) && (
                        <section className="space-y-4">
                            <div>
                                <h2 className="font-semibold text-gray-900">Détails de la synchronisation</h2>
                                <p className="mt-1 text-sm text-gray-500">Les éléments détectés pendant l&apos;import sont listés ici.</p>
                            </div>
                            <div className="grid gap-4">
                                <ImportedUsers users={newUsers} />
                                <ImportedConstraintTypes constraintTypes={newConstraintTypes} />
                                <MissingUsers users={missingUsers} />
                            </div>
                        </section>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
